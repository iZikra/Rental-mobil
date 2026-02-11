import os
import shutil
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_chroma import Chroma
from langchain_community.document_loaders import TextLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_core.documents import Document

# --- KONFIGURASI ---
DB_DIR = "chroma_db"
DOC_DIR = "dokumen" # Folder utama sesuai gambar Anda

# Gunakan model embedding yang ringan namun akurat
embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")

def ingest():
    all_final_docs = []
    
    # 1. Validasi Keberadaan Folder Utama
    if not os.path.exists(DOC_DIR):
        print(f"Error: Folder '{DOC_DIR}' tidak ditemukan!")
        return

    print(f"--- MEMULAI INGESTI MULTI-TENANT ---")

    # 2. Iterasi melalui Sub-folder (Fz, Berkah)
    # os.walk akan menyisir setiap folder di dalam 'dokumen'
    for root, dirs, files in os.walk(DOC_DIR):
        for file in files:
            if file.endswith(".txt"):
                file_path = os.path.join(root, file)
                
                # Mengambil nama folder sebagai rental_id (Fz atau Berkah)
                # Contoh: dokumen/Fz/syarat_sewa.txt -> folder_name = "Fz"
                folder_name = os.path.basename(root)
                
                try:
                    # Load dokumen
                    loader = TextLoader(file_path, encoding='utf-8')
                    loaded_docs = loader.load()
                    
                    # 3. Proses Chunking per file
                    splitter = RecursiveCharacterTextSplitter(chunk_size=700, chunk_overlap=100)
                    chunks = splitter.split_documents(loaded_docs)
                    
                    # 4. Sematkan Metadata Rental ID
                    for chunk in chunks:
                        # Kita konversi nama folder jadi ID sesuai DatabaseSeeder
                        # Fz -> rental_id: 1, Berkah -> rental_id: 2
                        rid = "1" if folder_name.lower() == "fz" else "2"
                        
                        chunk.metadata["rental_id"] = rid
                        chunk.metadata["source"] = file
                        chunk.metadata["kota"] = "Pekanbaru" # Sesuai lokasi rental Anda
                        all_final_docs.append(chunk)
                        
                    print(f"Berhasil memproses {file} dari folder [{folder_name}] -> ID: {rid}")
                    
                except Exception as e:
                    print(f"Gagal membaca file {file}: {str(e)}")

    # 5. Simpan ke ChromaDB
    if not all_final_docs:
        print("Gagal: Tidak ada dokumen .txt yang ditemukan untuk dimasukkan!")
        return

    # Bersihkan database lama agar tidak terjadi duplikasi data
    if os.path.exists(DB_DIR):
        shutil.rmtree(DB_DIR)
        print("Membersihkan database lama...")

    print(f"Menyimpan {len(all_final_docs)} potongan data ke ChromaDB...")
    
    vector_store = Chroma.from_documents(
        documents=all_final_docs, 
        embedding=embeddings, 
        persist_directory=DB_DIR
    )
    
    print(f"--- PROSES SELESAI: DATA SIAP DIGUNAKAN ---")

if __name__ == "__main__":
    ingest()