import re
import pprint

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

available_cities = ['pekanbaru', 'jakarta']
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
print("wants_any_filter:", wants_any_filter)

car_intent_keywords = ['cari mobil', 'mencari mobil', 'butuh mobil', 'sewa mobil', 'rental mobil', 'butuh kendaraan']
has_car_intent = False
intent_text = user_input_norm
for t in reversed(history_texts):
    if any(k in t for k in car_intent_keywords) or ('mobil' in t and ('cari' in t or 'sewa' in t or 'rental' in t)):
        has_car_intent = True
        break

is_other_vehicle = False
has_car_intent_or_filter = (has_car_intent or wants_any_filter) and not is_other_vehicle
print("has_car_intent_or_filter:", has_car_intent_or_filter)

def contains_any_city(text: str) -> bool:
    t = (text or '').lower()
    return any(city in t for city in [c.lower() for c in available_cities])
print("contains_any_city:", contains_any_city(user_input_norm))

wants_list_explicitly = False
is_question = False

cond = selected_city and not wants_any_filter and (has_car_intent_or_filter or contains_any_city(user_input_norm) or wants_list_explicitly) and not is_question
print("CONDITION 612 IS:", cond)

if cond:
    print("EXECUTING LINE 612")
else:
    print("BYPASSING LINE 612!")
