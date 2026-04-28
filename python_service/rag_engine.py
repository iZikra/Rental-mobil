import os
import re
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

# 2. CHAT ROUTE
@app.route('/', methods=['GET'])
def root():
    return "OK", 200

@app.route('/health', methods=['GET'])
def health():
    return jsonify({"status": "ok"}), 200

@app.route('/chat', methods=['POST'])
def chat():
    try:
        data = request.json
        user_input = data.get('question', '')
        user_name = data.get('user_name', 'Pelanggan')
        laravel_context = data.get('context', '')
        rental_id = str(data.get('rental_id', '1'))
        raw_history = data.get('history', [])

        def normalize_text(text: str) -> str:
            t = (text or '').strip().lower()
            t = re.sub(r"[^\w\s]", " ", t, flags=re.UNICODE)
            t = re.sub(r"\s+", " ", t).strip()
            return t

        user_input_norm = normalize_text(user_input)

        wants_list_explicitly = any(w in user_input_norm for w in ['sebutkan', 'tampilkan', 'apa saja', 'list', 'daftar', 'semua'])

        # Ambil kota dari antara dua marker yang tepat
        if 'DATA KOTA YANG TERSEDIA DI RENTAL INI:' in laravel_context and 'DATA STOK MOBIL SAAT INI' in laravel_context:
            cities_raw = laravel_context.split('DATA KOTA YANG TERSEDIA DI RENTAL INI:')[1].split('DATA STOK MOBIL SAAT INI')[0].strip()
        else:
            cities_raw = ''
        available_cities = [c.strip() for c in cities_raw.split(',') if c.strip() and c.strip().lower() not in ('tidak ada cabang', '')]
        available_cities_norm = [c.lower() for c in available_cities]

        def contains_any_city(text: str) -> bool:
            t = (text or '').lower()
            return any(city in t for city in available_cities_norm)

        def get_most_recent_city(texts: list[str]) -> str | None:
            for t in texts:
                tl = (t or '').lower()
                for city in available_cities:
                    if city.lower() in tl:
                        return city
            return None

        def parse_stock_items(context: str) -> list[dict]:
            if 'DATA STOK MOBIL SAAT INI' not in context:
                return []
            stock_text = context.split('DATA STOK MOBIL SAAT INI', 1)[1]
            # Ambil semua baris yang dimulai dengan '- ' atau '- UNIT:'
            lines = [ln.strip() for ln in stock_text.splitlines() if ln.strip().startswith('-')]
            items: list[dict] = []
            for ln in lines:
                # Bersihkan prefix '- UNIT:' atau '- '
                raw = re.sub(r"^- (UNIT: )?", "", ln).strip()
                parts = [p.strip() for p in raw.split('|')]
                if not parts: continue
                
                name = parts[0]
                kota = ''
                harga = ''
                transmisi = ''
                tipe = ''
                kursi = None
                bbm = ''
                
                for p in parts[1:]:
                    pl = p.lower()
                    if 'cabang:' in pl:
                        kota = p.split(':', 1)[1].strip()
                    elif 'harga:' in pl:
                        # Ambil angka saja untuk harga
                        harga_raw = p.split(':', 1)[1].strip()
                        harga = harga_raw.replace('Rp', '').replace('/hari', '').replace('.', '').replace(',', '').strip()
                        # Format ulang agar cantik (titik ribuan)
                        try:
                            harga_int = int(harga)
                            harga = f"{harga_int:,}".replace(',', '.')
                        except:
                            harga = harga_raw
                    elif 'transmisi:' in pl:
                        transmisi = p.split(':', 1)[1].strip().lower()
                    elif 'tipe:' in pl:
                        tipe = p.split(':', 1)[1].strip().lower()
                    elif 'kursi:' in pl:
                        val = p.split(':', 1)[1].strip()
                        try:
                            kursi = int(''.join([ch for ch in val if ch.isdigit()]) or '0') or None
                        except:
                            kursi = None
                    elif 'bbm:' in pl:
                        bbm = p.split(':', 1)[1].strip().lower()
                
                items.append({
                    'name': name,
                    'kota': kota,
                    'harga': harga,
                    'transmisi': transmisi,
                    'tipe': tipe,
                    'kursi': kursi,
                    'bbm': bbm,
                })
            return items

        def split_greeting(text_norm: str) -> tuple[str | None, str]:
            t = (text_norm or '').strip()
            if not t:
                return None, ''

            for phrase in [
                'assalamualaikum',
                'assalamu alaikum',
                'assalamu’alaikum',
                'selamat pagi',
                'selamat siang',
                'selamat sore',
                'selamat malam',
            ]:
                if t.startswith(phrase):
                    rest = t[len(phrase):].strip()
                    rest = re.sub(r"^(kak|kakak|min|admin)\b", "", rest).strip()
                    return phrase, rest

            first = t.split(' ', 1)[0]
            if first in {'halo', 'hai', 'hi', 'hello', 'p'}:
                rest = t[len(first):].strip()
                rest = re.sub(r"^(kak|kakak|min|admin)\b", "", rest).strip()
                return first, rest

            return None, t

        def render_greeting(g: str | None) -> str:
            if not g:
                return "Halo!"
            if g.startswith('assalamu') or g.startswith('assalam'):
                return "Waalaikumsalam!"
            if g.startswith('selamat'):
                return f"{g.capitalize()}!"
            return "Halo!"

        yes_words = {'iya', 'iyaa', 'ya', 'y', 'ok', 'oke', 'okee', 'okey', 'baik', 'boleh', 'siap', 'setuju', 'gas', 'yoi', 'mantap'}
        car_intent_keywords = ['cari mobil', 'mencari mobil', 'butuh mobil', 'sewa mobil', 'rental mobil', 'butuh kendaraan']

        greet_token, rest_norm = split_greeting(user_input_norm)
        greeting_text = render_greeting(greet_token) if greet_token else None

        def with_greeting(answer: str) -> str:
            if greeting_text:
                return f"{greeting_text} {answer}".strip()
            return answer

        # --- DETERMINISTIC ANSWERS (FAQS) ---
        if any(w in user_input_norm for w in ['cara booking', 'bagaimana booking', 'langkah booking', 'proses booking', 'cara sewa', 'gimana booking', 'cara bayar', 'pembayaran', 'bayarnya gimana']):
            answer = "\n".join([
                "Cara booking dan pembayaran di platform kami sangat mudah. Ini langkahnya:",
                "1) Buka menu Booking di website",
                "2) Pilih unit mobil yang diinginkan",
                "3) Isi tanggal & jam sewa (ambil/kembali)",
                "4) Upload KTP & SIM untuk verifikasi",
                "5) Klik Konfirmasi Booking, lalu lanjutkan pembayaran via Midtrans (bisa pakai QRIS, Transfer Bank, atau Kartu)",
                "",
                "Gampang banget kan? Mau dibantu cari mobil di kota mana dulu nih?"
            ])
            return jsonify({"answer": with_greeting(answer)})

        about_me_norm = rest_norm or user_input_norm
        if any(
            p in about_me_norm
            for p in [
                'kamu ini apa',
                'kamu itu apa',
                'kamu siapa',
                'siapa kamu',
                'siapa anda',
                'anda siapa',
                'ini bot apa',
                'bot apa ini',
                'asisten apa',
            ]
        ):
            answer = "\n".join([
                "Saya asisten Rental Mobil yang siap bantu cari mobil yang pas.",
                "Saya bisa bantu cek harga, ketersediaan, spesifikasi, dan aturan rental.",
                "Ada yang bisa saya bantu hari ini?",
            ])
            return jsonify({"answer": with_greeting(answer)})

        if greet_token and not rest_norm:
            return jsonify({"answer": f"{greeting_text} Ada yang bisa saya bantu?"})

        def is_yes_only(text_norm: str) -> bool:
            toks = (text_norm or '').split()
            if not toks:
                return False
            for t in toks:
                if t in yes_words:
                    continue
                if re.fullmatch(r"ok(e)+y*", t):
                    continue
                if re.fullmatch(r"iy+a+", t):
                    continue
                if re.fullmatch(r"ya+a+", t):
                    continue
                return False
            return True

        if is_yes_only(rest_norm or user_input_norm) and raw_history:
            last_bot = ''
            for h in reversed(raw_history):
                if isinstance(h, dict) and h.get('bot'):
                    last_bot = str(h.get('bot'))
                    break
            last_bot_norm = last_bot.lower()
            if 'pekanbaru' in last_bot_norm and 'jakarta' in last_bot_norm:
                return jsonify({"answer": with_greeting("Oke. Mau yang Pekanbaru atau Jakarta nih?")})
            if 'bisa saya bantu' in last_bot_norm:
                return jsonify({"answer": with_greeting("Siap. Mau dibantu cari mobil atau ada pertanyaan lain?")})
            if ('konfirmasi booking' in last_bot_norm) or ('menu booking' in last_bot_norm) or ('langkahnya' in last_bot_norm):
                return jsonify({"answer": with_greeting("Sip. Tinggal ikuti langkah tadi ya. Mau mulai sewanya tanggal berapa dan kira-kira untuk berapa hari? Nanti tinggal isi itu di form Booking.")})
            m_city = re.search(r"maksudnya\s+([a-z\s]+?)\s+ya", last_bot_norm)
            if m_city:
                guessed_city = m_city.group(1).strip()
                if guessed_city:
                    return jsonify({"answer": with_greeting(f"Siap. Di {guessed_city.title()} bisa. Mau yang matic atau manual?")})

        # --- DATA PREPARATION ---
        available_cities_raw = laravel_context.split('DATA KOTA YANG TERSEDIA DI RENTAL INI:')[1].split('DATA STOK MOBIL SAAT INI')[0].strip()
        available_cities = [c.strip().lower() for c in available_cities_raw.split(',') if c.strip()]
        
        def contains_any_city(text):
            return any(city in text.lower() for city in available_cities)
        
        def get_matched_city(text):
            for city in available_cities:
                if city in text.lower():
                    return city
            return None

        def get_fuzzy_city_with_score(text: str) -> tuple[str | None, float]:
            t = (text or '').strip().lower()
            if not t:
                return None, 0.0
            best_city = None
            best_score = 0.0
            for city in available_cities:
                score = SequenceMatcher(None, t, city).ratio()
                if score > best_score:
                    best_score = score
                    best_city = city
            return best_city, best_score

        # --- INTENT DETECTION ---
        user_input_norm = normalize_text(user_input)
        
        # --- DETERMINISTIC ANSWERS (FAQS) ---
        if any(w in user_input_norm for w in ['cara booking', 'bagaimana booking', 'langkah booking', 'proses booking', 'cara sewa', 'gimana booking', 'cara bayar', 'pembayaran', 'bayarnya gimana']):
            answer = "\n".join([
                "Cara booking dan pembayaran di platform kami sangat mudah. Ini langkahnya:",
                "1) Buka menu Booking di website",
                "2) Pilih unit mobil yang diinginkan",
                "3) Isi tanggal & jam sewa (ambil/kembali)",
                "4) Upload KTP & SIM untuk verifikasi",
                "5) Klik Konfirmasi Booking, lalu lanjutkan pembayaran via Midtrans (bisa pakai QRIS, Transfer Bank, atau Kartu)",
                "",
                "Gampang banget kan? Mau dibantu cari mobil di kota mana dulu nih?"
            ])
            return jsonify({"answer": with_greeting(answer)})

        # Deteksi filter (Matic, Manual, MPV, dll)
        is_matic = any(w in user_input_norm for w in ['matic', 'matik', 'automatic', 'otomatis', 'auto'])
        is_manual = 'manual' in user_input_norm
        
        # Deteksi tipe mobil
        car_types = ['mpv', 'suv', 'sedan', 'city car', 'minibus', 'bus', 'pick up', 'truk']
        type_need = next((t for t in car_types if t in user_input_norm), '')
        
        # Deteksi kapasitas (kursi)
        seats_match = re.search(r"(\d+)\s*(orang|kursi|seat|pax)", user_input_norm)
        seats_need = int(seats_match.group(1)) if seats_match else 0
        seats_exact = 'pas' in user_input_norm or 'tepat' in user_input_norm
        
        # Deteksi BBM
        is_bensin = 'bensin' in user_input_norm
        is_diesel = 'diesel' in user_input_norm or 'solar' in user_input_norm

        wants_list_explicitly = any(w in user_input_norm for w in ['sebutkan', 'tampilkan', 'apa saja', 'list', 'daftar', 'semua'])
        
        # Perbaikan intent mobil: jangan sampai kendaraan lain kena
        other_vehicles = ['motor', 'helikopter', 'pesawat', 'kapal', 'sepeda', 'ojek']
        is_other_vehicle = any(v in user_input_norm for v in other_vehicles)
        
        car_intent_keywords = ['mobil', 'sewa', 'rental', 'pinjam', 'booking', 'cari']
        
        intent_text = rest_norm or user_input_norm
        has_car_intent = (any(k in intent_text for k in car_intent_keywords) or ('mobil' in intent_text and ('cari' in intent_text or 'sewa' in intent_text or 'rental' in intent_text))) and not is_other_vehicle

        # --- DETEKSI PERTANYAAN (KATA TANYA) ---
        # Kata tanya umum untuk mendeteksi pertanyaan platform/RAG
        question_words = ['berapa', 'siapa', 'mengapa', 'apa', 'bagaimana', 'apakah', 'gimana', 'kapan', 'sejak', 'adakah', 'ada', 'punya', 'dimana', 'mana']
        is_question = any(f" {w} " in f" {user_input_norm} " for w in question_words) or user_input_norm.startswith(tuple(question_words)) or user_input_norm.endswith('?')

        # --- RAG RETRIEVAL (DOKUMEN LOKAL) ---
        rag_context = ""
        # Kata tanya harga selalu wajib trigger RAG
        price_keywords = ['harga', 'berapa', 'biaya', 'tarif', 'denda', 'syarat', 'ketentuan', 'persyaratan']
        is_price_question = any(w in user_input_norm for w in price_keywords)
        
        if is_question or is_price_question or (not has_car_intent and len(user_input_norm) > 5):
            try:
                # Ambil dokumen relevan — k=6 untuk lebih banyak kandidat
                search_results = vector_store.similarity_search_with_relevance_scores(
                    user_input, 
                    k=6, 
                    filter={"rental_id": str(rental_id)}
                )
                if search_results:
                    # Threshold 0.25 — cukup rendah agar dokumen harga bisa masuk
                    rag_docs = [doc.page_content for doc, score in search_results if score > 0.25]
                    if rag_docs:
                        rag_context = "\n\nDOKUMEN RELEVAN DARI DATABASE (RAG):\n" + "\n---\n".join(rag_docs) + "\n"
            except Exception as e:
                print(f"Error RAG: {e}")
                # Fallback ke similarity_search biasa jika provider tidak support score
                try:
                    search_results = vector_store.similarity_search(user_input, k=5, filter={"rental_id": str(rental_id)})
                    rag_docs = [doc.page_content for doc in search_results]
                    rag_context = "\n\nDOKUMEN RELEVAN DARI DATABASE (RAG):\n" + "\n---\n".join(rag_docs) + "\n"
                except: pass

        # --- CITY SELECTION ---
        # Prioritaskan kota yang disebutkan di input TERBARU
        current_input_city = get_matched_city(user_input_norm)
        if not current_input_city:
            token = user_input_norm.split()[-1] if user_input_norm.split() else ''
            cand_city, cand_score = get_fuzzy_city_with_score(token)
            if cand_city and cand_score >= 0.86:
                current_input_city = cand_city
            elif cand_city and cand_score >= 0.75:
                last_bot_norm = ''
                if raw_history:
                    for h in reversed(raw_history):
                        if isinstance(h, dict) and h.get('bot'):
                            last_bot_norm = str(h.get('bot')).lower()
                            break
                if any(k in last_bot_norm for k in ['kota mana', 'mau yang kota mana', 'pilih salah satu', 'yang kota mana']):
                    return jsonify({"answer": with_greeting(f"Maksudnya {cand_city.title()} ya? Kalau iya, balas: iya.")})
        
        # Jika user menyebut kota yang TIDAK ada di daftar cabang
        maybe_city_match = re.search(r"\b(kota|di)\s+([a-z]{3,20})\b", user_input_norm)
        maybe_city = (maybe_city_match.group(2).strip() if maybe_city_match else '')
        if maybe_city:
            maybe_city = re.sub(r"\b(ya|iya|nih|dong|deh|aja|saja|kak|kakak|buat|untuk|ada|gak|nggak)\b", "", maybe_city).strip()
        # Jangan anggap nama rental / nama mobil sebagai nama kota
        rental_name_words = set()
        if 'DATA RENTAL YANG TERDAFTAR:' in laravel_context:
            rental_block = laravel_context.split('DATA RENTAL YANG TERDAFTAR:')[1].split('DATA KOTA')[0]
            for word in rental_block.lower().split():
                if len(word) >= 3:
                    rental_name_words.add(word)
        if maybe_city in rental_name_words:
            maybe_city = ''
        if maybe_city in {'sini', 'situ', 'rumah', 'kantor', 'mana', 'atas', 'bawah', 'tempat', 'daerah'}:
            maybe_city = ''

        wants_any_filter = is_matic or is_manual or bool(type_need) or bool(seats_need) or is_bensin or is_diesel
        has_car_intent_or_filter = (has_car_intent or wants_any_filter) and not is_other_vehicle

        # --- INTENT PRIORITY: PLATFORM QUESTIONS FIRST ---
        platform_keywords = ['partner', 'rental apa saja', 'daftar rental', 'siapa saja rental', 'perusahaan', 'rental yang terdaftar', 'rental yang sudah terdaftar', 'nama rental', 'apa saja rentalnya']
        is_asking_platform = any(w in user_input_norm for w in platform_keywords)
        
        # --- HELPERS ---
        def extract_seats(text_norm: str) -> tuple[int | None, bool]:
            m = re.search(r"\b(\d{1,2})\s*(orang|org|kursi|seater)\b", text_norm or '')
            if not m:
                return None, False
            seats = int(m.group(1))
            exact = 'pas' in (text_norm or '')
            return seats, exact

        def extract_type(text_norm: str, known_types: list[str]) -> str | None:
            t = (text_norm or '')
            # Cek tipe yang lebih spesifik dulu (seperti 'mini mpv')
            for tp in sorted(known_types, key=len, reverse=True):
                if tp and tp != '-' and tp in t:
                    return tp
            return None

        # --- PENGENALAN PEMILIHAN MOBIL DARI DAFTAR (CRITICAL FIX) ---
        # Jika user menyebut nama mobil yang PERSIS cocok dengan daftar di chat sebelumnya,
        # ini adalah PEMILIHAN, BUKAN pencarian baru.
        items = parse_stock_items(laravel_context)

        # Bangun dictionary nama mobil lengkap untuk recognition cepat
        known_cars_map: dict[str, dict] = {}
        if items:
            for it in items:
                car_name = it.get('name') or ''
                if car_name and len(car_name) >= 4:
                    # Simpan normalized + original
                    norm_name = normalize_text(car_name)
                    known_cars_map[norm_name] = it
                    # Simpan juga per kata kunci (tokenisasi)
                    for token in norm_name.split():
                        if len(token) >= 4:
                            known_cars_map[token] = it

        # Bangun dictionary nama MITRA/RENTAL untuk recognition
        known_rentals_map: dict[str, str] = {}
        if 'DATA RENTAL YANG TERDAFTAR:' in laravel_context:
            rental_block = laravel_context.split('DATA RENTAL YANG TERDAFTAR:')[1].split('DATA KOTA')[0]
            # Ambil nama rental per baris (format: "- NAMA RENTAL")
            for line in rental_block.splitlines():
                line = line.strip()
                if line.startswith('-'):
                    raw = line.lstrip('-').strip()
                    if raw and len(raw) >= 3:
                        norm_rental = normalize_text(raw)
                        known_rentals_map[norm_rental] = raw
                        # Simpan juga per token
                        for tok in norm_rental.split():
                            if len(tok) >= 3:
                                known_rentals_map[tok] = raw

        input_norm = normalize_text(user_input)

        # Cek apakah input user menyebut nama MITRA spesifik
        selected_rental_name = None
        for norm_rental, original_rental in known_rentals_map.items():
            if norm_rental in input_norm or input_norm in norm_rental:
                selected_rental_name = original_rental
                break

        # Cek apakah input user menyebut nama mobil spesifik
        selected_vehicle = None
        for car_key, car_data in known_cars_map.items():
            if car_key in input_norm:
                selected_vehicle = car_data
                break

        # Jika user menyebut nama MITRA/RENTAL spesifik
        if selected_rental_name and not is_question and not is_asking_platform:
            # Cek apakah juga menyebut mobil
            if selected_vehicle:
                # User menyebut rental + mobil
                chosen_name = selected_vehicle.get('name') or ''
                chosen_city = selected_vehicle.get('kota') or ''
                return jsonify({"answer": with_greeting(f"{chosen_name} di {chosen_city} tersedia. Mau tanya harga, tanya syarat, atau langsung booking?")})
            else:
                # User hanya menyebut nama rental - Gunakan SUARA PLATFORM (bukan suara rental)
                # Karena platform ini adalah DriveNow, bukan milik satu rental tertentu
                return jsonify({"answer": with_greeting(f"Soal {selected_rental_name}, saya bantu ya. Mau tanya apa? Misalnya: mobil apa saja yang tersedia di sana, harga sewanya, atau syarat rentasnya?")})

        # Jika user menyebut nama mobil spesifik (bukan mau cari, tapi milih dari daftar)
        if selected_vehicle and not is_question:
            # Ini adalah PEMILIHAN MOBIL, langsung tangkap mobilnya dan tanya kriteria lanjutan
            chosen_name = selected_vehicle.get('name') or ''
            chosen_city = selected_vehicle.get('kota') or selected_city or ''

            # Cari tahu filter yang sudah ada dari konteks
            if trans_pref and not (is_matic or is_manual):
                # Sudah ada preferensi transmisi, langsung ke下一步
                pass
            elif seats_need:
                # Sudah ada preferensi kursi
                pass
            elif not wants_any_filter:
                # Belum ada filter, tanya transmisi
                return jsonify({"answer": with_greeting(f"{chosen_name} (cabang {chosen_city}). Untuk berapa orang dan mau matic atau manual?")})

            # Jika sudah punya cukup konteks, langsung berikan panduan booking
            return jsonify({"answer": with_greeting(f"Oke, dicatat: {chosen_name} (cabang {chosen_city}). Mau booking untuk kapan dan berapa hari? Bisa langsung isi di menu Booking ya.")})

        # --- CONTEXT-AWARE FILTER EXTRACTION ---
        history_texts: list[str] = [user_input_norm]
        for h in reversed(raw_history):
            if isinstance(h, dict) and h.get('user'):
                history_texts.append(normalize_text(str(h.get('user'))))
        
        selected_city = current_input_city or get_most_recent_city(history_texts)
        
        # Jika user tanya "kota lain", "selain itu", dll, jangan paksa pakai city dari history
        is_asking_other_cities = any(w in user_input_norm for w in ['kota lain', 'selain itu', 'cabang lain', 'dimana lagi', 'di kota lain'])
        if is_asking_other_cities and not current_input_city:
            selected_city = None

        # 2. Transmisi (Cek history)
        trans_pref = None
        for t in history_texts:
            has_manual = 'manual' in t
            has_matic_hist = any(w in t for w in ['matic', 'matik', 'automatic', 'otomatis', 'auto'])
            if has_manual and has_matic_hist:
                trans_pref = 'both'
                break
            if has_manual:
                trans_pref = 'manual'
                break
            if has_matic_hist:
                trans_pref = 'matic'
                break
        trans_ambiguous = trans_pref == 'both'
        is_matic = trans_pref == 'matic'
        is_manual = trans_pref == 'manual'
        
        # 3. Kursi (Cek history)
        if not seats_need:
            for t in history_texts:
                s, e = extract_seats(t)
                if s:
                    seats_need = s
                    seats_exact = e
                    break

        # 4. BBM (Bensin/Diesel)
        if not (is_bensin or is_diesel):
            fuel_pref = None
            for t in history_texts:
                has_bensin_hist = 'bensin' in t
                has_diesel_hist = 'diesel' in t
                if has_bensin_hist and has_diesel_hist:
                    fuel_pref = 'both'
                    break
                if has_bensin_hist:
                    fuel_pref = 'bensin'
                    break
                if has_diesel_hist:
                    fuel_pref = 'diesel'
                    break
            is_bensin = fuel_pref == 'bensin'
            is_diesel = fuel_pref == 'diesel'

        # 5. Tipe (Cek history)
        items = parse_stock_items(laravel_context)
        known_types = sorted({it.get('tipe') for it in items if it.get('tipe') and it.get('tipe') != '-'})
        if not type_need:
            for t in history_texts:
                tp = extract_type(t, known_types)
                if tp:
                    type_need = tp
                    break

        wants_any_filter = is_matic or is_manual or bool(type_need) or bool(seats_need) or is_bensin or is_diesel
        has_car_intent_or_filter = (has_car_intent or wants_any_filter) and not is_other_vehicle

        # --- INTENT PRIORITY: PLATFORM QUESTIONS FIRST ---
        platform_keywords = ['partner', 'rental apa saja', 'daftar rental', 'siapa saja rental', 'perusahaan', 'rental yang terdaftar', 'rental yang sudah terdaftar', 'nama rental', 'apa saja rentalnya', 'rental yang ada', 'rental mana saja']
        is_asking_platform = any(w in user_input_norm for w in platform_keywords)
        
        # --- VALIDASI KOTA SEBELUM LISTING ---
        if not is_asking_platform and not is_other_vehicle:
            # Jika user menyebut kota yang TIDAK ada di daftar cabang
            # PENGECUALIAN: pertanyaan harga/syarat/denda tidak perlu kota
            if maybe_city and (has_car_intent_or_filter or wants_list_explicitly) and not selected_city and not is_price_question:
                if available_cities:
                    return jsonify({"answer": with_greeting(f"Maaf, di {maybe_city} belum ada cabang. Yang tersedia: {', '.join(available_cities).title()}. Mau yang kota mana?")})
                return jsonify({"answer": with_greeting("Maaf, saat ini belum ada cabang yang tersedia.")})

            if is_asking_other_cities:
                if available_cities:
                    return jsonify({"answer": with_greeting(f"Ada. Cabang yang tersedia: {', '.join(available_cities).title()}. Mau yang kota mana?")})
                return jsonify({"answer": with_greeting("Maaf, saat ini belum ada cabang yang tersedia.")})

            # Jangan tanya kota jika pertanyaan adalah tentang harga/syarat/denda
            if has_car_intent and not selected_city and not is_question and not is_price_question:
                return jsonify({"answer": with_greeting("Siap. Mau cari mobil di kota mana?")})
            
            if (wants_any_filter or wants_list_explicitly) and not selected_city and not is_question and not is_price_question:
                return jsonify({"answer": with_greeting("Sip. Cari mobilnya di kota mana nih?")})

            if selected_city and trans_ambiguous:
                return jsonify({"answer": with_greeting(f"Siap. Di {selected_city.title()} mau yang matic atau manual?")})

            if selected_city and not wants_any_filter and (has_car_intent_or_filter or contains_any_city(user_input_norm) or wants_list_explicitly) and not is_question:
                if any(w in user_input_norm for w in ['sebutkan', 'tampilkan', 'apa saja', 'list', 'daftar', 'semua']):
                    # Langsung ke proses listing
                    pass
                elif seats_need and not (is_matic or is_manual or type_need or is_bensin or is_diesel):
                    return jsonify({"answer": with_greeting(f"Siap. Di {selected_city.title()} untuk {('pas ' if seats_exact else 'minimal ')}{seats_need} orang, mau yang matic atau manual?")})
                else:
                    return jsonify({"answer": with_greeting(f"Siap, di {selected_city.title()} bisa. Mau yang matic atau manual?")})

        # --- INTELLIGENT CONTEXT INJECTION ---
        city_info_context = ""
        if current_input_city:
            city_info_context = f"\nINFO: Kota {current_input_city} TERSEDIA di cabang kita.\n"
        elif has_car_intent and not selected_city:
            city_info_context = "\nINFO: User INGIN MENCARI MOBIL tapi BELUM menyebutkan kota. Anda WAJIB langsung bertanya kota.\n"

        # --- LOGIKA SMART FILTERING ---
        matched = []
        if selected_city and (wants_any_filter or wants_list_explicitly):
            def trans_ok(it: dict) -> bool:
                tr = (it.get('transmisi') or '').lower()
                if is_matic: return 'matic' in tr or 'auto' in tr or 'otomatis' in tr
                if is_manual: return 'manual' in tr
                return True

            def type_ok(it: dict) -> bool:
                if not type_need: return True
                return type_need in ((it.get('tipe') or '').lower())

            def seats_ok(it: dict) -> bool:
                if not seats_need: return True
                k = it.get('kursi')
                if not isinstance(k, int): return False
                return (k == seats_need) if seats_exact else (k >= seats_need)

            def bbm_ok(it: dict) -> bool:
                bb = (it.get('bbm') or '').lower()
                if is_bensin: return 'bensin' in bb
                if is_diesel: return 'diesel' in bb
                return True

            matched = [
                it for it in items
                if it.get('kota', '').lower() == selected_city.lower()
                and trans_ok(it)
                and type_ok(it)
                and seats_ok(it)
                and bbm_ok(it)
            ]

        def get_last_bot_text(history: list) -> str:
            for h in reversed(history or []):
                if isinstance(h, dict) and h.get('bot'):
                    return str(h.get('bot'))
            return ''

        def extract_choice_index(text_norm: str) -> int | None:
            t = (text_norm or '')
            m = re.search(r"\b(?:no|nomor|nomer|pilihan)\s*(\d{1,2})\b", t)
            if m:
                return int(m.group(1))
            m = re.search(r"\b(\d{1,2})\b", t)
            if m:
                return int(m.group(1))
            words = {
                'pertama': 1,
                'satu': 1,
                'kedua': 2,
                'dua': 2,
                'ketiga': 3,
                'tiga': 3,
                'keempat': 4,
                'empat': 4,
                'kelima': 5,
                'lima': 5,
            }
            for w, n in words.items():
                if re.search(rf"\b{re.escape(w)}\b", t):
                    return n
            return None

        def try_match_vehicle_name(text_norm: str, candidates: list[dict]) -> dict | None:
            t = normalize_text(text_norm)
            if not t:
                return None
            stop = {
                'deh', 'dong', 'nih', 'ya', 'iya', 'yaa', 'yang', 'mau', 'pilih', 'ambil',
                'aja', 'saja', 'kak', 'kakak', 'itu', 'ini', 'yangini', 'yangitu',
            }
            input_tokens = [tok for tok in t.split() if len(tok) >= 4 and tok not in stop]
            if not input_tokens:
                return None

            best: dict | None = None
            best_score = 0.0

            for it in candidates:
                name = normalize_text(str(it.get('name') or ''))
                if not name:
                    continue
                name_tokens = [tok for tok in name.split() if len(tok) >= 4]
                if not name_tokens:
                    continue

                score = 0.0
                for a in input_tokens:
                    for b in name_tokens:
                        if a == b:
                            score = max(score, 1.0)
                            continue
                        if a in b or b in a:
                            score = max(score, 0.92)
                            continue
                        r = SequenceMatcher(a=a, b=b).ratio()
                        score = max(score, r)
                if score > best_score:
                    best_score = score
                    best = it

            if best and best_score >= 0.84:
                return best
            return None

        def build_booking_guide(chosen_name: str, city: str) -> str:
            lines = [
                f"Oke, dicatat: {chosen_name} (cabang {city}).",
                "",
                "Saya belum bisa booking langsung dari chat, tapi caranya begini:",
                "1) Buka menu Booking",
                f"2) Pilih mobil {chosen_name}",
                "3) Isi tanggal & jam sewa (ambil/kembali)",
                "4) Upload KTP & SIM",
                "5) Klik Konfirmasi Booking, lalu lanjutkan pembayaran yang muncul",
            ]
            return "\n".join(lines)

        # Metadata tambahan untuk membantu filter deterministic
        matched_context = ""
        if matched:
            matched_list = "\n".join([f"- {m['name']} | Harga: {m['harga']}/hari" for m in matched])
            matched_context = f"\n\nWAJIB TAMPILKAN DAFTAR MOBIL INI (KOTA: {selected_city}):\n{matched_list}\n"
            if wants_list_explicitly:
                matched_context += "INFO: User minta list secara eksplisit. Tampilkan daftar di atas SEKARANG tanpa bertanya kriteria lain dulu.\n"
        elif selected_city and wants_any_filter:
            matched_context = f"\n\nINFO: Tidak ada mobil yang sesuai kriteria {selected_city}. Beritahu user dengan sopan.\n"

        if selected_city and (wants_any_filter or wants_list_explicitly):
            if not matched:
                return jsonify({"answer": with_greeting(f"Yang sesuai di {selected_city} sedang kosong. Mau ganti kriteria atau ganti kota?")})

            if len(matched) == 1:
                only = matched[0]
                only_name = only.get('name') or 'mobil ini'
                only_price = only.get('harga') or ''
                price_part = f" (Rp {only_price}/hari)" if only_price else ""
                answer = "\n".join([
                    f"Siap. Di {selected_city} yang sesuai cuma {only_name}{price_part}.",
                    "",
                    "Kalau Kakak mau booking, ini langkahnya:",
                    "1) Buka menu Booking",
                    f"2) Pilih mobil {only_name}",
                    "3) Isi tanggal & jam sewa (ambil/kembali)",
                    "4) Upload KTP & SIM",
                    "5) Klik Konfirmasi Booking, lalu lanjutkan pembayaran yang muncul",
                ])
                return jsonify({"answer": with_greeting(answer)})

            s_now, _ = extract_seats(user_input_norm)
            if not s_now:
                last_bot_norm = get_last_bot_text(raw_history).lower()
                choice_idx = extract_choice_index(user_input_norm)
                if choice_idx and 1 <= choice_idx <= len(matched) and ('orang' not in user_input_norm and 'kursi' not in user_input_norm):
                    chosen = matched[choice_idx - 1]
                    return jsonify({"answer": with_greeting(build_booking_guide(chosen['name'], selected_city))})

                selection_cues = ('mau yang mana' in last_bot_norm) or ('yang sesuai ada' in last_bot_norm) or bool(re.search(r"\b1\)", last_bot_norm))
                is_switching_choice = bool(re.search(r"\b(gak jadi|nggak jadi|batal|ganti|ubah|jadi|jadinya)\b", user_input_norm))
                last_bot_has_booking = ('konfirmasi booking' in last_bot_norm) or ('menu booking' in last_bot_norm) or ('booking' in last_bot_norm)
                looks_like_requesting_list = any(w in user_input_norm for w in ['tampilkan', 'sebutkan', 'daftar', 'list', 'semua', 'apa saja'])

                should_try_pick_by_name = (selection_cues or is_switching_choice or last_bot_has_booking) and not looks_like_requesting_list
                if should_try_pick_by_name:
                    chosen = try_match_vehicle_name(user_input_norm, matched)
                    if chosen:
                        return jsonify({"answer": with_greeting(build_booking_guide(chosen['name'], selected_city))})

            listed = ", ".join([f"{i+1}) {m['name']} (Rp {m['harga']}/hari)" for i, m in enumerate(matched)])
            if seats_need:
                if len(matched) == 1:
                    only = matched[0]
                    return jsonify({"answer": with_greeting(build_booking_guide(only.get('name') or 'mobil ini', selected_city))})
                seats_prefix = f"Siap. Untuk {('pas ' if seats_exact else 'minimal ')}{seats_need} orang, di {selected_city} yang sesuai ada: {listed}. Mau yang mana?"
                return jsonify({"answer": with_greeting(seats_prefix)})
            if len(matched) == 1:
                only = matched[0]
                return jsonify({"answer": with_greeting(build_booking_guide(only.get('name') or 'mobil ini', selected_city))})
            return jsonify({"answer": with_greeting(f"Siap. Di {selected_city} yang sesuai ada: {listed}. Mau yang mana? Kalau mau, bisa kasih info untuk berapa orang biar saya saring lagi.")})

        # ==========================================
        # SYSTEM PROMPT: ASISTEN PLATFORM MULTI RENTAL (SMART & NATURAL)
        # ==========================================
        system_prompt = f"""Anda adalah 'Asisten Rental Mobil' yang asik, ramah, dan sangat pintar.
Tugas Anda adalah melayani user dengan informasi yang AKURAT berdasarkan data yang disediakan.

HIERARKI SUMBER INFORMASI (URUTAN PRIORITAS):
1. RIWAYAT CHAT: Ingat apa yang sudah dikatakan user sebelumnya (Lokasi, preferensi, dll). Gunakan kata ganti seperti "tadi", "itu", atau "ia" jika relevan.
2. DATA STOK (REAL-TIME): Gunakan ini untuk ketersediaan mobil, harga, dan spesifikasi unit.
3. DOKUMEN RAG: Gunakan ini untuk aturan platform, syarat sewa, denda, dan informasi cabang.
4. DATA RENTAL TERDAFTAR: Gunakan ini jika ditanya siapa saja partner rental kami.

ATURAN ANTI-HALUSINASI:
- JANGAN PERNAH mengarang informasi. Jika user mencari mobil spesifik (misal: Fortuner, Brio, Pajero, dsb) tapi TIDAK ADA di DATA STOK, informasikan dengan sopan bahwa unit tersebut tidak tersedia, lalu tawarkan unit/mobil alternatif yang ADA di DATA STOK.
- Jika user bertanya topik di luar layanan rental mobil dan tidak ada di DOKUMEN RAG, jawab bahwa info tersebut tidak tersedia dan sarankan hubungi admin cabang.
- Kami HANYA menyewakan MOBIL. Jika user minta helikopter, motor, pesawat, atau kendaraan lain, katakan dengan sopan bahwa platform kami hanya khusus untuk rental mobil.
- Jangan menebak kota jika user belum menyebutkannya dengan jelas.

=== ATURAN HARGA (PRIORITAS TERTINGGI) ===
- Jika user BERTANYA TENTANG HARGA SEWA MOBIL (seperti harga mobil spesifik): WAJIB langsung jawab menggunakan nominal angka yang tertera di DATA STOK (REAL-TIME).
- Jika user BERTANYA TENTANG DENDA, BIAYA GANTI RUGI, atau SYARAT (ada kata "denda", "syarat", "ketentuan"): WAJIB jawab menggunakan informasi dari DOKUMEN RAG.
- Jangan memberikan harga unit yang tidak ada di DATA STOK.
- DATA STOK adalah satu-satunya sumber kebenaran untuk harga sewa unit mobil harian.
==========================================

GAYA KOMUNIKASI:
- Sangat natural & manusiawi (bukan bot template).
- Gunakan sapaan yang hangat.
- Jawab singkat tapi padat.
- Hindari sapaan kaku seperti "Kak Pelanggan".
- Gunakan sapaan "Kak" di awal kalimat (seperti "Halo Kak!"), tapi JANGAN gunakan "Kak" sebagai akhiran di akhir kalimat (seperti "Ada yang bisa dibantu, Kak?"). Itu terdengar kurang natural.
- Gunakan "kamu" atau "Kakak" secara natural dalam kalimat.

KONTEKS SAAT INI:
DATA RENTAL TERDAFTAR: {laravel_context.split('DATA KOTA YANG TERSEDIA DI RENTAL INI:')[0].replace('DATA RENTAL YANG TERDAFTAR:', '').strip()}
DATA KOTA TERSEDIA: {laravel_context.split('DATA KOTA YANG TERSEDIA DI RENTAL INI:')[1].split('DATA STOK MOBIL SAAT INI')[0].strip()}
DATA STOK: {laravel_context.split('DATA STOK MOBIL SAAT INI')[1] if 'DATA STOK MOBIL SAAT INI' in laravel_context else 'Kosong'}
{city_info_context}
{matched_context}
{rag_context}

LOGIKA CHAT UTAMA:
1. Jika user bertanya tentang rental yang terdaftar: Sebutkan dari DATA RENTAL TERDAFTAR.
2. PRIORITAS UTAMA — Jika user bertanya HARGA SEWA, DENDA, atau SYARAT:
   - Jika ditanya HARGA SEWA MOBIL: LANGSUNG jawab dengan harga dari DATA STOK.
   - Jika ditanya DENDA / SYARAT UMUM: Jawab dari DOKUMEN RAG.
   - Format jawaban: "Harga [nama mobil] Rp [harga]/hari"
3. Jika user bertanya hal umum/aturan/CARA BOOKING: 
   - Jawab menggunakan DOKUMEN RAG. 
   - Jika ditanya CARA BOOKING, WAJIB sebutkan langkah ini: 1) Buka menu Booking, 2) Pilih mobil, 3) Isi tanggal/jam, 4) Upload KTP & SIM, 5) Konfirmasi & Bayar.
4. Jika user menyapa: Sapa balik dengan ramah dan tawarkan bantuan.
5. Jika user ingin CARI MOBIL (bukan tanya harga): ANDA WAJIB memastikan KOTA dipilih dulu, lalu tampilkan SEMUA unit yang cocok dari DATA STOK.
6. Jika user sudah pilih unit: Berikan panduan booking website.

INGAT: Anda asisten satu platform, bukan satu rental saja. Jangan menyebut nama rental tertentu kecuali ditanya."""
        messages = [
            {
                "role": "system",
                "content": system_prompt
            }
        ]
        
        # Masukkan History Percakapan (Ingatan AI)
        for h in raw_history:
            if isinstance(h, dict):
                if h.get('user'): messages.append({"role": "user", "content": h['user']})
                if h.get('bot'): messages.append({"role": "assistant", "content": h['bot']})
            
        # Pertanyaan Terkini
        messages.append({"role": "user", "content": user_input})

        # Eksekusi ke Llama 3.1
        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=messages,
            temperature=0.7,
            max_tokens=1024
        )

        return jsonify({"answer": completion.choices[0].message.content})

    except Exception as e:
        error_msg = str(e)
        import traceback
        with open('error.log', 'w') as f:
            traceback.print_exc(file=f)
        try:
            print(f"Error Internal Flask: {error_msg}")
        except Exception:
            pass
        return jsonify({"error": "Sistem kami sedang memproses permintaan, mohon tunggu sebentar."}), 500
        
