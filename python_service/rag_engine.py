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
    [RAG COMPONENT: RETRIEVAL PIPELINE]
    Fungsi ini melakukan pencarian dokumen relevan menggunakan Vector Similarity Search
    dengan dukungan Metadata Filtering.
    """
    if not db:
        return ""

    # [RAG COMPONENT: EMBEDDING TRANSFORMATION]
    # Mengubah query teks menjadi vektor (MiniLM-L6-v2)
    query_embedding = embeddings.embed_query(user_input)
    
    # Deteksi Kota untuk Metadata Filtering (Simple detection)
    # Anda bisa memperluas daftar ini sesuai kebutuhan
    available_cities = ['pekanbaru', 'jakarta', 'medan', 'padang']
    detected_city = next((city for city in available_cities if city in user_input.lower()), None)

    # [RAG COMPONENT: METADATA FILTERING LOGIC]
    # Filter utama: rental_id
    # Kita juga bisa menambahkan filter kota jika terdeteksi
    search_filter = {"rental_id": {"$in": [str(rental_id), "global"]}}
    if detected_city:
        # Jika ada kota, kita cari yang (rental_id=X OR global) DAN (kota=detected_city OR kota="")
        # Namun ChromaDB metadata filtering memiliki keterbatasan sintaks OR/AND kompleks,
        # jadi kita prioritaskan filter rental_id dan ambil k lebih banyak untuk disaring manual
        pass

    try:
        # [RAG COMPONENT: VECTOR SIMILARITY SEARCH]
        # Mencari k-Nearest Neighbors (k-NN) berdasarkan kemiripan kosinus vektor
        results = db.similarity_search_by_vector(
            embedding=query_embedding, 
            k=6, 
            filter=search_filter
        )
        
        # Saring hasil berdasarkan kota jika ada
        final_results = []
        for doc in results:
            doc_city = doc.metadata.get('kota', '').lower()
            if not detected_city or not doc_city or doc_city == detected_city:
                final_results.append(doc)

        context_parts = []
        for doc in final_results[:5]: # Ambil top 5 setelah filter
            doc_type = doc.metadata.get('doc_type', 'info')
            context_parts.append(f"[{doc_type.upper()}]: {doc.page_content}")

        return "\n".join(context_parts)
    except Exception as e:
        print(f"Retrieval Error: {e}")
        return "Gagal mengambil data dari Vector Database."

@app.route('/', methods=['GET'])
def root():
    return "RAG Engine Active - Full Implementation", 200

@app.route('/health', methods=['GET'])
def health():
    return jsonify({"status": "ok", "service": "rag_engine"}), 200

@app.route('/search', methods=['POST'])
def search():
    """
    [RAG COMPONENT: SMART SEARCH / HYBRID SEARCH]
    Menggabungkan Vector Search dengan data stok (MySQL context).
    """
    try:
        data = request.json
        if not data: return jsonify({"error": "No data"}), 400
        
        query = data.get('query', '')
        stock_context = data.get('context', '')
        rental_id = str(data.get('rental_id', '1'))

        if not query.strip():
            return jsonify({"status": "error", "summary": "Query kosong", "results": []}), 400

        # TAHAP 1: VECTOR RETRIEVAL
        semantic_context = get_relevant_context(query, rental_id)
        
        json_example = '{"results": [{"id": 1, "reason": "alasan"}], "summary": "kalimat pengantar"}'
        
        # [RAG COMPONENT: CONTEXT AUGMENTATION]
        # Menggabungkan Knowledge (Vector) + Inventory (MySQL) ke dalam Prompt
        search_prompt_template = """Anda adalah asisten rental mobil profesional. 
Format output Anda harus selalu dalam bentuk JSON.

[PENGETAHUAN PERUSAHAAN (VECTOR DB)]:
{{SEMANTIC_CONTEXT}}

[STOK MOBIL REAL-TIME (MYSQL)]:
{{STOCK_CONTEXT}}

[PERMINTAAN USER]: "{{QUERY}}"

