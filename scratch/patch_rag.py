import re
def normalize_text(text: str) -> str:
    t = (text or '').strip().lower()
    t = re.sub(r"[^\w\s]", " ", t, flags=re.UNICODE)
    t = re.sub(r"\s+", " ", t).strip()
    return t

context = """DATA RENTAL YANG TERDAFTAR:
FZ Rent, Putra Wijaya

DATA KOTA YANG TERSEDIA DI RENTAL INI:
Pekanbaru, Jakarta

DATA STOK MOBIL SAAT INI (REAL-TIME):
- ID: 5 | UNIT: Toyota Innova | Cabang: Pekanbaru | Harga: Rp 500.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin
- ID: 4 | UNIT: Daihatsu Sigra | Cabang: Pekanbaru | Harga: Rp 350.000/hari | Tipe: mpv | Transmisi: manual | Kursi: 7 | BBM: bensin
"""

cities_raw = context.split('DATA KOTA YANG TERSEDIA DI RENTAL INI:')[1].split('DATA STOK MOBIL SAAT INI')[0].strip()
available_cities = [c.strip() for c in cities_raw.split(',') if c.strip() and c.strip().lower() not in ('tidak ada cabang', '')]
available_cities_norm = [c.lower() for c in available_cities]
print("available_cities:", available_cities)

def parse_stock_items(context: str) -> list[dict]:
    if 'DATA STOK MOBIL SAAT INI' not in context:
        return []
    stock_text = context.split('DATA STOK MOBIL SAAT INI', 1)[1]
    lines = [ln.strip() for ln in stock_text.splitlines() if ln.strip().startswith('-')]
    items = []
    for ln in lines:
        raw = re.sub(r"^- ", "", ln).strip()
        parts = [p.strip() for p in raw.split('|')]
        if not parts: continue
        
        car_id = ''
        name = ''
        
        if parts[0].upper().startswith('ID:'):
            car_id = parts[0].split(':', 1)[1].strip()
            if len(parts) > 1:
                name = parts[1].split(':', 1)[1].strip() if parts[1].upper().startswith('UNIT:') else parts[1]
        else:
            name = parts[0]
            if name.upper().startswith('UNIT:'):
                name = name.split(':', 1)[1].strip()

        kota = ''
        for p in parts[1:]:
            pl = p.lower()
            if 'cabang:' in pl:
                kota = p.split(':', 1)[1].strip()
        items.append({'id': car_id, 'name': name, 'kota': kota})
    return items

print("items:", parse_stock_items(context))

history = [{'user': 'saya mencari mobil keluarga', 'bot': 'Mau cari mobil di kota mana?'}]
def get_most_recent_city(texts: list[str]) -> str | None:
    for t in texts:
        tl = (t or '').lower()
        for city in available_cities:
            if city.lower() in tl:
                return city
    return None

import json
history_texts = []
for h in history:
    if isinstance(h, dict):
        if h.get('user'): history_texts.append(normalize_text(h['user']))
        if h.get('bot'): history_texts.append(normalize_text(h['bot']))
user_input_norm = "pekanbaru"
history_texts.append(user_input_norm)
selected_city = get_most_recent_city(history_texts)
print("selected_city:", selected_city)
