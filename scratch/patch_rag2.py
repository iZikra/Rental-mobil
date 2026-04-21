import sys
sys.path.append(r'c:\Users\GF 63\rental-mobil\python_service')
from rag_engine import parse_stock_items

context = """DATA STOK MOBIL SAAT INI (REAL-TIME):
- ID: 5 | UNIT: Toyota Innova | Cabang: Pekanbaru | Harga: Rp 500.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin
- ID: 4 | UNIT: Daihatsu Sigra | Cabang: Pekanbaru | Harga: Rp 350.000/hari | Tipe: mpv | Transmisi: manual | Kursi: 7 | BBM: bensin"""

print(parse_stock_items(context))
