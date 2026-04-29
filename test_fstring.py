query = 'x'
stock_context = 'x'
semantic_context = 'x'
json_example = '{"x": 1}'
search_prompt = f"""Anda adalah asisten rental mobil.
Tugas: Rekomendasikan mobil dari DATA STOK yang paling cocok dengan permintaan user.
Gunakan data BBM untuk mencari mobil irit, dan data KURSI untuk mobil keluarga.

DATA STOK:
{stock_context}

KONTEKS TAMBAHAN:
{semantic_context}

PERMINTAAN USER: "{query}"

INSTRUKSI:
1. Berikan list mobil bernomor (1, 2, 3).
2. Format: "[Nama Mobil] dengan harga Rp [Harga]/hari".
3. Berikan alasan sangat singkat kenapa mobil itu cocok.
4. JANGAN gunakan sapaan. JANGAN bertele-tele.
5. Jika tidak ada yang cocok, katakan stok kosong.

HANYA BALAS JSON:
{json_example}"""
print('OK')
