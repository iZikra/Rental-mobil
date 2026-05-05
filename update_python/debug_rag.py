import sys
sys.path.append('.')
from rag_engine import app, chat

with app.test_request_context('/chat', method='POST', json={
    'question': 'berapa harga sewa alphard?',
    'user_name': 'Tester',
    'context': '- UNIT: Toyota Alphard | Cabang: Pekanbaru | Tipe: sedan | Transmisi: matic | Kursi: 5 | BBM: bensin\n',
    'rental_id': '1',
    'history': []
}):
    res = chat()
    print(res.get_data(as_text=True))
