import os
from flask import Flask, request, jsonify
from dotenv import load_dotenv

from langchain_huggingface import HuggingFaceEmbeddings
from langchain_chroma import Chroma
from langchain_groq import ChatGroq
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.output_parsers import StrOutputParser

load_dotenv()
app = Flask(__name__)

# --- KONFIGURASI ---
DB_DIR = "chroma_db"
embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")

# Load Database
if os.path.exists(DB_DIR):
    vector_store = Chroma(persist_directory=DB_DIR, embedding_function=embeddings)
else:
    vector_store = None
    print("Peringatan: Folder chroma_db tidak ditemukan!")

# --- SETUP LLM ---
try:
    llm = ChatGroq(model="llama-3.3-70b-versatile", api_key=os.getenv("GROQ_API_KEY"))
except Exception:
    llm = ChatGoogleGenerativeAI(model="gemini-1.5-flash", api_key=os.getenv("GOOGLE_API_KEY"))

# --- PROMPT GROUNDING ---
prompt = ChatPromptTemplate.from_template(
    """
    Anda adalah Chatbot Cerdas Platform Multi-Rental Mobil.
    User: {user_name}
    Rental Saat Ini: {current_rental_name}

    [DOKUMEN]: {doc_context}
    [STOK]: {laravel_context}

    ATURAN BICARA:
    1. JANGAN mengulangi sapaan "Selamat datang" jika percakapan sudah berlangsung.
    2. Langsung jawab intinya dengan gaya bahasa yang tegas namun membantu.
    3. Jika user bertanya tentang liburan, langsung berikan saran mobil tanpa basa-basi pembuka yang panjang.
    4. Jika terdapat mobil dengan merk yang sama namun harga berbeda, jelaskan perbedaannya berdasarkan tahun, varian, atau kebijakan vendor.
    5. PENTING: Jika user mencari tipe mobil tertentu (misal: Xenia), Anda WAJIB memeriksa seluruh daftar di [STOK] dari SEMUA rental.Jangan hanya menyebutkan dari satu rental jika rental lain (misal: Berkah Rent) juga memiliki unit yang sama.
    6. Urutkan jawaban Anda berdasarkan tahun atau harga agar user bisa melihat perbandingannya dengan jelas.
    
    Pertanyaan: {question}
    """
)

chain = prompt | llm | StrOutputParser()

def ekstrak_konteks_kota(teks):
    teks = teks.lower()
    if "pekanbaru" in teks: return "Pekanbaru"
    if "jakarta" in teks: return "Jakarta"
    return "Umum"

@app.route('/chat', methods=['POST'])
def chat():
    data = request.json
    user_input = data.get('question') or data.get('message') or ""
    user_name = data.get('user_name', 'sobat rental')
    rental_id = str(data.get('rental_id', '')) 
    laravel_context = data.get('context', 'Informasi armada tidak tersedia.')
    
    # 1. Identifikasi Kota
    kota_terdeteksi = ekstrak_konteks_kota(user_input)
    
    # Jika user menanyakan mobil spesifik (misal: Brio)
    words = user_input.split()
    # Mencari apakah nama mobil yang ditanyakan ada dalam konteks stok dari Laravel
    unit_exists_in_context = any(word in laravel_context.lower() for word in words if len(word) > 3)

    # Jika user tanya mobil yang MEMANG TIDAK ADA di sistem
    if not unit_exists_in_context and any(k in user_input for k in ["brio", "ayla", "jazz"]):
        return jsonify({
            "status": "success",
            "answer": f"Mohon maaf {user_name}, saat ini unit tersebut (Brio/Ayla/Jazz) belum tersedia di platform kami, baik di Pekanbaru maupun Jakarta. Unit yang tersedia saat ini adalah Xenia, Agya, Terios, Alphard, dan Fortuner."
        })

    # 2. Logika Validasi Lokasi (Entity Validation)
    if any(keyword in user_input.lower() for keyword in ["cari", "sewa", "mobil", "stok"]):
        if kota_terdeteksi == "Umum":
            return jsonify({
                "status": "success",
                "answer": f"Halo {user_name}! Untuk memberikan informasi ketersediaan unit yang akurat, boleh tahu Kakak berencana sewa di kota mana? Saat ini kami tersedia di Pekanbaru dan Jakarta."
            })

    # 3. Penentuan Nama Rental secara Dinamis
    current_rental_name = "Semua Rental"
    if rental_id == "1": current_rental_name = "FZ Rent Car"
    elif rental_id == "2": current_rental_name = "Berkah Rent"

    try:
        doc_context = ""
        if vector_store:
            # 4. Retrieval dengan Metadata Filter (Multi-tenancy)
            if rental_id and rental_id != "":
                search_filter = {"rental_id": rental_id}
                docs = vector_store.similarity_search(user_input, k=5, filter=search_filter)
            else:
                docs = vector_store.similarity_search(user_input, k=6)
            
            doc_context = "\n".join([f"[{d.metadata.get('source', 'SOP')}] {d.page_content}" for d in docs])

        # 5. Jalankan AI Chain (Pastikan SEMUA variabel di template terisi)
        response = chain.invoke({
            "question": user_input,
            "user_name": user_name,
            "current_rental_name": current_rental_name,
            "doc_context": doc_context if doc_context.strip() else "Gunakan pengetahuan umum rental untuk menjawab sapaan saja.",
            "laravel_context": laravel_context
        })

        return jsonify({"status": "success", "answer": response})

    except Exception as e:
        print(f"Error: {str(e)}")
        return jsonify({"status": "error", "message": "Terjadi kesalahan internal pada sistem AI."})

if __name__ == '__main__':
    app.run(port=5000, debug=True)