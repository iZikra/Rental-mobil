import sys
import os
import json

# Add python_service to path
sys.path.append(os.path.abspath(os.path.join(os.path.dirname(__file__), '..', 'python_service')))

from rag_engine import app

print("Menjalankan Simulasi Chatbot Testing...\n" + "="*40)

# Mock context
context = """DATA KOTA YANG TERSEDIA DI RENTAL INI:
Pekanbaru, Padang
DATA STOK MOBIL SAAT INI (REAL-TIME):
- ID: 1 | UNIT: Toyota Innova Reborn | Cabang: Pekanbaru | Harga: 550.000 | Tipe: mpv | Transmisi: manual | Kursi: 7 | BBM: bensin
- ID: 4 | UNIT: Daihatsu Sigra | Cabang: Pekanbaru | Harga: 300.000 | Tipe: mpv | Transmisi: manual | Kursi: 7 | BBM: bensin
- ID: 5 | UNIT: Daihatsu Xenia | Cabang: Pekanbaru | Harga: 350.000 | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin
- ID: 6 | UNIT: Toyota Ayla | Cabang: Pekanbaru | Harga: 250.000 | Tipe: city car | Transmisi: manual | Kursi: 4 | BBM: bensin
- ID: 7 | UNIT: Daihatsu Rocky | Cabang: Padang | Harga: 350.000 | Tipe: suv | Transmisi: matic | Kursi: 4 | BBM: bensin
"""

client = app.test_client()

def test_query(question):
    print(f"\nUser: {question}")
    payload = {
        "question": question,
        "context": context,
        "history": []
    }
    res = client.post('/chat', json=payload)
    if res.status_code == 200:
        data = json.loads(res.data)
        print(f"Bot:  {data.get('answer')}")
    else:
        print(f"Error {res.status_code}: {res.data}")

# Test 1: Beli mobil kota padang
test_query("ada mobil apa aja di padang?")

# Test 2: Mobil keluarga (harusnya Innova, Xenia, Sigra)
test_query("buat keluarga di pekanbaru ada?")

# Test 3: Muat bertiga
test_query("yang muat untuk bertiga di pekanbaru?")

# Test 4: Langsung sebut nama mobil
test_query("xenia aja buat tgl 20 april")
