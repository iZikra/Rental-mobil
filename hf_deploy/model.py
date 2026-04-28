import os
import google.generativeai as genai
from dotenv import load_dotenv

# 1. Load Environment (Pastikan .env terbaca)
load_dotenv()
GEMINI_API_KEY = os.getenv("GOOGLE_API_KEY")

if not GEMINI_API_KEY:
    print("❌ ERROR: API Key tidak ditemukan di file .env!")
else:
    genai.configure(api_key=GEMINI_API_KEY)

def check_connection():
    print("🔍 Sedang memverifikasi koneksi ke Google Gemini...")
    try:
        models = genai.list_models()
        found_models = []
        for m in models:
            if 'generateContent' in m.supported_generation_methods:
                clean_name = m.name.replace("models/", "")
                found_models.append(clean_name)
        
        if found_models:
            print(f"✅ Koneksi Berhasil! Model tersedia: {', '.join(found_models[:3])}...")
            return True
        else:
            print("⚠️ Tidak ada model generateContent yang ditemukan.")
            return False
            
    except Exception as e:
        print(f"❌ Error Koneksi: {str(e)}")
        return False

# Jalankan test jika file ini dipanggil langsung
if __name__ == "__main__":
    check_connection()