import json
from rag_engine import app, get_fuzzy_city

def test_bot():
    print("=== STARTING BOT LOGIC TESTING ===\n")
    client = app.test_client()

    # Data simulasi dari Laravel sesuai database riil (screenshot phpMyAdmin)
    mock_context = """
DATA RENTAL YANG TERDAFTAR:
Fz Rent Car, Putra Widjaya Rent Car, AA RENT CAR, Evan Rental, PT Trans Nusantara Gemilang

DATA KOTA YANG TERSEDIA DI RENTAL INI:
Pekanbaru, Jakarta

DATA STOK MOBIL SAAT INI (REAL-TIME):
- ID: 1 | UNIT: Daihatsu Xenia facelift | Cabang: Pekanbaru | Harga: Rp 350.000/hari | Tipe: Compact MPV | Transmisi: matic | Kursi: 7 | BBM: Bensin | Mitra: Fz Rent Car
- ID: 2 | UNIT: Toyota Rush GR | Cabang: Jakarta | Harga: Rp 350.000/hari | Tipe: SUV | Transmisi: matic | Kursi: 7 | BBM: Bensin | Mitra: Putra Widjaya Rent Car
- ID: 3 | UNIT: Toyota All New Veloz | Cabang: Jakarta | Harga: Rp 300.000/hari | Tipe: Compact MPV | Transmisi: manual | Kursi: 7 | BBM: Bensin | Mitra: Putra Widjaya Rent Car
- ID: 4 | UNIT: Toyota All New Ayla | Cabang: Pekanbaru | Harga: Rp 300.000/hari | Tipe: City Car | Transmisi: manual | Kursi: 4 | BBM: Bensin | Mitra: Fz Rent Car
- ID: 5 | UNIT: Toyota Innova Reborn | Cabang: Jakarta | Harga: Rp 550.000/hari | Tipe: Compact MPV | Transmisi: manual | Kursi: 7 | BBM: Bensin | Mitra: AA RENT CAR
- ID: 6 | UNIT: Daihatsu Sigra | Cabang: Pekanbaru | Harga: Rp 300.000/hari | Tipe: Compact MPV | Transmisi: manual | Kursi: 7 | BBM: Bensin | Mitra: Fz Rent Car
- ID: 7 | UNIT: Daihatsu Rocky | Cabang: Pekanbaru | Harga: Rp 350.000/hari | Tipe: Mini MPV | Transmisi: matic | Kursi: 4 | BBM: Bensin | Mitra: Evan Rental
"""

    scenarios = [
        {
            "name": "1. Greeting Test",
            "input": {"question": "halo", "context": mock_context, "history": []},
            "not_contains": ["kota mana"]
        },
        {
            "name": "2. Search All-in-one",
            "input": {"question": "mau innova di jakarta tanggal 20 april", "context": mock_context, "history": []},
            "expected_contains": ["Innova", "Jakarta", "20 april"]
        },
        {
            "name": "3. State Recovery: Date Only",
            "input": {"question": "untuk tanggal 20 april", "context": mock_context, "history": [
                {"user": "cari innova di jakarta", "bot": "Oke, Innova di Jakarta ready. Mau tanggal berapa?"}
            ]},
            "expected_contains": ["Innova", "Jakarta", "20 april"]
        },
        {
            "name": "4. Casual Exploration",
            "input": {"question": "mau tanya tanya dulu", "context": mock_context, "history": []},
            "not_contains": ["booking", "sewa"]
        }
    ]

    for s in scenarios:
        print(f"Running: {s['name']}")
        response = client.post('/chat', 
                               data=json.dumps(s['input']),
                               content_type='application/json')
        
        data = json.loads(response.data)
        answer = data.get('answer', '')
        
        print(f"Input: {s['input']['question']}")
        print(f"Output: {answer}")
        
        # Validation
        success = True
        if "expected_contains" in s:
            for term in s["expected_contains"]:
                if term.lower() not in answer.lower():
                    print(f"FAILED: Expected term '{term}' not found.")
                    success = False
        
        if "not_contains" in s:
            for term in s["not_contains"]:
                if term.lower() in answer.lower():
                    print(f"FAILED: Prohibited term '{term}' found.")
                    success = False
        
        if success:
            print("RESULT: PASSED")
        else:
            print("RESULT: FAILED")
        print("-" * 30)

    # Test Fuzzy City Helper Directly
    print("\nTesting Fuzzy City Helper:")
    cities = ["Pekanbaru", "Jakarta"]
    test_typos = [("pekambaru", "Pekanbaru"), ("pknbaru", "Pekanbaru"), ("jkrta", "Jakarta")]
    
    for typo, expected in test_typos:
        result = get_fuzzy_city(typo, cities)
        if result == expected:
            print(f"Fuzzy Match '{typo}' -> '{result}' OK")
        else:
            print(f"Fuzzy Match '{typo}' -> '{result}' (Expected: {expected}) FAIL")

if __name__ == "__main__":
    test_bot()
