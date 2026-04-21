import re

def normalize_text(text: str) -> str:
    t = (text or '').strip().lower()
    t = re.sub(r"[^\w\s]", " ", t, flags=re.UNICODE)
    t = re.sub(r"\s+", " ", t).strip()
    return t

user_input = "pekanbaru"
user_input_norm = normalize_text(user_input)
rest_norm = ""

raw_history = [
    {"user": "halo", "bot": "Selamat siang, apa yang bisa saya bantu?"},
    {"user": "saya mencari mobil keluarga", "bot": "Mau cari mobil di kota mana?"}
]

car_intent_keywords = ['cari mobil', 'mencari mobil', 'butuh mobil', 'sewa mobil', 'rental mobil', 'butuh kendaraan']

history_texts = []
for h in raw_history:
    if isinstance(h, dict):
        if h.get('user'): history_texts.append(normalize_text(h['user']))
        if h.get('bot'): history_texts.append(normalize_text(h['bot']))
history_texts.append(rest_norm or user_input_norm)

is_other_vehicle = False

intent_text = rest_norm or user_input_norm
has_car_intent = (any(k in intent_text for k in car_intent_keywords) or ('mobil' in intent_text and ('cari' in intent_text or 'sewa' in intent_text or 'rental' in intent_text))) and not is_other_vehicle

print("has_car_intent before:", has_car_intent)
if has_car_intent:
    pass
else:
    for t in reversed(history_texts):
        if any(k in t for k in car_intent_keywords) or ('mobil' in t and ('cari' in t or 'sewa' in t or 'rental' in t)):
            has_car_intent = True
            break
print("has_car_intent after:", has_car_intent)

selected_city = None
is_question = False
is_price_question = False

if has_car_intent and not selected_city and not is_question and not is_price_question:
    print("LINE 603 HIT!")
else:
    print("LINE 603 BYPASSED!")
