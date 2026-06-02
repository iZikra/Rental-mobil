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
import math
import requests
import mysql.connector


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
    print(f"OK: ChromaDB loaded from {DB_DIR}", flush=True)
else:
    db = None
    print(f"Warning: ChromaDB directory not found at {DB_DIR}", flush=True)

client = Groq(api_key=os.getenv("GROQ_API_KEY"))

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
    
    # Ambil hasil pencarian (k=10 agar lebih lengkap)
    results = db.similarity_search(
        user_input,
        k=10,
        filter=filter_params
    )

    # Tambahkan lebih banyak konteks global (SOP/Kebijakan) jika ini adalah request spesifik rental
    if str(rental_id) != "global":
        global_results = db.similarity_search(
            user_input,
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


def haversine(lat1, lon1, lat2, lon2):
    R = 6371.0
    dlat = math.radians(lat2 - lat1)
    dlon = math.radians(lon2 - lon1)
    a = math.sin(dlat / 2)**2 + math.cos(math.radians(lat1)) * math.cos(math.radians(lat2)) * math.sin(dlon / 2)**2
    c = 2 * math.atan2(math.sqrt(a), math.sqrt(1 - a))
    return R * c

def calculate_distances_to_branches(location_name):
    url = f"https://nominatim.openstreetmap.org/search?q={location_name}+Pekanbaru&format=json&limit=1"
    headers = {"User-Agent": "RentalMobilBot/1.0"}
    try:
        r = requests.get(url, headers=headers)
        data = r.json()
        if not data:
            return f"Sistem gagal menemukan koordinat Peta untuk lokasi '{location_name}'."
        user_lat = float(data[0]['lat'])
        user_lon = float(data[0]['lon'])
        
        db = mysql.connector.connect(host="localhost", user="root", password="", database="rental_mobil")
        cursor = db.cursor(dictionary=True)
        cursor.execute("SELECT rental_id, nama_cabang, koordinat_lokasi FROM branches WHERE koordinat_lokasi IS NOT NULL")
        branches = cursor.fetchall()
        db.close()
        
        results = []
        for b in branches:
            coords = b['koordinat_lokasi'].split(',')
            b_lat = float(coords[0].strip())
            b_lon = float(coords[1].strip())
            dist = haversine(user_lat, user_lon, b_lat, b_lon)
            results.append((dist, b['nama_cabang'], b['rental_id']))
            
        results.sort(key=lambda x: x[0])
        info = f"INFORMASI SISTEM: Jarak mitra (Rental ID) dari titik lokasi {data[0]['display_name']} ({user_lat}, {user_lon}):\n"
        for d, name, rid in results:
            info += f"- {name} (ID: {rid}): {d:.1f} km\n"
        return info
    except Exception as e:
        return f"Gagal menghitung jarak: {str(e)}"

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
        
        # RETRIEVE dari ChromaDB secara eksplisit
        db_chat = Chroma(persist_directory=os.path.join(os.path.dirname(__file__), "chroma_db"), embedding_function=embeddings)
        docs = db_chat.similarity_search(
            query=user_input,
            k=5,
            filter={"rental_id": rental_id} if rental_id != 'global' else None
        )
        retrieved_context = "\n".join([doc.page_content for doc in docs])
        
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
3. Jadilah asisten (Customer Service) yang ramah dan natural. Balas sapaan dengan hangat.
4. Jika ada SATU mobil yang fix ingin di-booking user, atur "is_ready": true, isi "car_id" dengan SATU ID angka saja (misal "1"), dan isi "date".
5. JIKA USER MEMINTA DAFTAR/REKOMENDASI (misal "mobil matic", "SUV"), Anda WAJIB menjabarkan SEMUA MOBIL yang cocok secara vertikal (satu mobil satu baris baru).
   WAJIB GUNAKAN FORMAT INI UNTUK SETIAP MOBIL:
   1. [Nama Mobil] Rp [Harga]/hari (Mitra: [Nama Mitra]) [LINK_BOOKING:ID|TANGGAL]
   2. [Nama Mobil] Rp [Harga]/hari (Mitra: [Nama Mitra]) [LINK_BOOKING:ID|TANGGAL]
   (Lanjutkan ke nomor 3, 4, dst. Pastikan setiap mobil memiliki tag LINK_BOOKING masing-masing secara terpisah).
4. JAWABAN SOP & KEBIJAKAN: Jawablah dengan MENDETAIL dan LENGKAP berdasarkan KONTEKS PENGETAHUAN. JANGAN hanya merangkum poin singkat; sertakan poin-poin teknis (seperti denda, syarat cuci, identitas, dll) yang relevan dengan pertanyaan user.
5. Gunakan bahasa sehari-hari yang sopan namun tetap profesional.

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

        # Setup Tools
        tools = [{
            "type": "function",
            "function": {
                "name": "calculate_distances_to_branches",
                "description": "Panggil fungsi ini JIKA user menanyakan mitra terdekat, atau menanyakan jarak mitra ke sebuah lokasi tertentu (misal 'mall ska', 'bandara', 'sudirman', dll).",
                "parameters": {
                    "type": "object",
                    "properties": {
                        "location_name": {"type": "string", "description": "Nama lokasi atau jalan (misal: 'Mall SKA', 'Universitas Riau')"}
                    },
                    "required": ["location_name"]
                }
            }
        }]

        # 1. Panggil LLM dengan Tools (Tanpa JSON constraint)
        completion_tool = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=messages,
            temperature=0.3,
            max_tokens=2048,
            tools=tools,
            tool_choice="auto"
        )
        
        msg_tool = completion_tool.choices[0].message
        
        # Jika LLM memutuskan untuk memanggil fungsi jarak
        if msg_tool.tool_calls:
            for tool_call in msg_tool.tool_calls:
                import json
                args = json.loads(tool_call.function.arguments)
                location_name = args.get("location_name")
                
                print(f"[RAG_ENGINE] [AGENTIC] LLM meminta kalkulasi jarak untuk lokasi: '{location_name}'", flush=True)
                
                # Jalankan fungsi Python
                dist_result = calculate_distances_to_branches(location_name)
                print(f"[RAG_ENGINE] [AGENTIC] Hasil Kalkulasi:\n{dist_result}", flush=True)
                
                # Append hasil kalkulasi ke messages context
                messages.append(msg_tool) # Tambahkan assistant tool call intent
                messages.append({
                    "role": "tool",
                    "tool_call_id": tool_call.id,
                    "name": tool_call.function.name,
                    "content": dist_result
                })
        
        # 2. Panggil LLM untuk final answer (Wajib JSON)
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
            booking_tag = f"[LINK_BOOKING:{res_ai['car_id']}|{res_ai['date']}]"
            if booking_tag not in final_answer:
                final_answer += f"<br><br>{booking_tag}"

        latency_ms = round((time.time() - start_time) * 1000, 2)
        print(f"[RAG_ENGINE] [SUCCESS] Respons LLM selesai dalam {latency_ms} ms", flush=True)

        return jsonify({
            "answer": final_answer,
            "sources": used_sources
        })

    except Exception as e:
        import traceback
        try:
            with open("debug.log", "w", encoding="utf-8") as f:
                f.write(traceback.format_exc())
        except Exception:
            pass
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