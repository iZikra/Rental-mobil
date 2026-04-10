import os
from langchain_chroma import Chroma
from langchain_huggingface import HuggingFaceEmbeddings

DB_DIR = "chroma_db"
embeddings = HuggingFaceEmbeddings(model_name="sentence-transformers/all-MiniLM-L6-v2")
vector_store = Chroma(persist_directory=DB_DIR, embedding_function=embeddings)

question = "Berapa harga sewa mobil Agya?"
results = vector_store.similarity_search_with_relevance_scores(question, k=4)
for i, (doc, score) in enumerate(results):
    print(f"[{i}] SCORE: {score} | METADATA: {doc.metadata}")
    print(f"CONTENT: {doc.page_content}\\n---")
