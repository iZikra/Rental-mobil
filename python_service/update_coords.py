import mysql.connector

def update_coordinates():
    try:
        db = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="rental_mobil"
        )
        cursor = db.cursor()
        
        # ID 1: FZ RENT CAR (Jl. Teropong)
        cursor.execute("UPDATE branches SET koordinat_lokasi = %s WHERE rental_id = 1", ("0.4561, 101.4116",))
        # ID 2: PUTRA WIDJAYA (Jakarta)
        cursor.execute("UPDATE branches SET koordinat_lokasi = %s WHERE rental_id = 2", ("-6.1754, 106.8272",))
        # ID 3: AA RENT CAR (Tenayan Raya)
        cursor.execute("UPDATE branches SET koordinat_lokasi = %s WHERE rental_id = 3", ("0.5054, 101.5161",))
        # ID 4: EVAN RENTAL (Panam)
        cursor.execute("UPDATE branches SET koordinat_lokasi = %s WHERE rental_id = 4", ("0.4636, 101.3855",))
        # ID 5: TNG RENT CAR (Payung Sekaki)
        cursor.execute("UPDATE branches SET koordinat_lokasi = %s WHERE rental_id = 5", ("0.5283, 101.4172",))
        
        db.commit()
        print("Coordinates updated successfully in branches table.")
        db.close()
    except Exception as e:
        print(f"Error updating coordinates: {e}")

if __name__ == "__main__":
    update_coordinates()
