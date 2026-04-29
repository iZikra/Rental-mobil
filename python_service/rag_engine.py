import os
import sys

try:
    __import__('pysqlite3')
    sys.modules['sqlite3'] = sys.modules.pop('pysqlite3')
except ImportError:
    pass

import json
from flask import Flask, request, jsonify
from flask_cors import CORS
from groq import Groq
from dotenv import load_dotenv
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_chroma import Chroma

load_dotenv(override=True)
app = Flask(__name__)
CORS(app)

# --- KONFIGURASI RAG ---
DB_DIR = os.path.join(os.path.dirname(__file__), "chroma_db")
EMBEDDING_MODEL = "sentence-transformers/all-MiniLM-L6-v2"
embeddings = HuggingFaceEmbeddings(model_name=EMBEDDING_MODEL)

# Inisialisasi Vector Store (ChromaDB)
if os.path.exists(DB_DIR):
    db = Chroma(persist_directory=DB_DIR, embedding_function=embeddings)
    # Pemanasan model embedding agar query pertama lebih cepat
    embeddings.embed_query("pemanasan")
    print(f"OK: ChromaDB loaded from {DB_DIR}")
else:
    db = None
    print(f"Warning: ChromaDB directory not found at {DB_DIR}")

client = Groq(api_key=os.getenv("GROQ_API_KEY"))

def get_relevant_context(user_input, rental_id="global"):
    """
    Retrieve relevant documents from ChromaDB.
    """
    if not db:
        return ""

    query_embedding = embeddings.embed_query(user_input)
    
    # Ambil hasil pencarian (k=5)
    if str(rental_id) != "global":
        results = db.similarity_search_by_vector(
            embedding=query_embedding, 
            k=5, 
            filter={"rental_id": str(rental_id)}
        )
        # Tambahkan sedikit konteks global jika perlu
        global_results = db.similarity_search_by_vector(
            embedding=query_embedding, 
            k=2, 
            filter={"rental_id": "global"}
        )
        results.extend(global_results)
    else:
        results = db.similarity_search_by_vector(
            embedding=query_embedding,
            k=5
        )

    context_parts = []
    for doc in results:
        doc_type = doc.metadata.get('doc_type', 'info')
        context_parts.append(f"[{doc_type.upper()}]: {doc.page_content}")

    return "\n".join(context_parts)

@app.route('/', methods=['GET'])
def root():
    return "RAG Engine Active - Pure Semantic Search & Chat", 200

@app.route('/health', methods=['GET'])
def health():
    return jsonify({"status": "ok", "service": "rag_engine"}), 200

