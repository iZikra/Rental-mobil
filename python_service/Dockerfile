FROM python:3.10-slim

WORKDIR /app

# Install dependensi sistem yang mungkin dibutuhkan
RUN apt-get update && apt-get install -y \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy semua file kode dan database (termasuk chroma_db dan dokumen)
COPY . .

# Berikan izin ke direktori app agar HuggingFace tidak error permission
RUN chmod -R 777 /app

# Expose port standar HuggingFace Spaces
EXPOSE 7860

# Jalankan server Gunicorn
CMD ["gunicorn", "-w", "2", "--timeout", "120", "-b", "0.0.0.0:7860", "rag_engine:app"]
