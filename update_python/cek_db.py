import chromadb
from chromadb.config import Settings

# 1. Hubungkan ke direktori penyimpanan
client = chromadb.PersistentClient(path="chroma_db")

# 2. Cek koleksi yang ada (biasanya LangChain otomatis menamai 'langchain')
collections = client.list_collections()
print(f"Koleksi ditemukan: {collections}")

# 3. Ambil data dari koleksi pertama
if collections:
    collection = client.get_collection(name=collections[0].name)
    results = collection.get() # Mengambil semua data
    
    print("\n--- ISI DATABASE CHROMA ---")
    for i in range(len(results['ids'])):
        print(f"ID: {results['ids'][i]}")
        print(f"Metadata: {results['metadatas'][i]}")
        print(f"Konten: {results['documents'][i][:100]}...") # Tampilkan 100 karakter pertama
        print("-" * 30)
else:
    print("Database kosong!")