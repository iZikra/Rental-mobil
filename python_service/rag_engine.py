import os
from flask import Flask, request, jsonify
from flask_cors import CORS
from langchain_chroma import Chroma
from langchain_huggingface import HuggingFaceEmbeddings
from groq import Groq 
from dotenv import load_dotenv

# 1. INITIALIZATION
load_dotenv()
app = Flask(__name__)
CORS(app)

# Setup Groq
client = Groq(api_key=os.getenv("GROQ_API_KEY"))

# Setup ChromaDB
DB_DIR = "chroma_db"
embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")
vector_store = Chroma(persist_directory=DB_DIR, embedding_function=embeddings)

# 2. CHAT ROUTE
@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.json
        user_input = data.get('question', '')
        user_name = data.get('user_name', 'Kakak')
        laravel_context = data.get('context', '')
        rental_id = str(data.get('rental_id', '1'))
        raw_history = data.get('history', [])

        # Retrieval
        docs = vector_store.similarity_search(user_input, k=10, filter={"rental_id": rental_id})
        source_knowledge = "\n".join([d.page_content for d in docs])

        # ==========================================
        # SYSTEM PROMPT MUTLAK (SATU PER SATU & NO SAPAAN PAKSA)
        # ==========================================
        system_prompt = f"""Anda adalah 'Customer Service' Multi Rent Platform. Anda melayani Kak {user_name}.

ATURAN GAYA BAHASA & SIFAT:
1. MIRRORING SAPAAN: Jika kalimat "{user_input}" TIDAK mengandung kata sapaan (seperti Halo, Pagi, Siang), Anda DILARANG KERAS menambahkan kata sapaan (Halo) di awal kalimat Anda. Jawab langsung ke intinya dengan sopan!
2. SATU TANYA PER BALASAN: JANGAN PERNAH menggabungkan pertanyaan kota dan spesifikasi mobil. MAKSIMAL 1 PERTANYAAN dalam satu kali balasan! Penyewa akan bingung jika ditanya banyak hal sekaligus.
3. MANUSIAWI: Ramah, santai, dilarang menyebut kata "database" atau "sistem".

DATA STOK REAL-TIME KAMI SAAT INI:
{laravel_context}
{source_knowledge}

SOP GATEKEEPER (WAJIB DITAATI BERURUTAN & SATU PER SATU):
Evaluasi pesan "{user_input}" dan riwayat obrolan. Anda harus melewati gembok ini secara berurutan:

[GEMBOK 1 - KOTA]: 
Apakah Kak {user_name} sudah menyebutkan kota tujuannya secara eksplisit? 
- Jika BELUM: Tanyakan kotanya SAJA. Balas singkat seperti: "Untuk pemakaian di kota mana Kak biar saya cek unitnya?" -> (BERHENTI MENULIS! JANGAN tambahkan pertanyaan lain!).
- Jika kota TIDAK ADA di data: Minta maaf dan sebutkan kota yang tersedia. -> (BERHENTI!)

[GEMBOK 2 - SPESIFIKASI]: 
Jika kota SUDAH jelas, CEK apakah Kak {user_name} sudah menyebutkan spesifikasi (matic / manual / jumlah penumpang)?
- Jika BELUM: Tanyakan spesifikasinya SAJA. Balas singkat seperti: "Di kota tersebut kita ready Kak. Butuhnya mobil matic, manual, atau untuk keluarga nih?" -> (BERHENTI MENULIS! JANGAN sebutkan daftar mobil!).

[GEMBOK 3 - PENAWARAN]:
Jika kota DAN spesifikasi (matic/manual) SUDAH diketik oleh penyewa, BARU Anda buka Gembok 2, lihat DATA STOK, dan tawarkan mobil yang sesuai kriteria beserta harganya secara menarik.

[GEMBOK 4 - HITUNG TOTAL]:
Jika penyewa menyebut durasi hari, hitung total harga (Harga x Hari)."""

        messages = [
            {
                "role": "system",
                "content": system_prompt
            }
        ]
        
        # Masukkan History Percakapan (Ingatan AI)
        for h in raw_history:
            if isinstance(h, dict):
                if h.get('user'): messages.append({"role": "user", "content": h['user']})
                if h.get('bot'): messages.append({"role": "assistant", "content": h['bot']})
            
        # Pertanyaan Terkini
        messages.append({"role": "user", "content": user_input})

        # Eksekusi ke Llama 3.3
        completion = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=messages,
            temperature=0.7,
            max_tokens=1024
        )

        return jsonify({"answer": completion.choices[0].message.content})

    except Exception as e:
        print(f"🔥 Error Internal Flask: {str(e)}")
        return jsonify({"error": "Sistem kami sedang memproses permintaan, mohon tunggu sebentar ya Kak."}), 500
        
if __name__ == "__main__":
    print("\n🚀 MESIN AI GROQ AKTIF (PERSONA 1 BY 1)!")
    print("📍 Menunggu perintah dari Laravel di port 5000...")
    app.run(host='0.0.0.0', port=5000, debug=False)