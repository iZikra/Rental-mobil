import os
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_chroma import Chroma

# --- KONFIGURASI ---
DB_DIR = "chroma_db"

# Gunakan model embedding yang sama persis dengan ingest/engine
embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")

def lihat_isi_database():
    if not os.path.exists(DB_DIR):
        print(f"Error: Folder '{DB_DIR}' tidak ditemukan. Silakan jalankan ingest_data.py dulu.")
        return

    # Load database
    vector_store = Chroma(
        persist_directory=DB_DIR, 
        embedding_function=embeddings
    )

    # Ambil semua data (IDs, Metadatas, dan Documents)
    # limit=10 agar terminal tidak penuh jika data sangat banyak
    data = vector_store.get(include=['documents', 'metadatas'])

    total_data = len(data['ids'])
    print(f"\n" + "="*50)
    print(f"TOTAL POTONGAN DOKUMEN (CHUNKS): {total_data}")
    print("="*50 + "\n")

    if total_data == 0:
        print("Database kosong!")
        return

    # Tampilkan 10 data pertama untuk pengecekan
    for i in range(min(total_data, 10)):
        print(f"--- DATA KE-{i+1} ---")
        print(f"ID       : {data['ids'][i]}")
        print(f"METADATA : {data['metadatas'][i]}") # Cek rental_id di sini
        print(f"ISI TEKS : {data['documents'][i][:200]}...") # Tampilkan 200 karakter awal
        print("-" * 30 + "\n")

if __name__ == "__main__":
    lihat_isi_database()