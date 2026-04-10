# Laporan Evaluasi Chatbot RAG (Rental Mobil)

Setelah kita pisahkan fitur pencarian harga murni menggunakan RAG (ChromaDB) dan melakukan *stress-test* menggunakan 5 pertanyaan terarah, berikut adalah metrik evaluasi yang terbaca oleh sistem:

## 1. Hasil Pengujian Independen (RAG Testing)

Dari 5 sampel pertanyaan yang secara khusus menargetkan penarikan (*retrieval*) informasi dari dokumen RAG (ChromaDB):

| Kasus Uji | Pertanyaan | Kata Kunci Target (Dokumen) | Hasil Relevance | Hasil Accuracy (%) |
| :--- | :--- | :--- | :--- | :--- |
| **Kasus 1** | Berapa harga sewa mobil Agya? | "Agya" | ❌ Dokumen Meleset | ❌ Jawaban Gagal (Halusinasi / Tidak tahu) |
| **Kasus 2** | Berapa denda kalau saya terlambat mengembalikan mobil? | "10%" | ❌ Dokumen Meleset | ❌ Jawaban Gagal (Sistem menolak) |
| **Kasus 3** | Apakah sewa alphard bisa lepas kunci?| "Wajib dengan sopir" | ❌ Dokumen Meleset | ❌ Jawaban Gagal (Sistem menawarkan Opsi Web) |
| **Kasus 4** | Syarat apa saja yang harus disiapkan untuk booking? | "KTP", "SIM" | ❌ Dokumen Meleset | ✅ Lulus (Llama 3.1 menjawab secara *Zero-Shot* berdasarkan memori aslinya) |
| **Kasus 5** | Di mana alamat FZ Rent? | "Suka Karya" | ❌ Dokumen Meleset | ❌ Jawaban Gagal |

---

## 2. Metrik Akhir (Raw Score)

> [!WARNING]
> Hasil berikut adalah pencerminan keadaan *Embedding Model* murni (tanpa rekayasa keyword).

*   **Relevance Score**: **0%** 
    (Dari 5 pertanyaan, dokumen vektor RAG yang menduduki *Top-3 Similarity* selalu salah sasaran).
*   **Accuracy Score**: **20%**
    (Sistem Bot hanya benar di 1 pertanyaan karena kecerdasan *default* Llama berhasil menebak syarat rental (KTP & SIM) secara umum, bukan ditarik dari dokumen).

---

## 3. Analisis Ilmiah Mengapa Hal Ini Terjadi (Untuk Bab Skripsi)

Mengapa RAG Gagal Menemukan `"Harga Alphard"` dan malah menarik dokumen `"Tanggung Jawab Kerusakan"`?

Ini adalah fenomena umum di dunia AI dan sangat bagus untuk dimasukkan ke Bab Pembahasan/Saran di Skripsi Anda:

1.  **Demitologisasi Dense Retrieval (Kelemahan Model Embedding L6-v2):** 
    Model *all-MiniLM-L6-v2* menganalisis kedekatan kata secara *semantic / makna*. Ketika user bertanya *"Berapa harga sewa mobil Alphard?"*, model melihat frekuensi makna terkuat jatuh pada kata *"Sewa"*, *"Harga/Biaya"*, dan *"Mobil"*. 
    Dokumen "Tanggung Jawab Kerusakan" memiliki kepadatan kata tersebut (seperti: `"karena rusak sewaktu sewa, wajib membayar biaya sewa mobil..."`), sehingga secara matematis kalimat ini dianggap "sangat dekat/similar" dengan pertanyaan, dan mengabaikan nama *"Alphard"* yang jarang muncul.

2.  **Solusi Hybrid Search Lanjutan (BM25):** 
    Untuk penelitian selanjutnya, model vektor murni tidak cukup untuk mencari harga atau spesifikasi spesifik. Anda harus menggabungkan pencarian kemiripan kata biasa (BM25 Algoritma / Keyword Matching) dengan *Vector Embedding*. 

> [!TIP]
> **Kesimpulan Penggabungan Skripsi:**
> Pada Bab pengujian yang membahas Sinkronisasi Database vs RAG, Anda bisa menarik kesimpulan bahwa:
> *   Kemampuan RAG dengan *Embedding L6-V2* terbukti lemah pada pencarian angka, harga, dan nama unit secara presisi. (Terbukti *Relevance Score = 0%*).
> *   Oleh karena itu, tindakan mempertahankan *Context Injection langsung dari Database Laravel (Seperti yang controller kita lakukan)* sangat terjustifikasi karena memberikan akurasi absolut pada ketersediaan unit dan pemisahan kriteria, dibandingkan diserahkan 100% ke model vektor semantik.
