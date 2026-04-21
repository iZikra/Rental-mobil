import os

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
    print("Connecting to MySQL for branch sync...")
    try:
        db = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="rental_mobil" 
        )
        cursor = db.cursor(dictionary=True) 
        
        cursor.execute("SELECT rental_id, nama_cabang, alamat_lengkap, kota FROM branches")
        rentals = cursor.fetchall()

        for r in rentals:
            content = f"Cabang resmi kami tersedia di kota {r['kota']} dengan alamat: {r['alamat_lengkap']} (Nama Cabang: {r['nama_cabang']})."
            rid = str(r['rental_id'])
            
            doc = Document(
                page_content=content, 
                metadata={"rental_id": rid, "source": "mysql_database"}
            )
            all_final_docs.append(doc)
        
        print(f"Successfully synced {len(rentals)} branches from MySQL.")
        
        # --- BAGIAN 1.5: AMBIL DATA MOBIL DARI MYSQL ---
        print("Connecting to MySQL for car specs sync...")
        cursor.execute("""
            SELECT m.merk, m.model, m.tipe_mobil, m.transmisi, m.jumlah_kursi, m.bahan_bakar, b.kota, b.rental_id 
            from mobils m 
            JOIN branches b ON m.branch_id = b.id
        """)
        mobils = cursor.fetchall()
        for m in mobils:
            content = f"Unit Tersedia: {m['merk']} {m['model']} dengan spesifikasi tipe {m['tipe_mobil']}, transmisi {m['transmisi']}, memuat {m['jumlah_kursi']} kursi, dan menggunakan BBM {m['bahan_bakar']}. Tersedia di kota {m['kota']}."
            rid = str(m['rental_id'])
            doc = Document(
                page_content=content,
                metadata={"rental_id": rid, "source": "mysql_database_mobil"}
            )
            all_final_docs.append(doc)
            
        print(f"Successfully synced {len(mobils)} car specs from MySQL.")
        db.close() 
        
    except Exception as e:
        print(f"Warning: Failed to fetch MySQL data, continuing with local files. Error: {e}")

    # --- (CHUNK)BAGIAN 2: AMBIL DATA DARI FILE .TXT (SOP/DENDA) ---
    if not os.path.exists(DOC_DIR):
        print(f"Error: Folder '{DOC_DIR}' not found!")
        return

    # Pemetaan rental_id berdasarkan folder (sesuai tabel 'rentals' di database)
    # id=1: Fz Rent Car, id=2: Putra Wijaya Rent Car, id=3: AA RENT CAR, id=4: Evan Rental, id=5: PT Trans Nusantara Gemilang
    rental_mapping = {
        "fz": "1", 
        "putra_wijaya": "2", 
        "aa_rent": "3",
        "evan_rental": "4",
        "tng": "5"
    }

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
                    print(f"OK: {file} [{folder_name.upper()}] -> {len(chunks)} chunks.")
                except Exception as e:
                    print(f"Error loading file {file}: {e}")

    # --- BAGIAN 3: PENYIMPANAN ---
    if not all_final_docs:
        print("Error: No data to put into ChromaDB!")
        return

    try:
        if os.path.exists(DB_DIR):
            import chromadb
            client = chromadb.PersistentClient(path=DB_DIR)
            client.delete_collection("langchain")
            print("Old ChromaDB collection deleted for new sync.")
    except Exception as e:
        print(f"Bypass delete error: {e}")
    
    Chroma.from_documents(documents=all_final_docs, embedding=embeddings, persist_directory=DB_DIR)
    print(f"INGESTION COMPLETE. {len(all_final_docs)} data items ready!")

if __name__ == "__main__":
    ingest()