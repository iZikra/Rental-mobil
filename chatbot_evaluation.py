import json
import time
import requests

# Configuration
CHATBOT_URL = "http://127.0.0.1:5000/chat"  # Python Flask service
CONTEXT = (
    "DATA RENTAL YANG TERDAFTAR:\n"
    "FZ Rent, Berkah Rent\n\n"
    "DATA KOTA YANG TERSEDIA DI RENTAL INI:\n"
    "Pekanbaru, Jakarta\n\n"
    "DATA STOK MOBIL SAAT INI (REAL-TIME):\n"
    "- UNIT: Toyota Avanza | Cabang: Pekanbaru | Harga: Rp 300.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
    "- UNIT: Daihatsu Xenia | Cabang: Pekanbaru | Harga: Rp 280.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
    "- UNIT: Toyota Innova | Cabang: Jakarta | Harga: Rp 500.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: diesel\n"
    "- UNIT: Toyota Agya | Cabang: Pekanbaru | Harga: Rp 220.000/hari | Tipe: city car | Transmisi: manual | Kursi: 5 | BBM: bensin\n"
)

# Advanced Test Cases
test_cases = [
    {
        "name": "Multi-Filter Query",
        "question": "Halo, saya cari mobil matic di Pekanbaru buat 7 orang",
        "expected_contains": ["Avanza", "Xenia", "300.000", "280.000"],
        "description": "Mengetes kemampuan bot memproses sapaan + kota + transmisi + kursi dalam satu pesan."
    },
    {
        "name": "RAG Knowledge (Denda)",
        "question": "Berapa denda kalau saya telat balikin mobilnya?",
        "expected_contains": ["denda", "telat"], # Mengandalkan dokumen RAG
        "description": "Mengetes pengambilan informasi dari dokumen lokal (syarat & ketentuan)."
    },
    {
        "name": "Platform Partner List",
        "question": "Sebutkan semua rental yang sudah terdaftar di sini",
        "expected_contains": ["FZ Rent", "Berkah Rent"],
        "description": "Mengetes kemampuan menyebutkan nama partner rental dari konteks."
    },
    {
        "name": "Out of Scope (Anti-Hallucination)",
        "question": "Saya mau sewa helikopter di Jakarta, ada?",
        "expected_contains": ["maaf", "tidak"], # Lebih fleksibel (tidak menyewakan, tidak ada, dll)
        "description": "Mengetes agar bot tidak berhalusinasi menyediakan layanan di luar rental mobil."
    },
    {
        "name": "Typo & Context (Price Inquiry)",
        "question": "xeniya di pknbaru harganya brp?",
        "expected_contains": ["280.000"],
        "description": "Mengetes ketahanan bot terhadap typo berat (xeniya, pknbaru, brp) dan pencarian harga spesifik."
    },
    {
        "name": "Payment Method deterministic",
        "question": "Gimana cara bayarnya?",
        "expected_contains": ["Midtrans", "QRIS", "Transfer"],
        "description": "Mengetes jawaban prosedural tentang metode pembayaran."
    }
]

def evaluate_chatbot():
    print("=== ADVANCED CHATBOT EVALUATION (STRESS TEST) ===")
    print(f"Target URL: {CHATBOT_URL}")
    print("-" * 50)
    
    total_cases = len(test_cases)
    passed_cases = 0
    total_response_time = 0
    
    results = []
    
    for case in test_cases:
        print(f"Testing: {case['name']}...", end="", flush=True)
        start_time = time.time()
        try:
            payload = {
                "question": case["question"],
                "user_name": "Pro Tester",
                "context": CONTEXT,
                "rental_id": "1",
                "history": []
            }
            response = requests.post(CHATBOT_URL, json=payload, timeout=25)
            end_time = time.time()
            
            if response.status_code == 200:
                answer = response.json().get("answer", "")
                duration = end_time - start_time
                total_response_time += duration
                
                # Check accuracy
                passed = all(needle.lower() in answer.lower() for needle in case["expected_contains"])
                if passed:
                    passed_cases += 1
                    print(" [PASS]")
                else:
                    print(" [FAIL]")
                
                results.append({
                    "name": case["name"],
                    "passed": passed,
                    "duration": duration,
                    "answer": answer,
                    "expected": case["expected_contains"]
                })
            else:
                print(f" [ERROR: {response.status_code}]")
                results.append({
                    "name": case["name"],
                    "passed": False,
                    "error": f"Status code {response.status_code}"
                })
        except Exception as e:
            print(f" [EXCEPTION]")
            results.append({
                "name": case["name"],
                "passed": False,
                "error": str(e)
            })
            
    # Calculate Metrics
    accuracy = (passed_cases / total_cases) * 100 if total_cases > 0 else 0
    avg_response_time = total_response_time / passed_cases if passed_cases > 0 else 0
    
    print("\n" + "=" * 50)
    print("FINAL RESULTS")
    print("=" * 50)
    print(f"Total Test Cases      : {total_cases}")
    print(f"Passed                : {passed_cases}")
    print(f"Failed                : {total_cases - passed_cases}")
    print(f"Accuracy              : {accuracy:.2f}%")
    print(f"Avg Response (Passed) : {avg_response_time:.2f}s")
    print("-" * 50)
    
    if total_cases - passed_cases > 0:
        print("\nFAILED CASE DETAILS:")
        for res in results:
            if not res.get("passed"):
                print(f"\n[!] {res['name']}")
                print(f"    - Question : {next(c['question'] for c in test_cases if c['name'] == res['name'])}")
                print(f"    - Expected : {res.get('expected')}")
                print(f"    - Actual   : {res.get('answer', 'NO ANSWER')[:200]}...")
                if res.get("error"):
                    print(f"    - Error    : {res['error']}")

if __name__ == "__main__":
    try:
        evaluate_chatbot()
    except Exception as e:
        print(f"\nCritical Error: {e}")
