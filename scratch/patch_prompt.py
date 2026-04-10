f = open('rag_engine.py', 'r', encoding='utf-8')
content = f.read()
f.close()

old = '- Jangan menebak kota jika user belum menyebutkannya dengan jelas.\n- Jangan memberikan harga atau unit yang tidak ada di DATA STOK.'
new = ('- Jangan menebak kota jika user belum menyebutkannya dengan jelas.\n'
       '- PENGECUALIAN HARGA: Jika user BERTANYA TENTANG HARGA (kata "berapa", "harga", "tarif", "biaya") dan ada DOKUMEN RAG yang berisi daftar harga, '
       'WAJIB jawab langsung menggunakan harga dari DOKUMEN RAG. Dalam kasus ini JANGAN bertanya kota terlebih dahulu.\n'
       '- Jangan memberikan harga unit yang tidak ada di DATA STOK maupun DOKUMEN RAG.')

old2 = '2. Jika user bertanya hal umum/aturan/CARA BOOKING: \n   - Jawab menggunakan DOKUMEN RAG. '
new2 = ('2. Jika user bertanya HARGA atau TARIF sewa: \n'
        '   - WAJIB gunakan DOKUMEN RAG untuk menjawab. Sebutkan harga langsung tanpa bertanya kota.\n'
        '   - Contoh: "Harga Alphard Rp 5.000.000/hari"\n'
        '3. Jika user bertanya hal umum/aturan/CARA BOOKING: \n   - Jawab menggunakan DOKUMEN RAG. ')
# renumber the rest
old3 = '3. Jika user menyapa: Sapa balik dengan ramah dan tawarkan bantuan.\n4. Jika user ingin cari mobil: ANDA WAJIB memastikan KOTA dipilih dulu, lalu tampilkan SEMUA unit yang cocok dari DATA STOK.\n5. Jika user sudah pilih unit: Berikan panduan booking website.'
new3 = ('4. Jika user menyapa: Sapa balik dengan ramah dan tawarkan bantuan.\n'
        '5. Jika user ingin CARI MOBIL (bukan tanya harga): ANDA WAJIB memastikan KOTA dipilih dulu, lalu tampilkan SEMUA unit yang cocok dari DATA STOK.\n'
        '6. Jika user sudah pilih unit: Berikan panduan booking website.')

print('match old:', old in content)
print('match old2:', old2 in content)
print('match old3:', old3 in content)
content = content.replace(old, new).replace(old2, new2).replace(old3, new3)
f = open('rag_engine.py', 'w', encoding='utf-8')
f.write(content)
f.close()
print('done')
