from dotenv import load_dotenv
import os, json
from groq import Groq

load_dotenv()
client = Groq(api_key=os.getenv('GROQ_API_KEY'))

system_prompt = '''Kamu adalah asisten platform rental mobil multi-kota.

IDENTITAS & BATASAN:
- Sistem melayani banyak kota.
- HANYA jawab hal berkaitan dengan rental mobil.

ALUR PERCAKAPAN:
- Jika user meminta daftar mobil di kota tertentu, TAMPILKAN DAFTAR MOBIL HANYA untuk kota tersebut.

GAYA BAHASA & TONE:
- Terapkan gaya bahasa: Singkat: Merespon dengan jumlah kata seminimal mungkin.
- DILARANG MERANGKAI KALIMAT PENGANTAR. LANGSUNG ke daftar.

FORMAT MENAMPILKAN DAFTAR MOBIL:
- WAJIB gunakan baris baru (newline) \n pada output.
- Format baku: 1. [Nama Mobil] - Rp [Harga]/hari

CONTOH RESPON:
User: "pekanbaru"
→ "1. Xenia - Rp 350.000/hari\n2. Innova - Rp 550.000/hari"

DATA KOTA TERSEDIA: Pekanbaru
DATA MOBIL (HANYA GUNAKAN INI):
[{"id": "1", "nama": "Toyota Ayla", "kota": "Pekanbaru", "harga": "Rp 300000/hari"}]

FORMAT OUTPUT (WAJIB JSON):
{
  "intent": "show_list",
  "booking_details": {
      "is_ready": false,
      "car_id": "",
      "date": ""
  },
  "response": "Gunakan \n (backslash n) untuk baris baru di dalam string JSON ini"
}'''

completion = client.chat.completions.create(
    model="llama-3.1-8b-instant",
    messages=[{"role": "system", "content": system_prompt}, {"role": "user", "content": "daftar mobil di pekanbaru"}],
    temperature=0.2,
    response_format={"type": "json_object"}
)
print(completion.choices[0].message.content)
