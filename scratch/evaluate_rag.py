import os
from langchain_chroma import Chroma
from langchain_huggingface import HuggingFaceEmbeddings
import requests

DB_DIR = "chroma_db"
embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")
vector_store = Chroma(persist_directory=DB_DIR, embedding_function=embeddings)

TEST_CASES = [
    {
        "q": "Berapa harga sewa mobil Agya?",
        "expected_doc_keyword": "Agya",
        "expected_answer_keyword": ["350"]
    },
    {
        "q": "Berapa denda kalau saya terlambat mengembalikan mobil?",
        "expected_doc_keyword": "keterlambatan pengembalian unit adalah",
        "expected_answer_keyword": ["10%"]
    },
    {
        "q": "Apakah sewa alphard bisa lepas kunci?",
        "expected_doc_keyword": "Alphard",
        "expected_answer_keyword": ["wajib", "sopir"]
    },
    {
        "q": "Syarat apa saja yang harus disiapkan untuk booking?",
        "expected_doc_keyword": "E-KTP",
        "expected_answer_keyword": ["ktp", "sim"]
    },
    {
        "q": "Di mana alamat FZ Rent?",
        "expected_doc_keyword": "Kantor pusat kami",
        "expected_answer_keyword": ["suka karya"]
    }
]

def run_evaluation():
    print("=== MEMULAI EVALUASI CHATBOT (RAG METRICS) ===")
    
    total_cases = len(TEST_CASES)
    relevance_points = 0
    accuracy_points = 0
    
    for idx, case in enumerate(TEST_CASES, 1):
        question = case["q"]
        doc_key = case["expected_doc_keyword"]
        ans_keys = case["expected_answer_keyword"]
        
        print(f"\\n[{idx}] Pertanyaan: {question}")
        
        # 1. EVALUASI RELEVANCE (Apakah Vector DB berhasil menarik teks yang benar?)
        is_relevant = False
        try:
            results = vector_store.similarity_search_with_relevance_scores(
                question, k=4, filter={"rental_id": "1"}
            )
            retrieved_texts = [doc.page_content.lower() for doc, score in results if score > 0.45]
            
            # Cek apakah keyword yang diharapkan ada dalam teks yang ditarik
            if any(doc_key.lower() in t for t in retrieved_texts):
                is_relevant = True
                relevance_points += 1
                print(" -> [RELEVANCE]: LULUS (Dokumen ditemukan)")
            else:
                 print(" -> [RELEVANCE]: GAGAL (Dokumen tidak akurat)")
        except Exception as e:
            print(f" -> [RELEVANCE]: ERROR {e}")
            
        # 2. EVALUASI ACCURACY (Apakah Jawaban LLM Benar?)
        is_accurate = False
        payload = {
            'question': question,
            'user_name': 'Penguji',
            'context': 'DATA RENTAL YANG TERDAFTAR:\\nFZ Rent\\n\\nDATA KOTA YANG TERSEDIA DI RENTAL INI:\\nPekanbaru\\n\\nDATA STOK MOBIL SAAT INI (REAL-TIME):\\n- UNIT: Toyota Agya | Cabang: Pekanbaru | Tipe: city car | Transmisi: matic | Kursi: 5 | BBM: bensin\\n- UNIT: Toyota Alphard | Cabang: Pekanbaru | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin\\n',
            'rental_id': '1',
            'history': []
        }
        
        try:
            res = requests.post('http://localhost:5000/chat', json=payload, timeout=20)
            answer = res.json().get('answer', '').lower()
            
            # Jika memuat SALAH SATU atau SEMUA keyword penting, dianggap akurat
            if any(k.lower() in answer for k in ans_keys):
                is_accurate = True
                accuracy_points += 1
                print(f" -> [ACCURACY] : LULUS")
            else:
                print(f" -> [ACCURACY] : GAGAL (Jawaban bot divergen)")
                # Print why it failed
                print(f"    Expected: {ans_keys}")
                print(f"    Got: {answer}")
        except Exception as e:
            print(f" -> [ACCURACY] : ERROR {e}")
            
    # CALCULATE PERCENTAGES
    relevance_pct = (relevance_points / total_cases) * 100
    accuracy_pct = (accuracy_points / total_cases) * 100
    
    print("\\n==================================")
    print("HASIL AKHIR EVALUASI:")
    print("==================================")
    print(f"Relevance Score (% dokumen relevan) : {relevance_pct:.0f}%")
    print(f"Accuracy Score  (% jawaban benar)   : {accuracy_pct:.0f}%")
    print("==================================")

if __name__ == "__main__":
    run_evaluation()
