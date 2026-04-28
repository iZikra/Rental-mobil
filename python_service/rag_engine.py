import os
import re
import json
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
# Pastikan model embedding sama dengan saat ingestion
embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")
vector_store = Chroma(persist_directory=DB_DIR, embedding_function=embeddings)

def get_hybrid_context(query, rental_id, city=None, k=5):
    """
    Fungsi inti Hybrid Retrieval:
    1. Mencari dokumen di ChromaDB dengan metadata filtering (rental_id & city).
    2. Mendukung dokumen 'global' untuk pengetahuan umum kategori mobil.
    """
    # Build filter
    where_filter = {
        "$or": [
            {"rental_id": str(rental_id)},
            {"rental_id": "global"}
        ]
    }
    
    # Jika ada filter kota, tambahkan ke pencarian metadata
    if city:
        # ChromaDB logic: kita ingin (rental_id OR global) AND (kota == city OR doc_type != branch/car_spec)
        # Namun untuk kesederhanaan skripsi, kita ambil k yang lebih besar dan biarkan LLM menyaring,
        # ATAU kita gunakan filter rental_id sebagai filter utama yang wajib.
        pass

    try:
        # TAHAP 1: VECTOR SEARCH (RETRIEVAL)
        # Menggunakan embedding all-MiniLM-L6-v2 secara otomatis saat query
        search_results = vector_store.similarity_search(
            query, 
            k=k, 
            filter={"rental_id": {"$in": [str(rental_id), "global"]}}
        )
        return [doc.page_content for doc in search_results]
    except Exception as e:
        print(f"Retrieval Error: {e}")
        # Fallback jika filter kompleks gagal
        search_results = vector_store.similarity_search(query, k=3)
        return [doc.page_content for doc in search_results]

# 2. CHAT ROUTE (TRUE RAG)
@app.route('/', methods=['GET'])
def root():
    return "AI Engine is Running", 200

@app.route('/health', methods=['GET'])
def health():
    return jsonify({"status": "ok"}), 200

@app.route('/chat', methods=['POST'])
def chat():
    """
    Implementasi FULL RAG & HYBRID RETRIEVAL:
    1. Retrieval: Mencari dokumen kebijakan/prosedur dari ChromaDB.
    2. Augmentation: Menggabungkan Dokumen (Vektor) + Data Stok (MySQL) + History.
    3. Generation: LLM memproses konteks hybrid untuk jawaban akurat.
    """
    try:
        data = request.json
        user_input = data.get('question', '')
        laravel_context = data.get('context', '') # Data dari MySQL
        rental_id = str(data.get('rental_id', '1'))
        city = data.get('city')
        raw_history = data.get('history', [])

        # ─── TAHAP 1: RETRIEVAL (Vector Similarity Search) ──────────────────
        rag_docs = get_hybrid_context(user_input, rental_id, city)
        knowledge_context = "\n---\n".join(rag_docs) if rag_docs else "Tidak ada dokumen pengetahuan tambahan."

        # ─── TAHAP 2: CONTEXT AUGMENTATION (Hybrid: Vektor + SQL) ─────────────
        current_date = data.get('current_date', '2026-04-29')
        system_prompt = f"""Anda adalah 'Asisten Rental Mobil' yang sangat efisien dan to-the-point.
Tugas: Memberikan informasi mobil secara instan tanpa basa-basi.
Tanggal Hari Ini: {current_date}

ATURAN KERJA:
1. SAPAAN KONDISIONAL: Hanya gunakan sapaan (seperti 'Halo', 'Hai', 'Kak') JIKA DAN HANYA JIKA user menyapa terlebih dahulu dalam pesan terakhirnya. Jika user langsung bertanya atau meminta sesuatu tanpa menyapa, JANGAN gunakan sapaan sama sekali.
2. LANGSUNG KE INTI: Jawab pertanyaan dalam 1-2 kalimat saja.
3. REKOMENDASI: Jika user mencari mobil, berikan 2-3 opsi dari stok yang tersedia.
4. FORMAT BOOKING: Gunakan format [LINK_BOOKING:ID|TANGGAL] (TANGGAL dalam YYYY-MM-DD, gunakan tanggal yang diminta user atau {current_date} jika tidak ada) tepat di samping nama mobil atau saat user konfirmasi pilihan.
5. JANGAN BERTANYA BALIK yang tidak perlu. Langsung berikan informasi teknis atau link booking.

SUMBER DATA:
- Pengetahuan: {knowledge_context}
- Stok MySQL (PILIH DARI SINI): {laravel_context}
"""

        messages = [{"role": "system", "content": system_prompt}]
        
        # Tambahkan history
        for h in raw_history:
            if isinstance(h, dict):
                if h.get('user'): messages.append({"role": "user", "content": h['user']})
                if h.get('bot'): messages.append({"role": "assistant", "content": h['bot']})
        
        messages.append({"role": "user", "content": user_input})

        # ─── TAHAP 3: GENERATION (LLM) ───────────────────────────────────────
        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=messages,
            temperature=0.5,
            max_tokens=1024
        )

        answer = completion.choices[0].message.content.strip()
        return jsonify({"answer": answer})

    except Exception as e:
        print(f"Chat Error: {e}")
        return jsonify({"error": "Maaf, terjadi gangguan teknis."}), 500

