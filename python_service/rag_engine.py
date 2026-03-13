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
        user_name = data.get('user_name', 'Pelanggan') # Akan menerima 'Abil'
        laravel_context = data.get('context', '')
        rental_id = str(data.get('rental_id', '1'))
        raw_history = data.get('history', [])

        # Retrieval dari ChromaDB (SOP/Aturan)
        docs = vector_store.similarity_search(user_input, k=3, filter={"rental_id": rental_id})
        source_knowledge = "\n".join([d.page_content for d in docs])

        # SYSTEM PROMPT: ANONIM & PERSONAL (Sesuai Permintaan)
        # Cek apakah ini chat pertama atau sudah ada percakapan sebelumnya
        is_new_conversation = len(raw_history) == 0

        messages = [
            {
                "role": "system",
                "content": f"""Anda adalah asisten virtual rental mobil yang profesional. 
                Nama user: {user_name}.
                
                ATURAN PENGGUNAAN NAMA:
                1. Jika ini awal percakapan (is_new: {is_new_conversation}), sapa {user_name} dengan ramah.
                2. Jika sudah dalam percakapan (is_new: False), JANGAN panggil nama {user_name} lagi agar percakapan terasa natural. 

                INSTRUKSI KERJA:
                1. JANGAN menyebutkan nama spesifik rental mana pun.
                2. Fokus pada stok: {laravel_context}.
                3. Gunakan SOP: {source_knowledge}.
                4. Jika ditanya 'Siapa saya?', barulah sebutkan nama {user_name}.
                5. Jawab langsung ke inti pertanyaan tanpa basa-basi berlebih."""
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
    print("\n🚀 MESIN AI GROQ ZIKRALLAH AKTIF!")
    print("📍 Menunggu perintah dari Laravel di port 5000...")
    app.run(host='0.0.0.0', port=5000, debug=False)