@app.route('/search', methods=['POST'])
def search():
    """
    Smart Search dengan implementasi RAG yang benar:
    1. RETRIEVAL  : Gunakan ChromaDB vector similarity_search() untuk mencari
                   dokumen pengetahuan yang relevan dengan query pengguna.
    2. AUGMENT    : Gabungkan hasil retrieval (pengetahuan kategori mobil) dengan
                   data stok real-time dari MySQL (dikirim via Laravel context).
    3. GENERATION : Kirim semua ke LLM untuk menghasilkan rekomendasi.
    """
    import json as _json

    try:
        data = request.json
        query = data.get('query', '')
        context = data.get('context', '')
        rental_id = str(data.get('rental_id', '1'))

        if not query:
            return jsonify({"results": [], "summary": "Query pencarian tidak boleh kosong.", "source": "error"}), 400

        # ─── TAHAP 1: RETRIEVAL dari ChromaDB ───────────────────────────────
        # Gunakan vector similarity search untuk mencari dokumen pengetahuan
        # yang paling relevan dengan query pengguna (kategori mobil, FAQ, dll)
        retrieved_docs = vector_store.similarity_search(query, k=5)
        knowledge_context = "\n\n".join([
            f"[DOKUMEN PENGETAHUAN {i+1}]\n{doc.page_content}"
            for i, doc in enumerate(retrieved_docs)
        ]) if retrieved_docs else "Tidak ada pengetahuan relevan ditemukan."

        # ─── TAHAP 2: PARSE stok real-time dari Laravel (MySQL) ─────────────
        # Data stok real-time yang dikirim Laravel — ini adalah "structured data"
        # yang dikombinasikan dengan "unstructured knowledge" dari ChromaDB
        items = []
        if 'DATA STOK MOBIL SAAT INI' in context:
            stock_text = context.split('DATA STOK MOBIL SAAT INI', 1)[1]
            lines = [ln.strip() for ln in stock_text.splitlines() if ln.strip().startswith('-')]
            for ln in lines:
                raw = re.sub(r'^- (UNIT: )?', '', ln).strip()
                parts = [p.strip() for p in raw.split('|')]
                if not parts:
                    continue
                item = {}
                for p in parts:
                    pl = p.lower()
                    if 'id:' in pl:
                        try:
                            item['id'] = int(re.search(r'\d+', p).group())
                        except Exception:
                            pass
                    elif 'unit:' in pl:
                        item['name'] = p.split(':', 1)[1].strip()
                    elif 'kota:' in pl:
                        item['kota'] = p.split(':', 1)[1].strip()
                    elif 'harga:' in pl:
                        nums = re.findall(r'\d+', p.replace('.', ''))
                        item['harga'] = int(''.join(nums)) if nums else 0
                    elif 'tipe:' in pl:
                        item['tipe'] = p.split(':', 1)[1].strip()
                    elif 'kapasitas:' in pl:
                        nums = re.findall(r'\d+', p)
                        item['kursi'] = int(nums[0]) if nums else 0
                    elif 'transmisi:' in pl:
                        item['transmisi'] = p.split(':', 1)[1].strip()
                    elif 'bahan bakar:' in pl:
                        item['bbm'] = p.split(':', 1)[1].strip()
                    elif 'deskripsi:' in pl:
                        item['deskripsi'] = p.split(':', 1)[1].strip()
                if 'id' in item:
                    items.append(item)

        if not items:
            return jsonify({
                "results": [],
                "summary": "Tidak ada stok mobil yang tersedia untuk dicari saat ini.",
                "source": "no_stock"
            })

        # Format stok real-time
        item_list = "\n".join([
            f"- ID: {it.get('id')} | {it.get('name','')} | Kota: {it.get('kota','')} "
            f"| Harga: Rp {it.get('harga',0):,}/hari | Tipe: {it.get('tipe','')} "
            f"| {it.get('kursi','')} Kursi | {it.get('transmisi','')} "
            f"| BBM: {it.get('bbm','')} | Deskripsi: {it.get('deskripsi','')}"
            for it in items
        ])

        # ─── TAHAP 3: AUGMENTED GENERATION ke LLM ────────────────────────────
        # Gabungkan pengetahuan ChromaDB (semantik) + stok MySQL (real-time)
        # lalu minta LLM untuk menghasilkan rekomendasi berdasarkan KEDUANYA
        prompt = f"""Anda adalah sistem RAG (Retrieval-Augmented Generation) untuk pencarian mobil rental.

### PENGETAHUAN RELEVAN (dari ChromaDB vector similarity search):
{knowledge_context}

### STOK MOBIL TERSEDIA SAAT INI (data real-time dari database):
{item_list}

### PERMINTAAN PENGGUNA:
"{query}"

### INSTRUKSI:
Gunakan PENGETAHUAN di atas untuk memahami karakteristik jenis mobil, kemudian cocokkan dengan STOK TERSEDIA untuk memilih maksimal 3 mobil yang PALING RELEVAN dengan permintaan.

Jika pengguna menyebut kota/lokasi, WAJIB prioritaskan mobil di kota tersebut.
Kata "irit" berarti harga terjangkau (di bawah rata-rata) ATAU bahan bakar Bensin/Listrik yang hemat.

Balas HANYA dengan JSON valid ini (tidak ada teks lain):
{{"results": [{{"id": <integer_id_mobil>, "reason": "<alasan singkat kenapa cocok>"}}], "summary": "<ringkasan rekomendasi dalam 1-2 kalimat>"}}

Jika benar-benar tidak ada yang cocok: {{"results": [], "summary": "<penjelasan kenapa tidak ada yang cocok>"}}"""

        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=[{"role": "user", "content": prompt}],
            temperature=0.1,
            max_tokens=600
        )

        raw = completion.choices[0].message.content.strip()

        # Parse JSON dari respons LLM
        json_match = re.search(r'\{.*\}', raw, re.DOTALL)
        if not json_match:
            return jsonify({
                "results": [],
                "summary": "AI tidak dapat memproses pencarian ini. Coba dengan kata kunci yang lebih spesifik.",
                "source": "parse_error"
            })

        ai_result = _json.loads(json_match.group())
        return jsonify({
            "results": ai_result.get("results", []),
            "summary": ai_result.get("summary", "Berikut rekomendasi berdasarkan pencarian Anda."),
            "source": "rag_hybrid"  # Hybrid RAG: ChromaDB + MySQL + LLM
        })

    except Exception as e:
        print(f"Error /search: {e}")
        import traceback
        traceback.print_exc()
        return jsonify({"results": [], "summary": "Terjadi kesalahan pada server pencarian.", "source": "error"}), 500


