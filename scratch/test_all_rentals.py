import requests, time
time.sleep(2)

BASE_CTX = 'DATA RENTAL YANG TERDAFTAR:\nFz Rent Car, Putra Wijaya Rent Car, AA RENT CAR\n\nDATA KOTA YANG TERSEDIA DI RENTAL INI:\nPekanbaru\n\nDATA STOK MOBIL SAAT INI (REAL-TIME):\n- UNIT: Toyota Innova Reborn | Cabang: Pekanbaru | Tipe: MPV | Transmisi: manual | Kursi: 7 | BBM: bensin\n- UNIT: Toyota Alphard | Cabang: Pekanbaru | Tipe: MPV | Transmisi: matic | Kursi: 7 | BBM: bensin\n- UNIT: Mitsubishi Xpander | Cabang: Pekanbaru | Tipe: MPV | Transmisi: matic | Kursi: 7 | BBM: bensin\n'

def ask(question, rental_id='1'):
    payload = {
        'question': question,
        'user_name': 'Budi',
        'context': BASE_CTX,
        'rental_id': str(rental_id),
        'history': []
    }
    r = requests.post('http://localhost:5000/chat', json=payload, timeout=20)
    return r.json().get('answer', '[ERROR]')

tests = [
    ("Berapa harga sewa Innova Reborn bensin di FZ Rent?", "1", "550"),
    ("Berapa harga sewa Fortuner di FZ Rent?",            "1", "1.600"),
    ("Berapa harga sewa Alphard?",                        "1", "5.000"),
    ("Berapa harga sewa Xpander?",                        "2", "450"),
    ("Apa syarat untuk booking?",                         "1", "ktp"),
    ("Berapa denda kalau telat kembalikan mobil?",        "1", "10"),
]

passed = 0
for q, rid, kw in tests:
    ans = ask(q, rid)
    ok = kw.lower() in ans.lower()
    status = "PASS" if ok else "FAIL"
    if ok: passed += 1
    print(f"[{status}] Q: {q}")
    if not ok:
        print(f"       Expected keyword: '{kw}' | Got: {ans[:120]}")

print(f"\nHasil: {passed}/{len(tests)} lulus")
