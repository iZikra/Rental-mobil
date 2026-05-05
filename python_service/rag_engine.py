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

# --- MUAT KONFIGURASI ENV TERPUSAT ---
# 1. Coba muat .env utama dari root project Laravel (Centralized Config)
root_env_path = os.path.join(os.path.dirname(__file__), '..', '.env')
if os.path.exists(root_env_path):
    load_dotenv(dotenv_path=root_env_path)  # Tanpa override agar HF Secrets tetap prioritas

# Muat .env lokal jika ada (TIDAK override env var yang sudah ada dari HF Secrets)
load_dotenv()  # Tanpa override=True
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

def get_relevant_context(user_input, rental_id="global", kota=None):
    """
    Retrieve relevant documents from ChromaDB.
    """
    if not db:
        return ""

    # Build Filter Parameters (ChromaDB syntax)
    filter_params = {}
    conditions = []
    
    if str(rental_id) != "global":
        conditions.append({"rental_id": str(rental_id)})
        
    if kota:
        conditions.append({"kota": str(kota).lower()})
        
    if len(conditions) == 1:
        filter_params = conditions[0]
    elif len(conditions) > 1:
        filter_params = {"$and": conditions}
    else:
        filter_params = None
    
    # Ambil hasil pencarian (k=5)
    results = db.similarity_search(
        user_input,
        k=5,
        filter=filter_params
    )

    # Tambahkan sedikit konteks global jika ini adalah request spesifik rental
    if str(rental_id) != "global":
        global_results = db.similarity_search(
            user_input,
            k=2,
            filter={"rental_id": "global"}
        )
        results.extend(global_results)

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
        kota_user = data.get('kota', None)
        semantic_context = get_relevant_context(query, rental_id, kota=kota_user)
        
        search_prompt = f"""Anda adalah asisten rental mobil profesional.
Gunakan informasi di bawah ini untuk menjawab permintaan user.

DATA STOK REAL-TIME (MySQL):
{stock_context}

PENGETAHUAN PENDUKUNG (RAG):
{semantic_context}

PERMINTAAN USER: "{query}"

INSTRUKSI JAWABAN:
1. JANGAN memberikan sapaan pembuka (Halo, Selamat Siang, dll).
2. Tampilkan rekomendasi dalam format LIST BERNUMOR (1, 2, 3).
3. Setiap item list harus berisi: "[Nama Mobil] Rp [Harga]/hari".
4. Berikan alasan sangat singkat (maks 1 kalimat) kenapa mobil tersebut cocok.
5. Jika tidak ada stok yang cocok, balas dengan: {{"results": [], "summary": "Maaf, stok yang Anda cari saat ini sedang kosong."}}

HANYA BALAS DALAM FORMAT JSON BERIKUT:
{{
  "results": [
    {{"id": <id_mobil>, "reason": "<alasan_singkat>"}}
  ],
  "summary": "<kalimat_pengantar_singkat_dan_padat>"
}}"""

        completion = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=[
                {"role": "system", "content": "Anda adalah asisten pencarian mobil berbasis RAG. Balas HANYA dengan JSON valid."},
                {"role": "user", "content": search_prompt}
            ],
            temperature=0.4,
            max_tokens=1500,
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
        user_input = data.get('question', '')
        laravel_context = data.get('context', '')
        raw_history = data.get('history', [])
        user_name = data.get('user_name', '')
        rental_id = str(data.get('rental_id', 'global'))

        # 1. RETRIEVAL (PURE RAG)
        # Ambil konteks tambahan (SOP, Denda, dll) dari ChromaDB
        kota_user = data.get('kota', None)
        print(f"\n[RAG_ENGINE] Menerima pertanyaan: '{user_input}'")
        print(f"[RAG_ENGINE] Memulai pencarian semantik (Filter: rental_id={rental_id}, kota={kota_user})...")
        semantic_context = get_relevant_context(user_input, rental_id, kota=kota_user)
        print(f"[RAG_ENGINE] Hasil pencarian semantik ditemukan ({len(semantic_context)} karakter).")
            
        current_date = data.get('current_date', '2026-04-29')

        system_prompt = f"""Anda adalah asisten cerdas untuk rental mobil.
Gunakan data STOK MOBIL dan KONTEKS PENGETAHUAN untuk menjawab.

KONTEKS PENGETAHUAN (RAG):
{semantic_context}

STOK MOBIL SAAT INI (MySQL):
{laravel_context}

INSTRUKSI:
1. Jadilah asisten (Customer Service) yang ramah dan natural. Balas sapaan dengan hangat.
2. Jika ada SATU mobil yang fix ingin di-booking user, atur "is_ready": true, isi "car_id" dengan SATU ID angka saja (misal "1"), dan isi "date".
3. JIKA USER MEMINTA DAFTAR/REKOMENDASI (misal "mobil matic", "SUV"), Anda WAJIB menjabarkan SEMUA MOBIL yang cocok secara vertikal (satu mobil satu baris baru).
   WAJIB GUNAKAN FORMAT INI UNTUK SETIAP MOBIL:
   1. [Nama Mobil] Rp [Harga]/hari (Mitra: [Nama Mitra]) [LINK_BOOKING:ID|TANGGAL]
   2. [Nama Mobil] Rp [Harga]/hari (Mitra: [Nama Mitra]) [LINK_BOOKING:ID|TANGGAL]
   (Lanjutkan ke nomor 3, 4, dst. Pastikan setiap mobil memiliki tag LINK_BOOKING masing-masing secara terpisah).
4. Jika ditanya soal kriteria tertentu (misal SUV 7 kursi) tapi di STOK MOBIL tidak ada yang cocok, BERITAHU user dengan sopan bahwa stok tersebut kosong/tidak tersedia. JANGAN MENCETAK JUDUL LIST LALU MEMBIARKANNYA KOSONG.
5. Gunakan bahasa sehari-hari yang sopan. Jawab SOP berdasarkan KONTEKS PENGETAHUAN.

HANYA BALAS JSON:
{{
    "is_ready": true/false,
    "car_id": "ID_MOBIL_JIKA_DIPILIH",
    "date": "TANGGAL_JIKA_DISEPAKATI",
    "response": "Jawaban Anda"
}}"""

        messages = [{"role": "system", "content": system_prompt}]

        if user_name:
            messages[0]["content"] = f"Nama user: {user_name}\n\n" + messages[0]["content"]

        for h in raw_history[-6:]:
            if h.get('user'): messages.append({"role": "user", "content": h['user']})
            if h.get('bot'):
                bot_content = h['bot'].replace('<br>', '\n')
                messages.append({"role": "assistant", "content": bot_content})

        messages.append({"role": "user", "content": user_input})

        completion = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=messages,
            temperature=0.3,
            max_tokens=1500,
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
    port = int(os.environ.get("PORT", 5000))
    print(f"\n[RAG_ENGINE] Server siap di port {port}")
    if os.environ.get("PORT"):
        print("[RAG_ENGINE] Berjalan dalam mode Cloud/HuggingFace")
    else:
        print("[RAG_ENGINE] Berjalan dalam mode Local")
    
    app.run(host='0.0.0.0', port=port, debug=False)