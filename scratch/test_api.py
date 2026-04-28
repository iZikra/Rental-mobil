import requests

try:
    url = "http://127.0.0.1:5000/chat"
    payload = {
        "question": "hai",
        "user_name": "Tester",
        "context": "Context data",
        "rental_id": "1",
        "history": []
    }
    response = requests.post(url, json=payload)
    print(f"Status: {response.status_code}")
    print(f"Response: {response.text}")
except Exception as e:
    print(f"Request failed: {e}")
