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
        user_name = data.get('user_name', 'Pelanggan')
        laravel_context = data.get('context', '')
        rental_id = str(data.get('rental_id', '1'))
        raw_history = data.get('history', [])

        # Retrieval: Menaikkan k=5 agar data dari MySQL tidak tertutup oleh file .txt
        docs = vector_store.similarity_search(user_input, k=10, filter={"rental_id": rental_id})
        source_knowledge = "\n".join([d.page_content for d in docs])

        is_new_conversation = len(raw_history) == 0

        # SYSTEM PROMPT: Dioptimasi untuk pengakuan Cabang/Lokasi
        messages = [
            {
                "role": "system",
                "content": f"""Anda adalah asisten virtual rental mobil yang profesional. 
                Nama user: {user_name}.

                DATA REAL-TIME (STOK & UNIT):
                {laravel_context}
                
                PENGETAHUAN CABANG & ATURAN (RAG):
                {source_knowledge}

                ATURAN PENGGUNAAN NAMA & IDENTITAS:
                1. Jika ini awal percakapan (is_new: {is_new_conversation}), sapa {user_name} dengan ramah.
                2. Jika sudah dalam percakapan, JANGAN panggil nama {user_name} kecuali ditanya 'Siapa saya?'.
                3. JANGAN menyebutkan nama brand rental (seperti 'FZ Rent' atau 'Berkah Rent') secara spesifik. Cukup sebut 'kami'.

                INSTRUKSI LOKASI & CABANG (KRUSIAL):
                1. Jika dalam PENGETAHUAN CABANG disebutkan ada lokasi di kota tertentu (contoh: Jakarta), Anda WAJIB mengakui bahwa kami memiliki cabang di sana.
                2. Prioritaskan data lokasi yang ditarik dari database. Jika ada, jangan katakan 'tidak ada'.
                3. Fokus jawaban pada stok tersedia: {laravel_context}.
                4. Gunakan SOP/Aturan dari: {source_knowledge}.
                5. Jawab langsung ke inti tanpa basa-basi."""

                """
                INSTRUKSI FILTER OTOMATIS:
                1. Jika user meminta kriteria tertentu (contoh: 'di bawah 300rb', 'mobil matic', atau 'kapasitas 7 orang'), Anda WAJIB memfilter DATA STOK MOBIL SAAT INI.
                2. Bandingkan angka harga yang diminta user dengan harga yang ada di data.
                3. Jika kriteria tidak ditemukan di satu kota tapi ada di kota lain, beri tahu user lokasi ketersediaannya.
                4. Jika tidak ada yang cocok sama sekali, sarankan unit terdekat dengan kriteria tersebut.
                """

                """
                INSTRUKSI KALKULASI BIAYA:
                1. Jika user menyebutkan durasi sewa, Anda WAJIB menghitung total biaya secara otomatis.
                2. Format jawaban harus menyertakan: [Harga per hari] x [Jumlah hari] = [Total].
                3. Ingatkan user tentang biaya tambahan jika mereka menyebutkan penggunaan ke luar kota (sesuai Syarat Sewa).
                4. Gunakan bahasa yang tegas namun tetap membantu.
                """
            }
        ]
        
        # Masukkan History Percakapan
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
        return jsonify({"error": "Sistem sedang sibuk memproses permintaan."}), 500
        
if __name__ == "__main__":
    print("\n🚀 MESIN AI GROQ AKTIF!")
    print("📍 Menunggu perintah dari Laravel di port 5000...")
    app.run(host='0.0.0.0', port=5000, debug=False)