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
# Menambahkan instruksi eksplisit agar AI memeriksa DATA ARMADA dari Laravel
prompt = ChatPromptTemplate.from_template(
    """
    Anda adalah Chatbot Cerdas Platform Multi-Rental.
    User: {user_name}

    [DOKUMEN]: {doc_context}
    [STOK]: {laravel_context}

    ATURAN BICARA:
    1. JANGAN mengulangi sapaan "Selamat datang" jika percakapan sudah berlangsung.
    2. Langsung jawab intinya dengan gaya bahasa yang tegas namun membantu.
    3. Jika user bertanya tentang liburan, langsung berikan saran mobil tanpa basa-basi pembuka yang panjang.
    
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
    
    # PENTING: Ambil rental_id dari Laravel agar filter RAG aktif
    rental_id = str(data.get('rental_id', '')) 
    
    # Ambil data stok mobil (JSON string dari Laravel)
    laravel_context = data.get('context', 'Informasi armada tidak tersedia.')
    
    # Metadata Rental Name untuk Prompt
    current_rental_name = "Semua Rental"
    if rental_id == "1": current_rental_name = "FZ Rent Car"
    if rental_id == "2": current_rental_name = "Berkah Rent"

    try:
        doc_context = ""
        if vector_store:
            # LOGIKA FILTER: Jika ada rental_id, cari dokumen spesifik rental tersebut.
            # Jika tidak ada (halaman utama), cari secara global (perbandingan).
            if rental_id:
                search_filter = {"rental_id": rental_id}
                docs = vector_store.similarity_search(user_input, k=5, filter=search_filter)
            else:
                docs = vector_store.similarity_search(user_input, k=6)
            
            doc_context = "\n".join([f"[{d.metadata.get('source', 'SOP')}] {d.page_content}" for d in docs])
            
            print(f"\n--- DEBUG RAG [Rental ID: {rental_id}] ---")
            print(f"Context Armada: {laravel_context[:100]}...")
            print("----------------------------------\n")

        response = chain.invoke({
            "question": user_input,
            "user_name": user_name,
            "rental_id": rental_id,
            "current_rental_name": current_rental_name,
            "doc_context": doc_context if doc_context.strip() else "Gunakan pengetahuan umum rental untuk menjawab sapaan saja.",
            "laravel_context": laravel_context
        })

        return jsonify({"status": "success", "answer": response})

    except Exception as e:
        return jsonify({"status": "error", "message": str(e)})

if __name__ == '__main__':
    app.run(port=5000, debug=True)