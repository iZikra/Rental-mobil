import os
from flask import Flask, request, jsonify
from dotenv import load_dotenv

# --- LIBRARY UTAMA ---
from langchain_community.document_loaders import TextLoader, PyPDFLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_community.embeddings import HuggingFaceEmbeddings
from langchain_community.vectorstores import FAISS
from langchain_google_genai import ChatGoogleGenerativeAI
from langchain_groq import ChatGroq
from langchain_core.prompts import ChatPromptTemplate
from langchain_core.output_parsers import StrOutputParser

load_dotenv()
app = Flask(__name__)

# ==========================================
# 1. HELPER: MEMBACA FILE SECARA MANUAL
# ==========================================
def baca_dokumen_langsung(filename):
    try:
        path = os.path.join('dokumen', filename)
        if os.path.exists(path):
            with open(path, 'r', encoding='utf-8') as f:
                return f.read()
    except Exception as e:
        print(f"⚠️ Gagal membaca {filename}: {e}")
    return None

# ==========================================
# 2. INISIALISASI VECTOR DB
# ==========================================
vector_store = None
try:
    if not os.path.exists('dokumen'):
        os.makedirs('dokumen')

    documents = []
    if os.path.exists('dokumen'):
        for file in os.listdir('dokumen'):
            file_path = os.path.join('dokumen', file)
            if file.endswith('.txt'):
                documents.extend(TextLoader(file_path, encoding='utf-8').load())
            elif file.endswith('.pdf'):
                documents.extend(PyPDFLoader(file_path).load())

    if documents:
        splitter = RecursiveCharacterTextSplitter(chunk_size=500, chunk_overlap=50)
        chunks = splitter.split_documents(documents)
        embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")
        vector_store = FAISS.from_documents(chunks, embeddings)
        print(f"✅ Berhasil memuat {len(documents)} dokumen ke Vector DB.")
except Exception as e:
    print(f"⚠️ Warning: Gagal inisialisasi Vector DB: {e}")

# ==========================================
# 3. SETUP LLM & PROMPT
# ==========================================
try:
    llm = ChatGroq(model="llama-3.3-70b-versatile", api_key=os.getenv("GROQ_API_KEY"))
except:
    llm = ChatGoogleGenerativeAI(model="gemini-2.5-flash", api_key=os.getenv("GOOGLE_API_KEY"))

prompt = ChatPromptTemplate.from_template(
    """
    Anda adalah sistem AI cerdas untuk 'FZ Rent Car'.
    
    [DATA STOK DARI DATABASE]: {laravel_context}
    [DATA SOP]: {doc_context}
    
    TUGAS UTAMA:
    1. Jika user mencari mobil yang TIDAK ADA di [DATA STOK], tawarkan unit yang statusnya 'Ready' sebagai alternatif.
    2. Jika user menunjukkan minat kuat atau ingin booking (misal: "Saya minat", "Mau booking ini"), berikan rincian singkat dan AKHIRI dengan tag: #DIRECT_BOOKING
    3. Jika user bertanya hal umum, jawab berdasarkan [DATA SOP].
    4. Selalu akhiri dengan tag: #SHOW_CARS jika menawarkan unit mobil.

    Pertanyaan User: {question}
    """
)
chain = prompt | llm | StrOutputParser()

# ==========================================
# 4. ENDPOINT CHAT
# ==========================================
@app.route('/chat', methods=['POST'])
def chat():
    data = request.json
    user_input = data.get('question') or data.get('message') or ""
    laravel_context = data.get('context', 'Tidak ada data stok saat ini.')

    # A. Prioritas 1: Intercept Syarat Sewa (Cepat & Offline)
    if "syarat" in user_input.lower() and "sewa" in user_input.lower():
        isi_syarat = baca_dokumen_langsung('syarat_sewa.txt')
        if isi_syarat:
            return jsonify({
                "status": "success", 
                "answer": f"Berikut syarat sewa di FZ Rent Car:\n\n{isi_syarat}"
            })

    # B. Prioritas 2: Proses RAG & LLM
    try:
        doc_context = ""
        if vector_store:
            docs = vector_store.similarity_search(user_input, k=3)
            doc_context = "\n".join([d.page_content for d in docs])

        response = chain.invoke({
            "question": user_input,
            "laravel_context": laravel_context,
            "doc_context": doc_context or "Gunakan pengetahuan umum sebagai admin ramah."
        })

        return jsonify({"status": "success", "answer": response})

    except Exception as e:
        print(f"❌ Error API: {e}")
        return jsonify({
            "status": "success", 
            "answer": "Mohon maaf Kak, sistem sedang sinkronisasi. Tapi kami ready unit Avanza dan Innova hari ini. Mau cek harganya? #SHOW_CARS"
        })

if __name__ == '__main__':
    app.run(port=5000, debug=True)