import os
import shutil
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_chroma import Chroma
from langchain_community.document_loaders import TextLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter

# --- KONFIGURASI ---
DB_DIR = "chroma_db"
DOC_DIR = "dokumen"
EMBEDDING_MODEL = "sentence-transformers/all-MiniLM-L6-v2"

embeddings = HuggingFaceEmbeddings(model_name=EMBEDDING_MODEL)

def ingest():
    all_final_docs = []
    
    if not os.path.exists(DOC_DIR):
        print(f"❌ Error: Folder '{DOC_DIR}' tidak ditemukan!")
        return

    print(f"🚀 MEMULAI INGESTI DATA RAG...")

    # Mapping Folder ke ID sesuai database Laravel Anda
    rental_mapping = {
        "fz": "1",
        "berkah": "2"
    }

    for root, dirs, files in os.walk(DOC_DIR):
        folder_name = os.path.basename(root).lower()
        
        # Hanya proses folder yang terdaftar di mapping
        if folder_name not in rental_mapping:
            if files: print(f"⚠️ Melewati folder '{folder_name}' karena tidak terdaftar di mapping.")
            continue

        rid = rental_mapping[folder_name]

        for file in files:
            if file.endswith(".txt"):
                file_path = os.path.join(root, file)
                
                try:
                    loader = TextLoader(file_path, encoding='utf-8')
                    loaded_docs = loader.load()
                    
                    # Chunking lebih rapat agar AI lebih presisi mencari jawaban
                    splitter = RecursiveCharacterTextSplitter(chunk_size=500, chunk_overlap=50)
                    chunks = splitter.split_documents(loaded_docs)
                    
                    for chunk in chunks:
                        chunk.metadata["rental_id"] = rid
                        chunk.metadata["source"] = file
                        # Menghapus metadata default yang tidak perlu untuk menghemat ruang
                        all_final_docs.append(chunk)
                        
                    print(f"✅ {file} [{folder_name.upper()}] -> {len(chunks)} chunks.")
                    
                except Exception as e:
                    print(f"❌ Gagal membaca {file}: {str(e)}")

    if not all_final_docs:
        print("🛑 Gagal: Tidak ada data valid untuk dimasukkan!")
        return

    # Reset Database
    if os.path.exists(DB_DIR):
        shutil.rmtree(DB_DIR)
        print("🧹 Database lama dibersihkan.")

    print(f"📦 Menyimpan {len(all_final_docs)} chunks ke {DB_DIR}...")
    
    Chroma.from_documents(
        documents=all_final_docs, 
        embedding=embeddings, 
        persist_directory=DB_DIR
    )
    
    print(f"✨ INGESTI SELESAI. Chatbot siap digunakan!")

if __name__ == "__main__":
    ingest()