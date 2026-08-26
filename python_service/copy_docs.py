import os

partners = [
    {"dir": "aa_rent", "name": "AA Rent Car"},
    {"dir": "evan_rental", "name": "Evan Rental"},
    {"dir": "putra_wijaya", "name": "Putra Wijaya Rent Car"},
    {"dir": "tng", "name": "PT. Trans Nusantara Gemilang Rent Car"}
]

base_dir = r"c:\Users\GF 63\rental-mobil\python_service\dokumen"
fz_dir = os.path.join(base_dir, "Fz")

with open(os.path.join(fz_dir, "denda.txt"), "r", encoding="utf-8") as f:
    denda_template = f.read()

with open(os.path.join(fz_dir, "persayaratan.txt"), "r", encoding="utf-8") as f:
    persyaratan_template = f.read()

for p in partners:
    target_dir = os.path.join(base_dir, p["dir"])
    
    # Write denda.txt
    denda_content = denda_template.replace("FZ Rent Car", p["name"])
    with open(os.path.join(target_dir, "denda.txt"), "w", encoding="utf-8") as f:
        f.write(denda_content)
        
    # Write persayaratan.txt
    persyaratan_content = persyaratan_template.replace("FZ RENT CAR", p["name"].upper())
    with open(os.path.join(target_dir, "persayaratan.txt"), "w", encoding="utf-8") as f:
        f.write(persyaratan_content)
        
    # Remove syarat_ketentuan.txt
    old_file = os.path.join(target_dir, "syarat_ketentuan.txt")
    if os.path.exists(old_file):
        os.remove(old_file)

print("Copy completed successfully.")
