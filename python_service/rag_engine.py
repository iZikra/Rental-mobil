import os
import json
from flask import Flask, request, jsonify
from flask_cors import CORS
from groq import Groq
from dotenv import load_dotenv

load_dotenv()
app = Flask(__name__)
CORS(app)

client = Groq(api_key=os.getenv("GROQ_API_KEY"))

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

        search_prompt = f"""Anda adalah asisten pencarian mobil yang menggunakan RAG (Retrieval-Augmented Generation).

TUGAS: Analisis query pengguna, pahami secara semantik apa yang mereka butuhkan, dan REKOMENDASIKAN mobil terbaik dari DATA STOK yang tersedia.

CARA KERJA RAG:
1. Pahami INTENT dari query pengguna secara semantik.
2. Retrieve mobil yang relevan dari DATA STOK. 
3. KRITIS: Jika user mencari model spesifik (contoh: "Innova"), Anda HARUS memprioritaskan model tersebut. JANGAN merekomendasikan mobil lain (seperti Xenia/Sigra) kecuali jika model yang dicari BENAR-BENAR tidak ada di DATA STOK.
4. Jika model yang dicari tidak ada, baru berikan alternatif yang paling mendekati secara semantik (misal: Innova tidak ada -> rekomendasikan SUV/MPV besar lainnya).
5. Berikan alasan personalisasi yang menjelaskan mengapa mobil tersebut cocok dengan kebutuhan spesifik user.

QUERY PENGGUNA: "{query}"

DATA STOK (REAL-TIME):
{stock_context}

FORMAT OUTPUT JSON:
{{
    "results": [
        {{
            "id": ID_MOBIL,
            "name": "NAMA MOBIL LENGKAP",
            "reason": "ALASAN PERSONALISASI (Jelaskan kelebihan mobil ini sesuai query user)"
        }}
    ],
    "summary": "RINGKASAN HASIL PENCARIAN"
}}

ATURAN:
- Jawab HANYA dengan JSON valid.
- Maksimal 10 hasil.
- Urutkan dari yang paling relevan.
- Jika tidak ada hasil, results: []."""

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

        system_prompt = f"""Anda adalah asisten rental mobil yang SOPAN, PROFESIONAL, dan MEMBANTU.

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

Jangan gunakan rule-based restrictions. Cukup jawab secara natural dan helpful sesuai konteks percakapan."""

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