@app.route('/search', methods=['POST'])
def search():
    try:
        data = request.json
        query = data.get('query', '')
        stock_context = data.get('context', '')
        rental_id = str(data.get('rental_id', '1'))

        if not query.strip():
            return jsonify({
                "status": "error",
                "summary": "Query tidak boleh kosong",
                "results": []
            }), 400

        # 1. RETRIEVAL (PURE RAG)
        # Langsung ambil konteks tanpa transformasi query untuk kecepatan maksimal
        semantic_context = get_relevant_context(query, rental_id)
        
        json_example = '{"results": [{"id": 1, "reason": "alasan"}], "summary": "kalimat pengantar"}'
        
        search_prompt = f"""Anda adalah asisten rental mobil.
Tugas: Rekomendasikan mobil dari DATA STOK yang paling cocok dengan permintaan user.
Gunakan data BBM untuk mencari mobil irit, dan data KURSI untuk mobil keluarga.

DATA STOK:
{stock_context}

KONTEKS TAMBAHAN:
{semantic_context}

PERMINTAAN USER: "{query}"

INSTRUKSI:
1. Berikan list mobil bernomor (1, 2, 3).
2. Format: "[Nama Mobil] dengan harga Rp [Harga]/hari".
3. Berikan alasan sangat singkat kenapa mobil itu cocok.
4. JANGAN gunakan sapaan. JANGAN bertele-tele.
5. Jika tidak ada yang cocok, katakan stok kosong.

HANYA BALAS JSON:
{json_example}"""

        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=[
                {"role": "system", "content": "Anda adalah asisten pencarian mobil berbasis RAG. Balas HANYA dengan JSON valid."},
                {"role": "user", "content": search_prompt}
            ],
            temperature=0.1,
            max_tokens=300,
            response_format={"type": "json_object"}
        )

        result = json.loads(completion.choices[0].message.content)

        if not result.get('results'):
            result['results'] = []

        return jsonify({
            "status": "success",
            "summary": result.get('summary', f'Ditemukan {len(result.get("results", []))} mobil yang relevan untuk "{query}"'),
            "source": "rag",
            "results": result.get('results', [])
        })

    except Exception as e:
        import traceback
        traceback.print_exc()
        return jsonify({
            "status": "error",
            "summary": f"Terjadi kesalahan: {str(e)}",
            "results": []
        }), 500

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.json
        user_input = data.get('question', '').strip()
        laravel_context = data.get('context', '')
        raw_history = data.get('history', [])
        user_name = data.get('user_name', '')
        rental_id = str(data.get('rental_id', 'global'))

        # Fast-track untuk sapaan sederhana (bypass LLM & Embedding agar instant)
        greetings = ['halo', 'hai', 'hi', 'p', 'ping', 'assalamualaikum', 'assalamu alaikum']
        if user_input.lower() in greetings and not raw_history:
             return jsonify({"answer": f"Halo! Ada yang bisa saya bantu hari ini?"})
        if user_input.lower() in greetings:
             return jsonify({"answer": f"Halo! Silakan, ada yang ingin dicari?"})

        # 1. RETRIEVAL (PURE RAG)
        # Ambil konteks tambahan (SOP, Denda, dll) dari ChromaDB
        semantic_context = get_relevant_context(user_input, rental_id)
            
        current_date = data.get('current_date', '2026-04-29')

        system_prompt = f"""Anda adalah asisten rental mobil yang super efisien.
Tugas: Jawab dengan sangat pendek dan langsung ke inti (maksimal 15 kata).

KONTEKS: {semantic_context}
STOK MOBIL: {laravel_context}

INSTRUKSI KETAT:
1. Maksimal 1-2 kalimat pendek.
2. TANPA basa-basi, TANPA sapaan, TANPA kalimat penutup.
3. JANGAN bertanya balik.
4. Jika stok tersedia, langsung beri format: "[Nama] - Rp [Harga]/hari".

HANYA BALAS JSON:
{{
    "is_ready": true/false,
    "car_id": "ID_MOBIL",
    "date": "YYYY-MM-DD",
    "response": "jawaban 15 kata"
}}"""

        messages = [{"role": "system", "content": system_prompt}]

        for h in raw_history[-4:]:
            if h.get('user'): messages.append({"role": "user", "content": h['user']})
            if h.get('bot'):
                bot_content = h['bot'].replace('<br>', '\n')
                messages.append({"role": "assistant", "content": bot_content})

        messages.append({"role": "user", "content": f"{user_input}\n\n(INGAT: Jawab maksimal 1-2 kalimat saja, jangan menyapa, jangan bertanya balik, langsung ke inti!)"})

        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=messages,
            temperature=0.1,
            max_tokens=250,
            response_format={"type": "json_object"}
        )

        res_ai = json.loads(completion.choices[0].message.content)
        final_answer = res_ai.get("response", "").replace('\n', '<br>')

        if res_ai.get("is_ready") and res_ai.get("car_id") and res_ai.get("date"):
            final_answer += f"<br><br>[LINK_BOOKING:{res_ai['car_id']}|{res_ai['date']}]"

        return jsonify({"answer": final_answer})

    except Exception as e:
        import traceback
        with open("debug.log", "w") as f:
            f.write(traceback.format_exc())
        traceback.print_exc()
        return jsonify({"answer": "Maaf, ada kendala teknis. Bisa ulangi?"})

if __name__ == "__main__":
    print("\n" + "="*50)
    print("PURE RAG ENGINE AKTIF!")
    print("="*50)
    print("Endpoints:")
    print("  /search - Pure RAG Semantic Search (non-chatbot)")
    print("  /chat   - RAG Chatbot Assistant")
    print("="*50)
    print("Menunggu permintaan di port 5000...\n")
    app.run(host='0.0.0.0', port=5000, debug=False)