import os
import shutil
import mysql.connector
from langchain_huggingface import HuggingFaceEmbeddings
from langchain_chroma import Chroma
from langchain_community.document_loaders import TextLoader
from langchain_text_splitters import RecursiveCharacterTextSplitter
from langchain_core.documents import Document

# --- KONFIGURASI ---
DB_DIR = "chroma_db"
DOC_DIR = "dokumen"
EMBEDDING_MODEL = "sentence-transformers/all-MiniLM-L6-v2"

embeddings = HuggingFaceEmbeddings(model_name=EMBEDDING_MODEL)

def ingest():
    all_final_docs = []
    
    # --- BAGIAN 1: AMBIL DATA DARI MYSQL (CABANG) ---
    print(f"🔗 Menghubungkan ke MySQL untuk sinkronisasi cabang...")
    try:
        db = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="rental_mobil" 
        )
        cursor = db.cursor(dictionary=True) # Variabel cursor harus ada di sini
        
        # Sesuai gambar database Anda: Tabel 'branches'
        cursor.execute("SELECT rental_id, nama_cabang, alamat_lengkap, kota FROM branches")
        rentals = cursor.fetchall()

        for r in rentals:
            # Menggabungkan informasi agar sangat jelas bagi AI
            content = f"Cabang resmi kami tersedia di kota {r['kota']} dengan alamat: {r['alamat_lengkap']} (Nama Cabang: {r['nama_cabang']})."
            rid = str(r['rental_id'])
            
            doc = Document(
                page_content=content, 
                metadata={"rental_id": rid, "source": "mysql_database"}
            )
            all_final_docs.append(doc)
        
        print(f"✅ Berhasil sinkronisasi {len(rentals)} cabang dari MySQL.")
        db.close() # Tutup koneksi
        
    except Exception as e:
        print(f"⚠️ Gagal tarik data MySQL, lanjut dengan file lokal saja. Error: {e}")

    # --- (CHUNK)BAGIAN 2: AMBIL DATA DARI FILE .TXT (SOP/DENDA) ---
    if not os.path.exists(DOC_DIR):
        print(f"❌ Error: Folder '{DOC_DIR}' tidak ditemukan!")
        return

    # Pemetaan rental_id berdasarkan folder
    rental_mapping = {"fz": "1", "berkah": "2"}

    for root, dirs, files in os.walk(DOC_DIR):
        folder_name = os.path.basename(root).lower()
        if folder_name not in rental_mapping: 
            continue
        rid = rental_mapping[folder_name]

        for file in files:
            if file.endswith(".txt"):
                file_path = os.path.join(root, file)
                try:
                    loader = TextLoader(file_path, encoding='utf-8')
                    loaded_docs = loader.load()
                    splitter = RecursiveCharacterTextSplitter(chunk_size=500, chunk_overlap=50)
                    chunks = splitter.split_documents(loaded_docs)
                    
                    for chunk in chunks:
                        chunk.metadata["rental_id"] = rid
                        chunk.metadata["source"] = file
                        all_final_docs.append(chunk)
                    print(f"✅ {file} [{folder_name.upper()}] -> {len(chunks)} chunks.")
                except Exception as e:
                    print(f"❌ Gagal memuat file {file}: {e}")

    # --- BAGIAN 3: PENYIMPANAN ---
    if not all_final_docs:
        print("❌ Tidak ada data untuk dimasukkan ke ChromaDB!")
        return

    # Hapus DB lama secara otomatis setiap kali ingest (Fresh Start)
    if os.path.exists(DB_DIR):
        shutil.rmtree(DB_DIR)
        print(f"🗑️ ChromaDB lama dihapus untuk sinkronisasi baru.")
    
    Chroma.from_documents(documents=all_final_docs, embedding=embeddings, persist_directory=DB_DIR)
    print(f"✨ INGESTI SELESAI. {len(all_final_docs)} data siap digunakan!")

if __name__ == "__main__":
    ingest()