@app.route('/admin-assist', methods=['POST'])
def admin_assist():
    """Endpoint untuk Asisten AI khusus Mitra/Admin."""
    try:
        data = request.json
        question = data.get('question', '')
        context = data.get('context', '')

        if not question:
            return jsonify({"answer": "Pertanyaan tidak boleh kosong."}), 400

        # Gunakan ChromaDB untuk mengambil pengetahuan bisnis yang relevan
        retrieved_docs = vector_store.similarity_search(question, k=3)
        knowledge = "\n".join([doc.page_content for doc in retrieved_docs]) if retrieved_docs else ""

        prompt = f"""Anda adalah Asisten Bisnis Rental Mobil yang cerdas dan profesional.

PENGETAHUAN BISNIS RELEVAN:
{knowledge}

DATA OPERASIONAL RENTAL ANDA:
{context}

PERTANYAAN MITRA: {question}

Berikan jawaban yang singkat, profesional, dan actionable dalam Bahasa Indonesia."""

        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=[{"role": "user", "content": prompt}],
            temperature=0.3,
            max_tokens=600
        )
        answer = completion.choices[0].message.content.strip()
        return jsonify({"answer": answer})

    except Exception as e:
        print(f"Error /admin-assist: {e}")
        return jsonify({"answer": "Maaf, asisten sedang tidak bisa dihubungi."}), 500


if __name__ == "__main__":
    print("\nMESIN AI GROQ AKTIF (TRUE RAG: ChromaDB + MySQL + LLM)!")
    print("Menunggu perintah dari Laravel di port 7860...")
    app.run(host='0.0.0.0', port=7860, debug=False)