INSTRUKSI:
1. Rekomendasikan 1-3 mobil dari [STOK MOBIL REAL-TIME].
2. Gunakan [PENGETAHUAN PERUSAHAAN] untuk menjelaskan keunggulan/tipe mobil.
3. JANGAN sebutkan nama mitra rental spesifik (seperti FZ Rent, AA Rent, dll).
4. Balas HANYA dengan format JSON:
{{JSON_EXAMPLE}}"""
        
        search_prompt = search_prompt_template.replace("{{SEMANTIC_CONTEXT}}", semantic_context)\
                                              .replace("{{STOCK_CONTEXT}}", stock_context)\
                                              .replace("{{QUERY}}", query)\
                                              .replace("{{JSON_EXAMPLE}}", json_example)

        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=[{"role": "user", "content": search_prompt}],
            temperature=0.1,
            max_tokens=400,
            response_format={"type": "json_object"}
        )

        result = json.loads(completion.choices[0].message.content)
        return jsonify({
            "status": "success",
            "summary": result.get('summary', 'Hasil pencarian RAG.'),
            "source": "hybrid_rag", # Menunjukkan ini adalah Hybrid RAG
            "results": result.get('results', [])
        })

    except Exception as e:
        print(f"Search Error: {e}")
        return jsonify({"status": "error", "summary": "Gagal memproses RAG.", "results": []}), 500

@app.route('/chat', methods=['POST'])
def chat():
    """
    [RAG COMPONENT: CONVERSATIONAL RAG]
    """
    try:
        data = request.json
        if not data: return jsonify({"answer": "Error: No data sent"}), 400
        
        user_input = data.get('question', '').strip()
        laravel_context = data.get('context', '')
        raw_history = data.get('history', [])
        rental_id = str(data.get('rental_id', 'global'))

        # TAHAP 1: VECTOR RETRIEVAL (Similarity Search)
        semantic_context = get_relevant_context(user_input, rental_id)
            
        current_date = data.get('current_date', '2026-04-29')

        # [RAG COMPONENT: HYBRID GENERATION]
        # Gunakan string biasa (bukan f-string) untuk menghindari 'Invalid format specifier'
        system_prompt_template = """Anda adalah asisten rental mobil yang cerdas dan ramah. 
Format jawaban Anda harus selalu dalam bentuk JSON.

[PENGETAHUAN (VEKTOR)]: {{SEMANTIC_CONTEXT}}
[STOK REAL-TIME (SQL)]: {{LARAVEL_CONTEXT}}

INSTRUKSI:
1. Jawab maksimal 2 kalimat pendek.
2. Jika user menyapa (seperti "Halo" atau "Hai"), balaslah dengan sapaan yang ramah juga.
3. JANGAN sebutkan nama mitra rental spesifik (seperti FZ Rent, dsb).
4. Langsung ke inti jawaban.

HANYA BALAS JSON:
{
    "response": "isi jawaban Anda"
}"""
        system_prompt = system_prompt_template.replace("{{SEMANTIC_CONTEXT}}", semantic_context).replace("{{LARAVEL_CONTEXT}}", laravel_context)

        messages = [{"role": "system", "content": system_prompt}]
        for h in raw_history[-4:]:
            if h.get('user'): messages.append({"role": "user", "content": h['user']})
            if h.get('bot'): messages.append({"role": "assistant", "content": str(h['bot'])})
        
        messages.append({"role": "user", "content": user_input})

        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=messages,
            temperature=0.3,
            max_tokens=200,
            response_format={"type": "json_object"}
        )

        res_ai = json.loads(completion.choices[0].message.content)
        return jsonify({"answer": res_ai.get("response", "Maaf, sistem RAG gagal merespons.")})

    except Exception as e:
        print(f"Chat Error: {e}")
        return jsonify({"answer": "Chatbot sedang gangguan, mohon ulangi beberapa saat lagi."}), 500

if __name__ == "__main__":
    print("\n" + "="*50)
    print("🚀 FULL HYBRID RAG ENGINE IS ACTIVE")
    print("Components: Vector Search, Metadata Filter, Hybrid Context")
    print("="*50 + "\n")
    app.run(host='0.0.0.0', port=5000, debug=False)