import requests

payload = {
    'question': 'Berdasarkan dokumen RAG, berapa harga sewa unit Alphard? Tolong sebutkan harga aslinya.',
    'user_name': 'Kak Budi',
    'context': 'DATA RENTAL YANG TERDAFTAR:\nFZ Rent, Berkah Rent\n\nDATA KOTA YANG TERSEDIA DI RENTAL INI:\nPekanbaru\n\nDATA STOK MOBIL SAAT INI (REAL-TIME):\n- UNIT: Toyota Alphard | Cabang: Pekanbaru | Tipe: sedan | Transmisi: matic | Kursi: 5 | BBM: bensin\n',
    'rental_id': '1',
    'history': []
}

res = requests.post('http://localhost:5000/chat', json=payload)
print("ANSWER ---> ", res.json().get('answer'))
