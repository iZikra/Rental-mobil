import os
import json
from flask import Flask, request, jsonify
from flask_cors import CORS
from groq import Groq
from dotenv import load_dotenv
load_dotenv(override=True)
app = Flask(__name__)
CORS(app)

# --- KONFIGURASI RAG (CLOUD VERSION) ---
# Di server cloud gratis (PythonAnywhere), kita menonaktifkan ChromaDB & PyTorch 
# karena masalah limitasi kuota disk (512MB).
# Sebagai gantinya, Chatbot akan 100% bergantung pada 'context' real-time 
# yang dikirimkan oleh Laravel (Stok Mobil & Harga sudah ada di sana).
db = None
embeddings = None
print("Running in Cloud Lightweight Mode (No PyTorch, No Chroma)")

client = Groq(api_key=os.getenv("GROQ_API_KEY"))

def transform_query(query):
    """
    Simple Query Transformation: Membersihkan query untuk pencarian vektor yang lebih baik.
    """
    try:
        prompt = f"Ubah pertanyaan pengguna berikut menjadi 3-5 kata kunci pencarian yang relevan untuk sistem RAG rental mobil. HANYA balas dengan kata kunci tersebut dipisahkan koma.\n\nPertanyaan: {query}"
        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=[{"role": "user", "content": prompt}],
            temperature=0,
            max_tokens=50
        )
        transformed = completion.choices[0].message.content.strip()
        return transformed if transformed else query
    except:
        return query

def detect_kota(user_input):
    """
    Ekstrak kota dari input. Ini adalah implementasi sederhana berdasarkan daftar kota.
    Bisa dikembangkan lebih lanjut.
    """
    input_lower = user_input.lower()
    # Contoh daftar kota sederhana (idealnya ini dinamis, tapi kita buat statis untuk contoh)
    kota_list = ['pekanbaru', 'jakarta', 'bandung', 'surabaya', 'medan', 'padang', 'bukittinggi', 'dumai']
    for k in kota_list:
        if k in input_lower:
            return k
    return None

