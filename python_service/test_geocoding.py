import math
import requests
import mysql.connector

def haversine(lat1, lon1, lat2, lon2):
    R = 6371.0 # Radius of earth in km
    dlat = math.radians(lat2 - lat1)
    dlon = math.radians(lon2 - lon1)
    a = math.sin(dlat / 2)**2 + math.cos(math.radians(lat1)) * math.cos(math.radians(lat2)) * math.sin(dlon / 2)**2
    c = 2 * math.atan2(math.sqrt(a), math.sqrt(1 - a))
    return R * c

def calculate_distances_to_branches(location_name):
    url = f"https://nominatim.openstreetmap.org/search?q={location_name}+Pekanbaru&format=json&limit=1"
    headers = {"User-Agent": "RentalMobilBot/1.0"}
    try:
        r = requests.get(url, headers=headers)
        data = r.json()
        if not data:
            return f"Lokasi '{location_name}' tidak ditemukan di peta."
        user_lat = float(data[0]['lat'])
        user_lon = float(data[0]['lon'])
        
        db = mysql.connector.connect(host="localhost", user="root", password="", database="rental_mobil")
        cursor = db.cursor(dictionary=True)
        cursor.execute("SELECT rental_id, nama_cabang, koordinat_lokasi FROM branches WHERE koordinat_lokasi IS NOT NULL")
        branches = cursor.fetchall()
        db.close()
        
        results = []
        for b in branches:
            coords = b['koordinat_lokasi'].split(',')
            b_lat = float(coords[0].strip())
            b_lon = float(coords[1].strip())
            dist = haversine(user_lat, user_lon, b_lat, b_lon)
            results.append((dist, b['nama_cabang'], b['rental_id']))
            
        results.sort(key=lambda x: x[0])
        
        info = f"Lokasi terdeteksi: {data[0]['display_name']} ({user_lat}, {user_lon})\nJarak ke mitra:\n"
        for d, name, rid in results:
            info += f"- {name} (Rental ID: {rid}): {d:.1f} km\n"
        return info
    except Exception as e:
        return f"Gagal menghitung jarak: {str(e)}"

print(calculate_distances_to_branches("Mall SKA"))
