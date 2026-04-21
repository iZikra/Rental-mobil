import os
import re
import traceback
import json
import random
from difflib import SequenceMatcher
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

def normalize_text(text: str) -> str:
    t = (text or '').strip().lower()
    t = re.sub(r"[^\w\s]", " ", t, flags=re.UNICODE)
    t = re.sub(r"\s+", " ", t).strip()
    return t

def get_fuzzy_city(text: str, available_cities: list[str], threshold: float = 0.6) -> str | None:
    t = normalize_text(text)
    if not t: return None
    
    # Cek match persis dulu
    for city in available_cities:
        if city.lower() in t:
            return city
            
    # Fuzzy match per kata
    words = t.split()
    best_city = None
    best_score = 0.0
    
    for word in words:
        if len(word) < 3: continue
        for city in available_cities:
            score = SequenceMatcher(None, word, city.lower()).ratio()
            if score > best_score:
                best_score = score
                best_city = city
                
    if best_score >= threshold:
        return best_city
    return None

def get_fuzzy_car(text: str, items: list[dict]) -> dict | None:
    t = normalize_text(text)
    if not t: return None
    
    # 1. Match persis (Innova Reborn -> Toyota Innova Reborn)
    for it in items:
        name = it['name'].lower()
        if t in name or name in t:
            return it
            
    # 2. Match kata kunci unik (Reborn, Xenia, Sigra, dll) 
    skip_words = ["mobil", "sewa", "rental", "mau", "toyota", "daihatsu", "honda", "suzuki", "mitsubishi", "nissan", "wuling", "hyundai"]
    words = [w for w in t.split() if len(w) > 3 and w not in skip_words]
    for word in words:
        for it in items:
            if word in it['name'].lower():
                return it
            
    # 3. Fuzzy match per kata (difflib)
    best_item = None
    best_score = 0.0
    for it in items:
        score = SequenceMatcher(None, t, it['name'].lower()).ratio()
        if score > best_score:
            best_score = score
            best_item = it
                
    if best_score >= 0.5:
        return best_item
    return None

def detect_date(text: str) -> str | None:
    t = normalize_text(text)
    months = ["januari", "februari", "maret", "april", "mei", "juni", "juli", "agustus", "september", "oktober", "november", "desember",
              "jan", "feb", "mar", "apr", "mei", "jun", "jul", "agu", "sep", "okt", "nov", "des"]
    
    # Cek format tanggal (misal: 20 april)
    date_pattern = r'(\d{1,2})\s+(' + '|'.join(months) + r')'
    match = re.search(date_pattern, t)
    if match:
        return f"{match.group(1)} {match.group(2)}"
    
    # Cek angka saja (misal: tanggal 20)
    if any(k in t for k in ["tanggal", "tgl", "tgl ", "tanggal "]):
        match_digit = re.search(r'\d{1,2}', t)
        if match_digit:
            return f"tanggal {match_digit.group(0)}"
            
    return None

def detect_passenger_count(text: str) -> int | None:
    t = normalize_text(text)
    
    # cari angka langsung
    match = re.search(r'\b(\d{1,2})\s*(orang|org|penumpang)?\b', t)
    if match:
        return int(match.group(1))
    
    # mapping kata umum
    if "berdua" in t: return 2
    if "bertiga" in t: return 3
    if "berempat" in t: return 4
    if "berlima" in t: return 5
    
    return None

