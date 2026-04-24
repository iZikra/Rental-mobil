import json
import urllib.request

url = 'http://127.0.0.1:5000/chat'
data = {
    'question': 'saya mau lihat mobil hemat di pekanbaru',
    'context': '''DATA RENTAL YANG TERDAFTAR:
Rental AAA

DATA KOTA YANG TERSEDIA DI RENTAL INI:
Pekanbaru, Jakarta

DATA STOK MOBIL SAAT INI (REAL-TIME):
- ID: 1 | UNIT: Toyota Ayla | Cabang: Pekanbaru | Harga: Rp 300000/hari | Tipe: Hatchback | Transmisi: Matic | Kursi: 5 | BBM: Bensin | Mitra: Rental AAA
- ID: 2 | UNIT: Toyota Agya | Cabang: Pekanbaru | Harga: Rp 300000/hari | Tipe: Hatchback | Transmisi: Matic | Kursi: 5 | BBM: Bensin | Mitra: Rental AAA
- ID: 3 | UNIT: Toyota Avanza | Cabang: Pekanbaru | Harga: Rp 450000/hari | Tipe: MPV | Transmisi: Matic | Kursi: 7 | BBM: Bensin | Mitra: Rental AAA
'''
}

req = urllib.request.Request(url, json.dumps(data).encode('utf-8'), {'Content-Type': 'application/json'})
try:
    response = urllib.request.urlopen(req)
    print(response.read().decode('utf-8'))
except Exception as e:
    print(e)
