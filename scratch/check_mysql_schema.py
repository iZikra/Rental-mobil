import mysql.connector

try:
    db = mysql.connector.connect(
        host="127.0.0.1",
        user="root",
        password="",
        database="rental_mobil"
    )
    cursor = db.cursor(dictionary=True)
    
    cursor.execute("SHOW TABLES")
    tables = cursor.fetchall()
    
    print("=== DAFTAR TABEL DI DATABASE rental_mobil ===")
    for table_dict in tables:
        # Extract the table name dynamically regardless of the exact key
        table_name = list(table_dict.values())[0]
        print(f"\\nTabel: {table_name}")
        
        cursor.execute(f"DESCRIBE {table_name}")
        columns = cursor.fetchall()
        for col in columns:
            print(f"  - {col['Field']} ({col['Type']})")
            
except Exception as e:
    print("Error:", e)
