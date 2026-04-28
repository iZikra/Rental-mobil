import os
from langchain_chroma import Chroma
from langchain_huggingface import HuggingFaceEmbeddings

DB_DIR = "chroma_db"

if not os.path.exists(DB_DIR):
    print(f"❌ ERROR: Folder '{DB_DIR}' tidak ditemukan!")
else:
    embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")
    db = Chroma(persist_directory=DB_DIR, embedding_function=embeddings)
    
    data = db.get(limit=1)
    if data['metadatas']:
        print("\n✅ DATABASE DITEMUKAN")
        print(f"Metadata pertama: {data['metadatas'][0]}")
        
        if 'rental_id' in data['metadatas'][0]:
            print("🚀 STATUS: RAG MULTI-TENANT AKTIF (rental_id ditemukan)")
        else:
            print("⚠️ WARNING: rental_id TIDAK ditemukan. Filter antar rental akan gagal!")
    else:
        print("❓ Database ada tapi isinya kosong. Jalankan ingest_data.py dulu.")