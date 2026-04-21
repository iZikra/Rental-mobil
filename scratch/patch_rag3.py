import json
from unittest.mock import MagicMock
import sys
import re

# Mocking Flask
class DummyApp:
    def route(self, *args, **kwargs):
        def wrapper(f): return f
        return wrapper

import rag_engine
rag_engine.app = DummyApp()
rag_engine.client = MagicMock()
rag_engine.vector_store = MagicMock()
rag_engine.jsonify = lambda x: print("JSONIFY OUTPUT:", json.dumps(x, indent=2))
rag_engine.request = MagicMock()
rag_engine.request.json = {
    'question': 'pekanbaru',
    'history': [{'user': 'saya mencari mobil keluarga', 'bot': 'Mau cari mobil di kota mana?'}],
    'rental_id': '1',
    'context': """DATA RENTAL YANG TERDAFTAR:
FZ Rent, Putra Wijaya

DATA KOTA YANG TERSEDIA DI RENTAL INI:
Pekanbaru, Jakarta

DATA STOK MOBIL SAAT INI (REAL-TIME):
- ID: 5 | UNIT: Toyota Innova | Cabang: Pekanbaru | Harga: Rp 500.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin
- ID: 4 | UNIT: Daihatsu Sigra | Cabang: Pekanbaru | Harga: Rp 350.000/hari | Tipe: mpv | Transmisi: manual | Kursi: 7 | BBM: bensin
"""
}

# Instead of calling api, print messages!
def mock_create(model, messages, **kwargs):
    print("LLM MESSAGES:", json.dumps(messages, indent=2))
    raise Exception("Simulated Groq Call")
rag_engine.client.chat.completions.create = mock_create

rag_engine.chat()
