import re

def normalize_text(text: str) -> str:
    t = (text or '').strip().lower()
    t = re.sub(r"[^\w\s]", " ", t, flags=re.UNICODE)
    t = re.sub(r"\s+", " ", t).strip()
    return t

user_input = "pekanbaru"
user_input_norm = normalize_text(user_input)

raw_history = [
    {"user": "halo", "bot": "Selamat siang, apa yang bisa saya bantu?"},
    {"user": "saya mencari mobil keluarga", "bot": "Mau cari mobil di kota mana?"}
]

history_texts = []
for h in raw_history:
    if isinstance(h, dict):
        if h.get('user'): history_texts.append(normalize_text(h['user']))
        if h.get('bot'): history_texts.append(normalize_text(h['bot']))
history_texts.append(user_input_norm)

available_cities = ['jakarta']
def get_most_recent_city(texts: list[str]) -> str | None:
    for t in texts:
        tl = (t or '').lower()
        for city in available_cities:
            if city.lower() in tl:
                return city
    return None
selected_city = get_most_recent_city(history_texts)
print("selected_city:", selected_city)

is_matic = False
is_manual = False
type_need = ""
seats_need = 0
is_bensin = False
is_diesel = False
wants_any_filter = is_matic or is_manual or bool(type_need) or bool(seats_need) or is_bensin or is_diesel

car_intent_keywords = ['cari mobil', 'mencari mobil', 'butuh mobil', 'sewa mobil', 'rental mobil', 'butuh kendaraan']
has_car_intent = False
intent_text = user_input_norm
for t in reversed(history_texts):
    if any(k in t for k in car_intent_keywords) or ('mobil' in t and ('cari' in t or 'sewa' in t or 'rental' in t)):
        has_car_intent = True
        break

is_question = False
is_price_question = False

if has_car_intent and not selected_city and not is_question and not is_price_question:
    print("LINE 603 HIT: Mau cari mobil di mana?")
else:
    print("LINE 603 BYPASSED!")
