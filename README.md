# Sistem Informasi Rental Mobil dengan RAG Chatbot

Proyek ini adalah sistem informasi rental mobil multi-tenant yang dilengkapi dengan asisten cerdas (chatbot) berbasis arsitektur RAG (Retrieval-Augmented Generation).

## Arsitektur RAG (Prioritas 1)
Sistem ini menggunakan *Hybrid Retrieval* yang menggabungkan:
- **Vector Search (ChromaDB)**: Menggunakan model `sentence-transformers/all-MiniLM-L6-v2` untuk pencarian konteks semantik dari dokumen syarat, ketentuan, dan kebijakan rental.
- **Structured Query (MySQL)**: Untuk mengambil data *real-time* seperti ketersediaan stok mobil dan harga dari database utama Laravel.
- **Metadata Filtering**: RAG engine secara otomatis memfilter dokumen berdasarkan `rental_id` dan `kota` untuk menjaga privasi data antar cabang/rental.

## Dokumentasi Teknis (Prioritas 5)

Berikut adalah panduan untuk mengatur dan menjalankan komponen Flask RAG Engine.

### 1. Daftar Dependency Proyek
Pastikan Python sudah terinstal di sistem Anda (versi 3.8+ direkomendasikan). Install library yang dibutuhkan menggunakan `pip`:
```bash
pip install flask flask-cors groq python-dotenv langchain-huggingface langchain-chroma sentence-transformers mysql-connector-python
```
*(Atau gunakan `pip install -r requirements.txt` jika file tersebut sudah tersedia)*

### 2. Cara Melakukan Ingest Data (Memasukkan data ke Vector DB)
Sebelum RAG engine bisa digunakan untuk pencarian semantik, Anda harus memasukkan data (ingest) dari file `.txt` dan database MySQL ke dalam ChromaDB.
```bash
cd python_service
python ingest_data.py
```
Perintah ini akan membaca folder `dokumen/` dan menyimpannya dalam bentuk vektor ke dalam folder `chroma_db/`.

### 3. Cara Menjalankan Flask RAG Engine
Setelah data di-ingest, Anda bisa menyalakan server RAG.
```bash
cd python_service
python rag_engine.py
```
Server akan berjalan di `http://127.0.0.1:5000` dan siap menerima *request* dari aplikasi Laravel (melalui `ChatbotController.php`).

## Catatan Database Chat Logs (Prioritas 2)
Riwayat percakapan chatbot disimpan secara permanen di dalam tabel `chat_logs` MySQL. Pastikan Anda telah menjalankan migrasi database:
```bash
php artisan migrate
```
Struktur tabel mencakup `session_id`, `user_message`, `bot_response`, dan `rental_id` untuk kebutuhan evaluasi (Prioritas 3).
