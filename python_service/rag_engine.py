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
import sys
import time
import zipfile

# --- AUTO EXTRACT ZIP (UNTUK HUGGING FACE) ---
if os.path.exists("rag_data.zip"):
    print("[RAG_ENGINE] Menemukan rag_data.zip, memulai ekstraksi otomatis...", flush=True)
    try:
        with zipfile.ZipFile("rag_data.zip", 'r') as zip_ref:
            zip_ref.extractall(".")
        os.remove("rag_data.zip")
        print("[RAG_ENGINE] Ekstrak rag_data.zip berhasil dan file zip telah dihapus.", flush=True)
    except Exception as e:
        print(f"[RAG_ENGINE] Gagal mengekstrak rag_data.zip: {e}", flush=True)


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
EMBEDDING_MODEL = "intfloat/multilingual-e5-small"
embeddings = HuggingFaceEmbeddings(model_name=EMBEDDING_MODEL)

# Inisialisasi Vector Store (ChromaDB)
if os.path.exists(DB_DIR):
    db = Chroma(persist_directory=DB_DIR, embedding_function=embeddings)
    # Pemanasan model embedding agar query pertama lebih cepat
    embeddings.embed_query("query: pemanasan")
    print(f"OK: ChromaDB loaded from {DB_DIR}", flush=True)
else:
    db = None
    print(f"Warning: ChromaDB directory not found at {DB_DIR}", flush=True)

import threading

# --- KONFIGURASI GROQ API MULTIPLE KEYS ---
groq_api_keys = []
for i in range(1, 10): # Mendukung banyak key (GROQ_API_KEY_1, GROQ_API_KEY_2, dst)
    key = os.getenv(f"GROQ_API_KEY_{i}")
    if key and key.strip():
        groq_api_keys.append(key.strip())

# Fallback jika tidak ada GROQ_API_KEY_X, gunakan GROQ_API_KEY biasa
if not groq_api_keys:
    fallback_key = os.getenv("GROQ_API_KEY")
    if fallback_key and fallback_key.strip():
        groq_api_keys.append(fallback_key.strip())

if not groq_api_keys:
    print("[RAG_ENGINE] WARNING: Tidak ada GROQ API KEY yang ditemukan di .env", flush=True)
else:
    print(f"[RAG_ENGINE] Berhasil memuat {len(groq_api_keys)} GROQ API KEY untuk rotasi.", flush=True)

current_key_index = 0
key_lock = threading.Lock()

def get_next_groq_client():
    global current_key_index
    if not groq_api_keys:
        return None
    
    with key_lock:
        key = groq_api_keys[current_key_index]
        print(f"[RAG_ENGINE] -> Menggunakan GROQ API KEY ke-{current_key_index + 1} dari {len(groq_api_keys)}", flush=True)
        # Pindah ke key berikutnya (Round Robin)
        current_key_index = (current_key_index + 1) % len(groq_api_keys)
    
    return Groq(api_key=key)

def get_relevant_context(user_input, rental_id="global", kota=None):
    """
    Retrieve relevant documents from ChromaDB (Vector Search).
    """
    if not db:
        print("[RAG_ENGINE] ERROR: ChromaDB belum diinisialisasi. Pencarian vektor dibatalkan.")
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
    
    print(f"\n[RAG_ENGINE] [PRIORITAS 3: MONITORING] Memulai similarity_search ke ChromaDB untuk query: '{user_input}'", flush=True)
    
    query_text = f"query: {user_input}"
    # Ambil hasil pencarian (k=10 agar lebih lengkap)
    results = db.similarity_search(
        query_text,
        k=10,
        filter=filter_params
    )

    # Tambahkan lebih banyak konteks global (SOP/Kebijakan) jika ini adalah request spesifik rental
    if str(rental_id) != "global":
        global_results = db.similarity_search(
            query_text,
            k=5,  # Ditingkatkan dari 2 agar SOP tidak terpotong
            filter={"rental_id": "global"}
        )
        results.extend(global_results)

    context_parts = []
    sources = set() # Untuk melacak dokumen apa saja yang digunakan
    print(f"[RAG_ENGINE] [PRIORITAS 3: MONITORING] Hasil similarity_search mengembalikan {len(results)} dokumen relevan.", flush=True)
    
    if not results:
        print("[RAG_ENGINE] Peringatan: Tidak ada dokumen relevan yang ditemukan di ChromaDB.", flush=True)

    for i, doc in enumerate(results):
        doc_type = doc.metadata.get('doc_type', 'info')
        rental_id_meta = doc.metadata.get('rental_id', 'global')
        source_meta = doc.metadata.get('source', 'unknown')
        sources.add(source_meta)
        print(f"  -> Doc {i+1} [{doc_type.upper()}] [Rental: {rental_id_meta}] [Source: {source_meta}]: {doc.page_content[:100]}...", flush=True)
        
        # Sertakan info Mitra ID agar AI tahu ini kebijakan siapa
        context_parts.append(f"[KATEGORI: {doc_type.upper()}] [MILIK MITRA ID: {rental_id_meta}] [SUMBER: {source_meta}]:\n{doc.page_content}")

    return "\n".join(context_parts), list(sources)

