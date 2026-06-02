import sys
import re

with open("rag_engine.py", "r", encoding="utf-8") as f:
    content = f.read()

# 1. Add imports if not exist
if "import math" not in content:
    content = content.replace("import time", "import time\nimport math\nimport requests\nimport mysql.connector")

# 2. Add geocoding logic before @app.route('/chat')
geocoding_code = """
def haversine(lat1, lon1, lat2, lon2):
    R = 6371.0
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
            return f"Sistem gagal menemukan koordinat Peta untuk lokasi '{location_name}'."
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
        info = f"INFORMASI SISTEM: Jarak mitra (Rental ID) dari titik lokasi {data[0]['display_name']} ({user_lat}, {user_lon}):\\n"
        for d, name, rid in results:
            info += f"- {name} (ID: {rid}): {d:.1f} km\\n"
        return info
    except Exception as e:
        return f"Gagal menghitung jarak: {str(e)}"

@app.route('/chat', methods=['POST'])
"""

content = content.replace("@app.route('/chat', methods=['POST'])", geocoding_code)

# 3. Modify Groq API call inside chat()
old_groq_call = """        completion = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=messages,
            temperature=0.3,
            max_tokens=2048,
            response_format={"type": "json_object"}
        )"""

new_groq_call = """        # Setup Tools
        tools = [{
            "type": "function",
            "function": {
                "name": "calculate_distances_to_branches",
                "description": "Panggil fungsi ini JIKA user menanyakan mitra terdekat, atau menanyakan jarak mitra ke sebuah lokasi tertentu (misal 'mall ska', 'bandara', 'sudirman', dll).",
                "parameters": {
                    "type": "object",
                    "properties": {
                        "location_name": {"type": "string", "description": "Nama lokasi atau jalan (misal: 'Mall SKA', 'Universitas Riau')"}
                    },
                    "required": ["location_name"]
                }
            }
        }]

        # 1. Panggil LLM dengan Tools (Tanpa JSON constraint)
        completion_tool = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=messages,
            temperature=0.3,
            max_tokens=2048,
            tools=tools,
            tool_choice="auto"
        )
        
        msg_tool = completion_tool.choices[0].message
        
        # Jika LLM memutuskan untuk memanggil fungsi jarak
        if msg_tool.tool_calls:
            for tool_call in msg_tool.tool_calls:
                import json
                args = json.loads(tool_call.function.arguments)
                location_name = args.get("location_name")
                
                print(f"[RAG_ENGINE] [AGENTIC] LLM meminta kalkulasi jarak untuk lokasi: '{location_name}'", flush=True)
                
                # Jalankan fungsi Python
                dist_result = calculate_distances_to_branches(location_name)
                print(f"[RAG_ENGINE] [AGENTIC] Hasil Kalkulasi:\\n{dist_result}", flush=True)
                
                # Append hasil kalkulasi ke messages context
                messages.append(msg_tool) # Tambahkan assistant tool call intent
                messages.append({
                    "role": "tool",
                    "tool_call_id": tool_call.id,
                    "name": tool_call.function.name,
                    "content": dist_result
                })
        
        # 2. Panggil LLM untuk final answer (Wajib JSON)
        completion = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=messages,
            temperature=0.3,
            max_tokens=2048,
            response_format={"type": "json_object"}
        )"""

content = content.replace(old_groq_call, new_groq_call)

with open("rag_engine_v3.py", "w", encoding="utf-8") as f:
    f.write(content)

print("rag_engine_v3.py created successfully with Agentic Tool Calling logic.")
