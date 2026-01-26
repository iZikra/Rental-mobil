from google.generativeAI import genai
from .env import GEMINI_API_KEY


genai.configure(api_key=GEMINI_API_KEY)

print("Sedang menghubungi server Google...")

try:
    # 2. Minta daftar model
    found = False
    for m in genai.list_models():
        # Filter hanya model yang bisa chatting (generateContent)
        if 'generateContent' in m.supported_generation_methods:
            # Hapus awalan 'models/' agar kita tahu nama bersihnya
            clean_name = m.name.replace("models/", "")
            print(f"- {clean_name}")
            found = True

    if not found:
        print("Model tidak ditemukan. Cek API Key atau Koneksi Internet.")

except Exception as e:
    print(f"Error Koneksi: {e}")