@app.route('/', methods=['GET'])
def root():
    return "RAG Engine Active - Pure Semantic Search & Chat", 200

@app.route('/health', methods=['GET'])
def health():
    return jsonify({"status": "ok", "service": "rag_engine"}), 200

@app.route('/ingest', methods=['POST'])
def ingest_docs():
    try:
        import subprocess
        import sys
        print("\n[RAG_ENGINE] Memulai proses Ingestion Dokumen secara manual via API...", flush=True)
        result = subprocess.run([sys.executable, "ingest_data.py"], cwd=os.path.dirname(__file__), capture_output=True, text=True)
        
        if result.returncode == 0:
            global db
            db = Chroma(persist_directory=DB_DIR, embedding_function=embeddings)
            return jsonify({
                "status": "success",
                "message": "Dokumen berhasil diserap dan Vector Database di-refresh.",
                "output": result.stdout
            }), 200
        else:
            return jsonify({
                "status": "error",
                "message": "Gagal menjalankan ingest_data.py",
                "error_detail": result.stderr
            }), 500
    except Exception as e:
        return jsonify({
            "status": "error",
            "message": f"Server Error saat ingest: {str(e)}"
        }), 500

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
        jumlah_kursi = data.get('jumlah_kursi')
        bahan_bakar = data.get('bahan_bakar')
        tahun = data.get('tahun')
        
        semantic_context = get_relevant_context(query, rental_id, kota=kota_user)
        
        search_prompt = f"""Anda adalah asisten rental mobil profesional.
Gunakan informasi di bawah ini untuk menjawab permintaan user.

DATA STOK REAL-TIME (MySQL):
{stock_context}

PENGETAHUAN PENDUKUNG (RAG):
{semantic_context}

Kriteria Tambahan User:
- Jumlah Kursi minimal: {jumlah_kursi if jumlah_kursi else 'Tidak ada batasan'}
- Bahan Bakar: {bahan_bakar if bahan_bakar else 'Tidak ada batasan'}
- Tahun minimal: {tahun if tahun else 'Tidak ada batasan'}

PERMINTAAN USER: "{query}"

INSTRUKSI JAWABAN:
1. JANGAN memberikan sapaan pembuka (Halo, Selamat Siang, dll).
2. Tampilkan rekomendasi dalam format LIST BERNUMOR (1, 2, 3).
3. Setiap item list harus berisi: "[Nama Mobil] Rp [Harga]/hari (Lokasi: [Kota/Cabang])".
4. Berikan alasan sangat singkat (maks 1 kalimat) kenapa mobil tersebut cocok dan beri tahu lokasi mitra.
5. Jika pengguna mencari berdasarkan nama tempat (seperti Mall, Universitas, Sekolah, dll), gunakan pengetahuan umum Anda untuk mengetahui kota tempat tersebut berada, dan cocokkan dengan kota pada data stok.
6. SANGAT PENTING: Pastikan `id` mobil yang Anda masukkan di JSON benar-benar sesuai dengan nama mobilnya di data stok. Jangan sampai tertukar ID!
7. Jika tidak ada stok yang cocok, balas dengan: {{"results": [], "summary": "Maaf, stok yang Anda cari saat ini sedang kosong."}}

HANYA BALAS DALAM FORMAT JSON BERIKUT:
{{
  "results": [
    {{"id": <id_mobil>, "reason": "<alasan_singkat>"}}
  ],
  "summary": "<kalimat_pengantar_singkat_dan_padat>"
}}"""

        client = get_next_groq_client()
        completion = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=[
                {"role": "system", "content": "Anda adalah asisten pencarian mobil berbasis RAG. Balas HANYA dengan JSON valid."},
                {"role": "user", "content": search_prompt}
            ],
            temperature=0.4,
            max_tokens=2048,
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
    start_time = time.time()
    try:
        data = request.json
        user_input = data.get('question', '')
        laravel_context = data.get('context', '')
        raw_history = data.get('history', [])
        user_name = data.get('user_name', '')
        rental_id = str(data.get('rental_id', 'global'))

        # 1. RETRIEVAL (PURE RAG) - Sesuai instruksi akademik
        kota_user = data.get('kota', None)
        print(f"\n[RAG_ENGINE] Menerima pertanyaan: '{user_input}'")
        
        # RETRIEVE dari ChromaDB secara eksplisit menggunakan global instance
        global db
        if not db:
            print("[RAG_ENGINE] ERROR: ChromaDB belum diinisialisasi.", flush=True)
            return jsonify({"answer": "Maaf, database pengetahuan sedang tidak tersedia. Silakan coba beberapa saat lagi."}), 500

        # Filter untuk mengambil dokumen spesifik rental DAN dokumen global
        filter_expr = None
        if rental_id != 'global':
            filter_expr = {"rental_id": {"$in": [str(rental_id), "global"]}}

        query_text = f"query: {user_input}"
        docs = db.similarity_search(
            query=query_text,
            k=15,
            filter=filter_expr
        )
        
        # Gabungkan context dengan menyertakan metadata rental_id agar LLM tahu ini aturan milik mitra mana
        formatted_docs = []
        for doc in docs:
            r_id = doc.metadata.get("rental_id", "global")
            src = doc.metadata.get("source", "unknown")
            formatted_docs.append(f"--- [DOKUMEN DARI RENTAL ID: {r_id} | SUMBER: {src}] ---\n{doc.page_content}\n")
            
        retrieved_context = "\n".join(formatted_docs)
        
        # Gabungkan dengan data real-time MySQL
        full_context = f"""INFO RETRIEVAL (SOP/HARGA):
{retrieved_context}

DATA STOK REAL-TIME:
{laravel_context}"""

        used_sources = list(set([doc.metadata.get('source', 'unknown') for doc in docs]))
        print(f"[RAG_ENGINE] Hasil pencarian semantik ditemukan ({len(retrieved_context)} karakter) dari sumber: {used_sources}")
            
        current_date = data.get('current_date', '2026-04-29')

        system_prompt = f"""Anda adalah asisten cerdas untuk rental mobil.
Gunakan data KONTEKS untuk menjawab.

{full_context}

ID RENTAL SAAT INI: {rental_id} (Jika 'global', berarti Anda di halaman utama aggregator).

INSTRUKSI:
1. Jika ID RENTAL adalah 'global', Anda adalah asisten pusat. Jawablah secara umum atau rangkum dari berbagai mitra yang ada di KONTEKS. JANGAN hanya menyebut satu mitra kecuali ditanya spesifik.
2. Jika ID RENTAL adalah angka (misal '1'), fokuslah pada kebijakan mitra tersebut.
3. Jadilah asisten (Customer Service) yang ramah dan natural. Jika pengguna memberi sapaan "hai" atau variasinya, balaslah dengan sapaan "hai". Jika pengguna memberi sapaan "halo" atau variasinya, balaslah dengan sapaan "halo". Jika pengguna TIDAK menyapa di pesannya, Anda JANGAN membalas dengan sapaan apapun (jangan menulis kata sapaan di awal kalimat).
4. Jika ada SATU mobil yang fix ingin di-booking user, atur "is_ready": true, isi "car_id" dengan SATU ID angka saja (misal "1"), dan isi "date".
5. KONSULTASI DULU: Jika pengguna mencari mobil secara umum (tanpa spesifikasi tipe/nama mobil), JANGAN langsung memberikan daftar panjang. Bertanyalah terlebih dahulu dengan jelas: "Mobil apa yang ingin Anda booking?" beserta kriteria lain yang mungkin diperlukan.
6. JIKA pengguna SUDAH menyebutkan kriteria spesifik (misal "mobil matic", "SUV", atau menjawab pertanyaan Anda), barulah Anda WAJIB menjabarkan nama-nama mobil yang cocok secara kasual. JANGAN PERNAH membuat tag link booking manual.
7. JIKA USER BERTANYA TENTANG LOKASI, ALAMAT, ATAU CABANG MITRA, gunakan informasi Cabang, Kota, dan Alamat Lengkap dari KONTEKS STOK REAL-TIME untuk menjawabnya secara mendetail.
8. JIKA PENGGUNA MENYEBUTKAN TEMPAT UMUM (seperti nama Mall, Universitas, Sekolah, Bandara, dll), gunakan pengetahuan umum Anda untuk mengidentifikasi kota tempat tersebut berada, lalu rekomendasikan mobil yang lokasinya sesuai dengan kota tersebut pada DATA STOK.
9. JAWABAN SOP & KEBIJAKAN: Jawablah dengan MENDETAIL dan LENGKAP berdasarkan KONTEKS PENGETAHUAN. PERHATIKAN label [DOKUMEN DARI RENTAL ID: X] pada teks konteks! Jangan sampai Anda salah menyebutkan aturan Mitra A sebagai aturan Mitra B. Jika user bertanya spesifik tentang mitra tertentu, pastikan Anda merujuk pada Rental ID milik mitra tersebut.
10. Gunakan bahasa sehari-hari yang sopan namun tetap profesional.

HANYA BALAS JSON:
{{
    "is_ready": true/false,
    "car_id": "ID_MOBIL_JIKA_DIPILIH",
    "date": "TANGGAL_JIKA_DISEPAKATI",
    "recommended_car_ids": [id_angka_1, id_angka_2, dst],
    "response": "Jawaban Anda (Teks biasa, sebutkan nama mobil yang direkomendasikan tanpa membuat link buatan sendiri)"
}}"""

        messages = [{"role": "system", "content": system_prompt}]

        if user_name:
            messages[0]["content"] = f"Nama user: {user_name}\n\n" + messages[0]["content"]

        for h in raw_history[-2:]:
            if h.get('user'): messages.append({"role": "user", "content": h['user']})
            if h.get('bot'):
                bot_content = h['bot'].replace('<br>', '\n')
                messages.append({"role": "assistant", "content": bot_content})

        messages.append({"role": "user", "content": user_input})

        client = get_next_groq_client()
        completion = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=messages,
            temperature=0.3,
            max_tokens=2048,
            response_format={"type": "json_object"}
        )

        res_ai = json.loads(completion.choices[0].message.content)
        final_answer = res_ai.get("response", "").replace('\n', '<br>')

        if res_ai.get("is_ready") and res_ai.get("car_id") and res_ai.get("date"):
            pass # Diurus oleh backend Laravel

        latency_ms = round((time.time() - start_time) * 1000, 2)
        print(f"[RAG_ENGINE] [SUCCESS] Respons LLM selesai dalam {latency_ms} ms", flush=True)

        return jsonify({
            "answer": final_answer,
            "sources": used_sources,
            "recommended_car_ids": res_ai.get("recommended_car_ids", [])
        })

    except Exception as e:
        import traceback
        error_trace = traceback.format_exc()
        try:
            with open("debug.log", "w", encoding="utf-8") as f:
                f.write(error_trace)
        except:
            pass
        print(f"[RAG_ENGINE] Error saat fallback Llama 3: {e}")
        return jsonify({"answer": f"Maaf, ada kendala teknis: {str(e)}"})

if __name__ == "__main__":
    port = int(os.environ.get("PORT", 5000))
    print(f"\n[RAG_ENGINE] Server siap di port {port}")
    if os.environ.get("PORT"):
        print("[RAG_ENGINE] Berjalan dalam mode Cloud/HuggingFace")
    else:
        print("[RAG_ENGINE] Berjalan dalam mode Local")
    
    app.run(host='0.0.0.0', port=port, debug=False)