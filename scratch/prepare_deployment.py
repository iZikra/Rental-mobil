import os
import zipfile

def zip_files(zip_name, base_path, includes=None, excludes=None):
    print(f"Creating {zip_name}...")
    with zipfile.ZipFile(zip_name, 'w', zipfile.ZIP_DEFLATED) as zipf:
        for root, dirs, files in os.walk(base_path):
            # Apply excludes
            if excludes:
                dirs[:] = [d for d in dirs if d not in excludes]
                files = [f for f in files if f not in excludes]
            
            for file in files:
                file_path = os.path.join(root, file)
                rel_path = os.path.relpath(file_path, base_path)
                
                # If includes is set, only include specific files/dirs at the top level
                if includes:
                    is_included = False
                    for inc in includes:
                        if rel_path.startswith(inc):
                            is_included = True
                            break
                    if not is_included:
                        continue
                
                zipf.write(file_path, rel_path)
    print(f"Done! Created {zip_name}")

# --- 1. PREPARE INFINITYFREE (LARAVEL UPDATE) ---
# Hanya kirim file yang berubah (App, Views, Routes) agar ukuran kecil
zip_files(
    'update_infinityfree_revisi.zip', 
    '.', 
    includes=[
        'app', 
        'resources/views', 
        'routes', 
        'public/build', 
        'config',
        'clear_cache.php'
    ],
    excludes=['vendor', 'node_modules', '.git', 'storage']
)

# --- 2. PREPARE HUGGINGFACE (PYTHON RAG SERVICE) ---
# Kirim seluruh folder python_service termasuk database chroma_db
zip_files(
    'update_huggingface_rag.zip', 
    'python_service', 
    excludes=['__pycache__', '.env', 'error.log', 'debug.log']
)

print("\nSemua paket update telah siap!")
print("1. update_infinityfree_revisi.zip -> Upload ke InfinityFree")
print("2. update_huggingface_rag.zip -> Upload & Extract ke HuggingFace Spaces")
