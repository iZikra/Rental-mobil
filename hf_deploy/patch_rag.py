import re

with open('rag_engine.py', 'r', encoding='utf-8') as f:
    content = f.read()

start_marker = "# 2. BACKEND ENTITY DETECTION (SOURCE OF TRUTH) - BEFORE LLM"
end_marker = "# 6. FAQ / Policy Handling (RAG) - Tetap ada sebagai pelengkap jika AI butuh info spesifik"

replacement = '''        items_dump = json.dumps([
            {"id": it.get('id', ''),
             "nama": it.get('name', ''), 
             "kota": it.get('kota', ''),
             "transmisi": it.get('transmisi', ''), 
             "kursi": it.get('kursi', ''), 
             "harga": f"Rp {str(it.get('harga', '')).replace('Rp', '').replace('rp', '').replace('/hari', '').strip()}/hari"} 
            for it in items
        ])

        # 2. CONSTRUCT SYSTEM PROMPT (PURE LLM LOGIC)
        system_prompt = f"""Kamu adalah asisten platform rental mobil multi-kota.

IDENTITAS & BATASAN:
- Sistem melayani banyak kota.
- HANYA jawab hal yang berkaitan dengan rental mobil.
- JIKA user menyebutkan kota yang TIDAK ADA, beritahu dengan sopan.

ALUR PERCAKAPAN (KAMU YANG MENENTUKAN):
- Kamu harus membaca riwayat percakapan untuk memahami niat (intent) user.
- Jika user mencari mobil tapi belum menyebutkan kota, TANYAKAN KOTA. Jangan tampilkan daftar.
- Jika user meminta daftar mobil di kota tertentu, TAMPILKAN DAFTAR MOBIL HANYA untuk kota tersebut.
- Jika user sudah memilih mobil, tanyakan TANGGAL PEMAKAIAN (jika belum menyebutkan).
- Jika user merespons "iya", "lanjut", atau setuju untuk melihat mobil, tampilkan daftarnya sesuai kota.
- Jika user membatalkan (cth: "tidak jadi", "ganti kota"), lupakan pilihan sebelumnya dan ikuti alur baru.
- Jika user SUDAH FIX memilih KOTA, MOBIL, dan TANGGAL: berikan konfirmasi akhir dan WAJIB set is_ready menjadi true dalam JSON.

GAYA BAHASA:
- Gunakan bahasa Indonesia yang merespon secara langsung, profesional, netral.
- Dilarang keras menggunakan sapaan seperti "Kak", "Bro", "Sis", dll.
- Jika informasi kurang (misal kota belum ada), tanya langsung dan singkat.

CONTOH RESPON:
User: "saya ingin melihat mobil"
→ "Anda ingin melihat mobil di kota mana?"
User: "pekanbaru"
→ "Berikut daftar mobil yang tersedia di Pekanbaru:\\n1. Xenia - Rp 350.000/hari\\n2. Innova - Rp 550.000/hari"

DATA KOTA TERSEDIA:
{", ".join(available_cities) if available_cities else 'Belum ada data'}

DATA MOBIL (HANYA GUNAKAN INI):
{items_dump}

FORMAT OUTPUT (WAJIB JSON):
{{
  "intent": "greeting | ask_city | show_list | ask_date | booking_ready | cancel | other",
  "booking_details": {{
      "is_ready": false,
      "car_id": "ISI_ID_MOBIL_DARI_DATA",
      "date": "ISI_TANGGAL_YANG_DIMINTA"
  }},
  "response": "Teks jawaban untuk user yang natural. Jangan membuat tag LINK_BOOKING di dalam response ini, sistem akan menambahkannya otomatis."
}}"""

        messages = [{"role": "system", "content": system_prompt}]
        for h in raw_history[-5:]:
            if isinstance(h, dict):
                if h.get('user'): messages.append({"role": "user", "content": h['user']})
                if h.get('bot'): messages.append({"role": "assistant", "content": h['bot']})
        messages.append({"role": "user", "content": user_input})

        # 3. LLM INTERPRETATION
        completion = client.chat.completions.create(
            model="llama-3.1-8b-instant",
            messages=messages,
            temperature=0.2,
            response_format={"type": "json_object"}
        )

        res_ai = {}
        try:
            res_ai = json.loads(completion.choices[0].message.content)
            final_answer = res_ai.get("response", "Ada yang bisa saya bantu?")
        except:
            final_answer = "Maaf, bisa diulangi lagi?"

        # --- SOFT GUARDRAIL (MEMASTIKAN FORMAT LIST BERHASIL DIBUAT) ---
        if "Rp" in final_answer and "-" in final_answer:
            lines = final_answer.split("\\n")
            numbered = []
            i = 1
            has_bullet = False
            for ln in lines:
                if ln.strip().startswith("-"):
                    clean = ln.lstrip("-").strip()
                    numbered.append(f"{i}. {clean}")
                    i += 1
                    has_bullet = True
                else:
                    numbered.append(ln)
            if has_bullet:
                final_answer = "\\n".join(numbered)

        # --- GENERATE BOOKING LINK (PURE LLM INTENT) ---
        booking_details = res_ai.get("booking_details", {})
        if booking_details.get("is_ready") and booking_details.get("car_id") and booking_details.get("date"):
            car_id = booking_details.get("car_id")
            date_val = booking_details.get("date")
            final_answer += f"<br><br>[LINK_BOOKING:{car_id}|{date_val}]"

        '''

new_content = content[:content.find(start_marker)] + replacement + content[content.find(end_marker):]

with open('rag_engine.py', 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Patch applied")
