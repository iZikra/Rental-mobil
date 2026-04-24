import re

def parse_stock_items(context: str) -> list[dict]:
    if 'DATA STOK MOBIL SAAT INI' not in context:
        return []
    try:
        stock_section = context.split('DATA STOK MOBIL SAAT INI', 1)[1]
        lines = [ln.strip() for ln in stock_section.splitlines() if '|' in ln]
        
        items = []
        for ln in lines:
            clean_ln = re.sub(r"^[*\-\s]+", "", ln).strip()
            parts = [p.strip() for p in clean_ln.split('|')]
            if not parts: continue
            
            details = {}
            for p in parts:
                if ':' in p:
                    key_val = p.split(':', 1)
                    key = key_val[0].strip().upper()
                    val = key_val[1].strip()
                    
                    if key == 'ID': details['id'] = val
                    elif key in ('UNIT', 'NAMA'): details['name'] = val
                    elif key in ('CABANG', 'KOTA'): details['kota'] = val
                    elif key == 'HARGA': details['harga'] = val
            
            if 'name' in details and 'kota' in details:
                items.append(details)
        return items
    except Exception as e:
        print(f"Error parsing stock: {e}")
        return []

context = '''DATA STOK MOBIL SAAT INI
- ID: 1 | UNIT: Honda Brio | Cabang: Pekanbaru | Harga: Rp 350.000/hari | Tipe: Hatchback | Transmisi: Matic | Kursi: 5 | BBM: Bensin | Mitra: Rental AAA
- ID: 2 | UNIT: Toyota Innova | Cabang: Jakarta | Harga: Rp 550.000/hari
'''

print(parse_stock_items(context))