def parse_stock_items(context: str) -> list[dict]:
    if 'DATA STOK MOBIL SAAT INI' not in context:
        return []
    try:
        # Ambil bagian setelah header stok
        stock_section = context.split('DATA STOK MOBIL SAAT INI', 1)[1]
        # Split per baris dan bersihkan
        lines = [ln.strip() for ln in stock_section.splitlines() if '|' in ln]
        
        items: list[dict] = []
        for ln in lines:
            # Bersihkan prefix seperti '- ID:', '* ID:', dll
            clean_ln = re.sub(r"^[*\-\s]+", "", ln).strip()
            parts = [p.strip() for p in clean_ln.split('|')]
            if not parts: continue
            
            details = {}
            for p in parts:
                if ':' in p:
                    key_val = p.split(':', 1)
                    key = key_val[0].strip().upper()
                    val = key_val[1].strip()
                    
                    # Map keys consistently
                    if key == 'ID': details['id'] = val
                    elif key in ('UNIT', 'NAMA'): details['name'] = val
                    elif key in ('CABANG', 'KOTA'): details['kota'] = val
                    elif key == 'HARGA': details['harga'] = val
                    elif key == 'TRANSMISI': details['transmisi'] = val
                    elif key == 'TIPE': details['tipe'] = val
                    elif key == 'KURSI': details['kursi'] = val
                    elif key == 'BBM': details['bbm'] = val
                    elif key == 'MITRA': details['mitra'] = val
            
            if 'name' in details and 'kota' in details:
                items.append(details)
        return items
    except Exception as e:
        print(f"Error parsing stock: {e}")
        return []

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.json
        user_input = data.get('question', '')
        user_name = data.get('user_name')
        laravel_context = data.get('context', '')
        rental_id = str(data.get('rental_id', '1'))
        raw_history = data.get('history', [])

        user_input_norm = normalize_text(user_input)
        if len(user_input_norm) < 2:
            return jsonify({"answer": "Maaf, bisa diperjelas lagi pertanyaannya?"})

        # 1. PARSE CONTEXT & DATA (BACKEND CONTROL)
        try:
            cities_raw = laravel_context.split('DATA KOTA YANG TERSEDIA DI RENTAL INI:')[1].split('DATA STOK MOBIL SAAT INI')[0].strip()
            available_cities = [c.strip() for c in cities_raw.split(',') if c.strip() and c.strip().lower() not in ('tidak ada cabang', '')]
        except:
            available_cities = []
        
        items = parse_stock_items(laravel_context)

        # 2. BACKEND ENTITY DETECTION (SOURCE OF TRUTH) - BEFORE LLM
        detected_city_current = get_fuzzy_city(user_input_norm, available_cities)
        
        # State Recovery from History (City)
        detected_city = detected_city_current
        reset_keywords = ["tidak jadi", "batal", "cancel", "gak jadi", "ganti kota", "kota lain", "pindah kota", "lalin"]
        
        if any(w in user_input_norm for w in reset_keywords):
            detected_city = None # Paksa reset kota jika user ganti pikiran
        elif not detected_city and raw_history:
            # Gunakan logika mundur untuk mencari kota di history tanpa string gabungan (mencegah first-match error)
            for h in reversed(raw_history[-4:]):
                if isinstance(h, dict):
                    hist_c = get_fuzzy_city(normalize_text(h.get('user', '')), available_cities)
                    if hist_c:
                        detected_city = hist_c
                        break

        passenger_count = detect_passenger_count(user_input_norm)
        global_detected_car_early = get_fuzzy_car(user_input_norm, items)
        intent_keywords = ['cari', 'mobil', 'sewa', 'rental', 'harga', 'daftar', 'list', 'pesan', 'booking', 'tersedia', 'ada', 'tipe', 'rekomendasi']
        is_car_inquiry = any(w in user_input_norm for w in intent_keywords) or passenger_count or global_detected_car_early
        
        # Tambahan: Jika bot baru saja bertanya soal kota
        if not is_car_inquiry and raw_history and isinstance(raw_history[-1], dict):
            last_bot = raw_history[-1].get('bot', '').lower()
            if 'kota mana' in last_bot or 'pilih kota' in last_bot or 'kota yang' in last_bot or 'kota apa' in last_bot:
                is_car_inquiry = True

        # --- TANDAI BAHWA LLM HARUS BERTANYA SOAL KOTA ---
        needs_city_prompt = is_car_inquiry and not detected_city

        # Filter items berdasarkan kota terpilih SAJA
        filtered_items = items
        if detected_city:
            filtered_items = [it for it in items if it.get('kota', '').lower() == detected_city.lower()]

        # Filter Penumpang
        if passenger_count:
            if passenger_count <= 4:
                filtered_items = [it for it in filtered_items if str(it.get('kursi') or 0).isdigit() and int(it.get('kursi') or 0) <= 5]
            else:
                filtered_items = [it for it in filtered_items if str(it.get('kursi') or 0).isdigit() and int(it.get('kursi') or 0) >= 6]
        elif any(w in user_input_norm for w in ["keluarga", "rombongan", "rame", "liburan"]):
            filtered_items = [it for it in filtered_items if str(it.get('kursi') or 0).isdigit() and int(it.get('kursi') or 0) >= 6]



        # Cari mobil khusus di list kota tersebut
        detected_car_item = get_fuzzy_car(user_input_norm, filtered_items)
        if not detected_car_item and raw_history:
            combined_hist = " ".join([normalize_text(h.get('user', '')) for h in raw_history[-4:] if isinstance(h, dict)])
            detected_car_item = get_fuzzy_car(combined_hist, filtered_items)

        detected_date = detect_date(user_input_norm)
        if not detected_date and raw_history:
            combined_hist = " ".join([normalize_text(h.get('user', '')) for h in raw_history[-4:] if isinstance(h, dict)])
            detected_date = detect_date(combined_hist)

        # --- DETEKSI INTENT SETUJU & BLOK LOOP PERTANYAAN ---
        affirmations = ["iya", "ya", "boleh", "oke", "ok", "lanjut", "carikan", "cariin"]
        last_bot_msg = raw_history[-1].get('bot', '').lower() if raw_history and isinstance(raw_history[-1], dict) else ''
        if "tidak tersedia" in last_bot_msg or "alternatif" in last_bot_msg or "pilihan" in last_bot_msg:
            if any(w in user_input_norm for w in affirmations):
                if filtered_items:
                    response_text = f"Di {detected_city}, ini pilihan yang tersedia:<br>"
                    for i, it in enumerate(filtered_items[:10], 1):
                        h_clean = str(it.get('harga', '')).replace('Rp', '').replace('rp', '').replace('/hari', '').strip()
                        response_text += f"{i}. {it['name']} - Rp {h_clean}/hari<br>"
                    return jsonify({"answer": response_text})

        # --- VALIDASI HALUSINASI (CARI MOBIL TAPI BEDA KOTA) ---
        global_detected_car = get_fuzzy_car(user_input_norm, items)
        if not global_detected_car and raw_history:
            combined_hist = " ".join([normalize_text(h.get('user', '')) for h in raw_history[-4:] if isinstance(h, dict)])
            global_detected_car = get_fuzzy_car(combined_hist, items)

        if global_detected_car and not detected_car_item:
            return jsonify({
                "answer": f"Maaf, {global_detected_car['name']} belum tersedia di {detected_city}. Mau saya carikan alternatif mobil lain yang ada di sana?"
            })

        # Mengirim row data (JSON) langsung ke AI agar diformat mandiri tanpa clash
        if needs_city_prompt:
            items_dump = "[]"
        else:
            items_dump = json.dumps([
                {"nama": it.get('name', ''), 
                 "transmisi": it.get('transmisi', ''), 
                 "kursi": it.get('kursi', ''), 
                 "harga": f"Rp {str(it.get('harga', '')).replace('Rp', '').replace('rp', '').replace('/hari', '').strip()}/hari"} 
                for it in filtered_items
            ])

        # 3. CONSTRUCT SYSTEM PROMPT (AI-DRIVEN)
        system_prompt = f"""Kamu adalah asisten platform rental mobil multi-kota yang cerdas, natural, dan membantu.

IDENTITAS & BATASAN:
- Sistem melayani banyak kota (multi-kota).
- HANYA jawab hal yang berkaitan dengan rental mobil.
- JIKA user menyebutkan kota yang TIDAK ADA di daftar KOTA TERSEDIA, BERITAHU dengan sopan dan sebutkan kota yang tersedia.

ALUR:
- Ikuti alur user
- Jika user minta daftar/harga → langsung tampilkan
- Jika user pilih mobil → fokus ke mobil itu
- Jika data kurang (misal kota belum ada) → tanyakan
- Jika user membatalkan (cth: tidak jadi, ganti kota), hargai keputusannya dengan ramah.

GAYA BAHASA:
- Gunakan bahasa santai natural seperti Customer Service WhatsApp.
- Langsung ke intinya.

FORMAT JADWAL:
- Gunakan format list bernomor (1, 2, 3, dst).
- Format list: 1. Nama Mobil – Rp harga/hari

ATURAN KRITIS (WAJIB DIIKUTI):
- {"User SEDANG MENCARI MOBIL tapi KOTA BELUM DIKETAHUI ATAU TIDAK TERSEDIA. TANYAKAN MAU PILIH KOTA MANA! (Pilihan yang ada: " + ", ".join(available_cities) + ") JANGAN tampilkan list mobil!" if needs_city_prompt else "Sebutkan daftar mobil HANYA dari data yang diberikan dan jangan mengarang harga atau mobil."}
- JANGAN tampilkan mobil dari kota lain jika tidak diminta.
- Jika user mengeluh atau merevisi, respon dengan natural.
- HANYA gunakan data dari DATA MOBIL.

DATA MOBIL (HANYA GUNAKAN INI):
{items_dump}

KOTA TERSEDIA:
{available_cities}

KONTEKS:
- Kota: {detected_city or 'Belum ada'}
- Mobil: {detected_car_item['name'] if detected_car_item else 'Belum ada'}
- Tanggal: {detected_date or 'Belum ada'}

FORMAT OUTPUT (WAJIB JSON):
{{
  "intent": "greeting | browsing | search | select | booking | closing",
  "response": "jawaban natural ke user"
}}"""

        messages = [{"role": "system", "content": system_prompt}]
        for h in raw_history[-5:]:
            if isinstance(h, dict):
                if h.get('user'): messages.append({"role": "user", "content": h['user']})
                if h.get('bot'): messages.append({"role": "assistant", "content": h['bot']})
        messages.append({"role": "user", "content": user_input})

        # 4. LLM INTERPRETATION
        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=messages,
            temperature=0.2,
            response_format={"type": "json_object"}
        )

        # 5. FINAL RESPONSE (AI-DRIVEN)
        try:
            res_ai = json.loads(completion.choices[0].message.content)
            final_answer = res_ai.get("response", "Ada yang bisa saya bantu?")
        except:
            final_answer = "Maaf, bisa diulangi lagi?"

        # --- SOFT GUARDRAIL (MEMASTIKAN FORMAT LIST BERHASIL DIBUAT) ---
        if "Rp" in final_answer and "-" in final_answer:
            lines = final_answer.split("\n")
            numbered = []
            i = 1
            has_bullet = False
            for ln in lines:
                if ln.strip().startswith("-"):
                    clean = ln.lstrip("-").strip()
                    numbered.append(f"{i}. {clean}")
                    i += 1
                    has_bullet = True
                else:
                    numbered.append(ln)
            if has_bullet:
                final_answer = "\n".join(numbered)

        # --- CONTEXT AWARENESS PENUH (MENCEGAH LLM LUPA STATE KETIKA MOBIL SUDAH DIPILIH) ---
        if detected_car_item:
            harga_clean = str(detected_car_item.get('harga', '')).replace('Rp', '').replace('rp', '').replace('/hari', '').strip()
            if detected_date:
                return jsonify({
                    "answer": f"{detected_car_item['name']} di {detected_city} tersedia untuk {detected_date}. Silakan lanjut booking ya 👍<br><br>[LINK_BOOKING:{detected_car_item['id']}|{detected_date}]"
                })
            else:
                final_answer = f"{detected_car_item['name']} di {detected_city or 'sini'} harganya Rp {harga_clean}/hari. Mau dipakai tanggal berapa?"
                return jsonify({"answer": final_answer})

        # 6. FAQ / Policy Handling (RAG) - Tetap ada sebagai pelengkap jika AI butuh info spesifik
        faq_keywords = ['syarat', 'denda', 'aturan', 'kebijakan', 'sopir', 'lepas kunci', 'jaminan', 'bayar']
        if any(w in user_input_norm for w in faq_keywords):
            try:
                search_results = vector_store.similarity_search_with_relevance_scores(user_input, k=3, filter={"rental_id": str(rental_id)})
                rag_docs = [doc.page_content for doc, score in search_results if score > 0.4]
                if rag_docs:
                    rag_context = "\n".join(rag_docs)
                    faq_completion = client.chat.completions.create(
                        model="llama-3.1-8b-instant",
                        messages=[{"role": "system", "content": f"Jawab singkat, natural, dan membantu (maks 3 kalimat) berdasarkan info ini:\n{rag_context}"}, {"role": "user", "content": user_input}],
                        temperature=0.1
                    )
                    final_answer = faq_completion.choices[0].message.content
            except: pass

        final_answer = final_answer.replace('\n', '<br>')
        return jsonify({"answer": final_answer})

    except Exception as e:
        print(traceback.format_exc())
        return jsonify({"answer": "Maaf, ada kendala teknis sedikit. Bisa coba ulangi lagi?"}), 200

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=5000, debug=False)