@app.route('/search', methods=['POST'])
def search():
    """
    Smart Search (Hybrid RAG):
    Mengkombinasikan Vector Search (untuk pemahaman kategori) dengan SQL (untuk akurasi stok).
    """
    try:
        data = request.json
        query = data.get('query', '')
        context = data.get('context', '') # Data dari MySQL
        rental_id = str(data.get('rental_id', '1'))
        city = data.get('city')

        # Vector Retrieval untuk memahami kategori mobil yang dicari (misal: "mobil buat keluarga" -> "MPV/SUV")
        rag_docs = get_hybrid_context(query, rental_id, city, k=3)
        knowledge = "\n".join(rag_docs)

        prompt = f"""Anda adalah sistem pakar rekomendasi mobil.
Tugas: Pilih mobil dari stok MySQL berdasarkan permintaan user.

[PENGETAHUAN KATEGORI (VECTOR DB)]:
{knowledge}

[STOK MOBIL TERSEDIA (MYSQL)]:
{context}

PERMINTAAN USER: "{query}"

INSTRUKSI OUTPUT:
1. HINDARI SAPAAN (Jangan gunakan "Halo", "Hai", dll).
2. Pilih 1-3 mobil yang paling relevan dari [STOK MOBIL TERSEDIA].
3. Jika stok kosong, tulis "Maaf, saat ini stok sedang kosong." di bagian summary.
4. Berikan alasan sangat singkat.
5. Balas HANYA JSON:
{{"results": [{"id": <id>, "reason": "<alasan>", "scores": <skor>}], "summary": "<kalimat_pengantar_singkat_tanpa_sapaan>"}}"""

        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=[{"role": "user", "content": prompt}],
            temperature=0.1,
            max_tokens=800
        )

        raw = completion.choices[0].message.content.strip()
        json_match = re.search(r'\{.*\}', raw, re.DOTALL)
        if json_match:
            ai_result = json.loads(json_match.group())
            return jsonify(ai_result)
            
        return jsonify({"results": [], "summary": "Tidak menemukan rekomendasi yang cocok."})

    except Exception as e:
        print(f"Search Error: {e}")
        return jsonify({"results": [], "summary": "Gagal memproses pencarian."}), 500

@app.route('/admin-assist', methods=['POST'])
def admin_assist():
    """Asisten cerdas untuk Mitra/Admin menggunakan Hybrid RAG."""
    try:
        data = request.json
        question = data.get('question', '')
        context = data.get('context', '') # Data operasional mitra

        rag_docs = get_hybrid_context(question, "global", k=3)
        knowledge = "\n".join(rag_docs)

        prompt = f"""Anda adalah Penasihat Bisnis Rental Mobil.
Gunakan pengetahuan industri dan data operasional untuk membantu admin.

PENGETAHUAN INDUSTRI:
{knowledge}

DATA OPERASIONAL ANDA:
{context}

PERTANYAAN: {question}"""

        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=[{"role": "user", "content": prompt}],
            temperature=0.3
        )
        return jsonify({"answer": completion.choices[0].message.content.strip()})

    except Exception as e:
        print(f"Admin Assist Error: {e}")
        return jsonify({"answer": "Maaf, asisten tidak dapat merespons."}), 500

if __name__ == "__main__":
    print("\n" + "="*50)
    print("🚀 HYBRID RAG ENGINE IS ACTIVE")
    print("Mode: Pure RAG (Vector Search + SQL Context)")
    print("Embedding: sentence-transformers/all-MiniLM-L6-v2")
    print("Port: 5000")
    print("="*50 + "\n")
    app.run(host='0.0.0.0', port=5000, debug=False)