def get_relevant_context(user_input, rental_id="global"):
    """
    Retrieve relevant documents from ChromaDB with metadata filtering (Langkah 1 & Langkah 2).
    """
    if not db:
        return ""

    # Langkah 1: Retrieval dari ChromaDB menggunakan similarity_search_by_vector
    query_embedding = embeddings.embed_query(user_input)
    
    # Filter 1: Data spesifik rental
    if str(rental_id) != "global":
        results = db.similarity_search_by_vector(
            embedding=query_embedding, 
            k=5, 
            filter={"rental_id": str(rental_id)}
        )
    else:
        results = db.similarity_search_by_vector(
            embedding=query_embedding,
            k=5
        )
    
    # Filter 2: Data global (jika bukan pencarian global)
    if str(rental_id) != "global":
        global_results = db.similarity_search_by_vector(
            embedding=query_embedding, 
            k=3, 
            filter={"rental_id": "global"}
        )
        results.extend(global_results)

    # Langkah 2: Metadata Filtering sesuai Skripsi (Filter kota)
    kota = detect_kota(user_input)
    if kota:
        # Jika kota terdeteksi, filter hasil. Pastikan yang tidak punya metadata kota (misal: policy) tetap masuk atau difilter?
        # Sesuai snippet pembimbing: results = [r for r in results if kota.lower() in r.metadata.get('kota', '')]
        # Modifikasi sedikit: kita pertahankan dokumen yang tidak punya label kota (seperti dokumen syarat ketentuan)
        filtered_results = []
        for r in results:
            doc_kota = r.metadata.get('kota', '')
            if not doc_kota or kota.lower() in doc_kota:
                filtered_results.append(r)
        results = filtered_results

    context_parts = []
    for doc in results:
        source = doc.metadata.get('source', 'unknown')
        doc_type = doc.metadata.get('doc_type', 'unknown')
        context_parts.append(f"[{doc_type.upper()} dari {source}]:\n{doc.page_content}")

    return "\n\n".join(context_parts)

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
        semantic_context = get_relevant_context(query, rental_id)
        
        search_prompt = f"""Anda adalah asisten pencarian mobil cerdas berbasis RAG (Retrieval-Augmented Generation).

TUGAS: Analisis query pengguna secara mendalam dan REKOMENDASIKAN mobil terbaik dari DATA STOK.

ANTI-HALUSINASI PROMPT:
1. HANYA gunakan data yang ada di 'DATA STOK' atau 'KONTEKS PENGETAHUAN'. JANGAN pernah mengarang fitur, harga, atau ketersediaan mobil yang tidak tercantum.
2. Jika user menanyakan sesuatu yang tidak ada datanya, katakan dengan jujur bahwa informasi tersebut tidak tersedia.

SISTEM SCORING (Wajib digunakan untuk menentukan urutan & alasan):
1. SCORING BBM (Hemat atau Tidak):
   - Skor Tinggi (Irit): Mesin < 1300cc, Tipe 'City Car', 'Compact MPV', atau bahan bakar 'Pertalite'/'Listrik'.
   - Skor Rendah (Boros): Mesin > 2000cc, Tipe 'SUV' besar, atau 'Luxury'.
2. SCORING HARGA (WAJIB):
   - Berikan skor tinggi untuk mobil dengan harga yang kompetitif di kelasnya atau sesuai budget user.
3. SCORING KAPASITAS:
   - Skor didasarkan pada ketepatan jumlah kursi dengan jumlah penumpang yang diminta user.
4. SCORING POPULAR/REKOMENDASI:
   - Prioritaskan mobil tahun terbaru (>= 2022) atau model yang paling sering dicari (Innova, Avanza, Xpander).

ATURAN KETAT (FILTERING):
1. FILTER LOKASI: Perhatikan 'LOKASI FILTER AKTIF (USER)'. Jika bukan 'Seluruh Indonesia', HANYA rekomendasikan mobil di kota tersebut.
2. KRITERIA USER: Jika user menyebutkan kriteria spesifik (seperti 'irit', '7 kursi', 'manual/matic', 'murah'), maka mobil yang Anda pilih WAJIB memenuhi kriteria tersebut.
3. ANTI-RELEVANSI BURUK: JANGAN menampilkan mobil yang bertolak belakang dengan kriteria utama user (Contoh: Jika user mencari mobil 'irit', dilarang keras menampilkan mobil dengan skor BBM 'Boros').

QUERY PENGGUNA: "{query}"

KONTEKS PENGETAHUAN (DARI VECTOR SEARCH):
{semantic_context}

DATA STOK (REAL-TIME DARI DATABASE):
{stock_context}

FORMAT OUTPUT JSON:
{{
    "results": [
        {{
            "id": ID_MOBIL,
            "name": "NAMA MOBIL LENGKAP",
            "scores": {{
                "bbm": "Irit/Cukup/Boros",
                "harga": "Skor 1-10",
                "kapasitas": "Skor 1-10"
            }},
            "reason": "ALASAN PERSONALISASI (Jelaskan mengapa skor bbm, harga, dan kapasitasnya cocok untuk user)"
        }}
    ],
    "summary": "RINGKASAN PINTAR (Jelaskan mengapa mobil-mobil ini direkomendasikan berdasarkan sistem scoring)"
}}

ATURAN:
- Jawab HANYA dengan JSON valid.
- Maksimal 10 hasil.
- Urutkan berdasarkan total skor tertinggi."""

        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
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
    try:
        data = request.json
        user_input = data.get('question', '')
        laravel_context = data.get('context', '')
        raw_history = data.get('history', [])
        user_name = data.get('user_name', '')
        rental_id = str(data.get('rental_id', 'global'))

        # 1. RETRIEVAL (PURE RAG)
        # Ambil konteks tambahan (SOP, Denda, dll) dari ChromaDB
        semantic_context = get_relevant_context(user_input, rental_id)

        system_prompt = f"""Anda adalah asisten rental mobil yang SOPAN, PROFESIONAL, dan MEMBANTU.

KONTEKS PENGETAHUAN (SOP, DENDA, KEBIJAKAN):
{semantic_context}

DATA STOK MOBIL (REAL-TIME):
{laravel_context}

TUGAS ANDA:
- Bantu pengguna menemukan mobil yang cocok
- Jawab pertanyaan tentang mobil, harga, lokasi, ketersediaan
- Dukung proses booking dengan memberikan informasi yang diperlukan
- Selalu bersikap ramah dan helpful

CATATAN TENTANG KONTEKS:
- Anda memiliki akses ke DATA STOK real-time
- Jika user menanyakan mobil tertentu, jelaskan kecocokannya
- Jika user belum menyebutkan preferensi, bantu mereka mengeksplorasi opsi
- Jika user sudah siap booking (sudah pilih mobil, kota, dan tanggal), berikan konfirmasi

FORMAT OUTPUT JSON:
{{
    "is_ready": true/false,
    "car_id": "ISI_ID_MOBIL_JIKA_SUDAH_PILIH",
    "date": "ISI_TANGGAL_JIKA_SUDAH_SEPAKAT",
    "response": "Jawaban natural Anda ke user dalam Bahasa Indonesia"
}}

ATURAN KOMUNIKASI:
1. Anda adalah asisten platform pencarian mobil (Asisten Rental). JANGAN PERNAH mengatasnamakan diri Anda sebagai mitra rental tertentu (jangan bilang "Selamat datang di FZ Rent Car"). Sebutkan nama mitra HANYA sebagai informasi properti mobil.
2. JANGAN membalas dengan sapaan "Kak", "Pak", "Bu" JIKA pengguna tidak menggunakan sapaan tersebut terlebih dahulu. Balas dengan netral jika pengguna hanya mengetik pesan singkat seperti "hai".
3. LOKASI WAJIB: JANGAN PERNAH menawarkan, menyebutkan, atau merekomendasikan stok mobil JIKA pengguna belum secara EKSPLISIT mengetikkan nama kota di dalam chat mereka (misal: "di Pekanbaru"). Jika pengguna hanya bilang "cari mobil irit", Anda WAJIB bertanya "Untuk digunakan di kota mana, Kak?" terlebih dahulu. JANGAN berasumsi lokasi pengguna dari data stok atau daftar cabang!
4. WAJIB SESUAI STOK: JANGAN PERNAH merekomendasikan, menawarkan, atau menyebutkan nama mobil yang TIDAK ADA di dalam "DATA STOK MOBIL (REAL-TIME)" saat ini. Meskipun sebuah mobil dibahas di dalam "KONTEKS PENGETAHUAN", Anda HANYA BOLEH menawarkannya jika mobil tersebut sedang tersedia di DATA STOK MOBIL.

Jangan gunakan rule-based restrictions yang kaku. Cukup jawab secara natural, netral, dan helpful sesuai aturan komunikasi di atas."""

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
            model="llama-3.1-8b-instant",
            messages=messages,
            temperature=0.3,
            max_tokens=1024,
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