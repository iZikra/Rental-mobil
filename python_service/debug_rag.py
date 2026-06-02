import sys
sys.path.append('.')
from rag_engine import app, chat
with app.test_request_context('/chat', method='POST', json={'question': 'Berapa denda kalau saya telat mengembalikan mobil lebih dari jam yang ditentukan di FZ Rent Car', 'user_name': 'Tester', 'context': '', 'rental_id': 'global', 'history': []}):
    res = chat()
    print(res.get_data(as_text=True))
