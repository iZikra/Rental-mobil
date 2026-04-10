from langchain_huggingface import HuggingFaceEmbeddings
from langchain_chroma import Chroma
from collections import Counter

embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")
db = Chroma(persist_directory="chroma_db", embedding_function=embeddings)
data = db.get(include=["documents", "metadatas"])
total = len(data["ids"])
print(f"Total chunks: {total}")

c = Counter(m.get("rental_id") for m in data["metadatas"])
print("Per rental_id:", dict(c))

for rid in ["1", "2", "3"]:
    docs = [
        (data["documents"][i], data["metadatas"][i])
        for i in range(total)
        if data["metadatas"][i].get("rental_id") == rid and "harga" in data["documents"][i].lower()
    ]
    print(f"\n--- rental_id={rid} harga docs ({len(docs)}) ---")
    for d, m in docs[:3]:
        src = m.get("source", "")
        print(f"  src: {src}")
        print(f"  txt: {d[:200]}")
        print()
