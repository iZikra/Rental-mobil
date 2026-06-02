import os
import json
from groq import Groq

def test_groq():
    client = Groq(api_key=os.getenv("GROQ_API_KEY"))
    
    tools = [
        {
            "type": "function",
            "function": {
                "name": "calculate_distances",
                "description": "Calculate distance from a given location to all rental branches.",
                "parameters": {
                    "type": "object",
                    "properties": {
                        "location_name": {"type": "string"}
                    },
                    "required": ["location_name"]
                }
            }
        }
    ]
    
    messages = [
        {"role": "system", "content": "You are a helpful assistant. You must reply in JSON format with {'response': '...'} after answering. If you need distance, call the tool."},
        {"role": "user", "content": "mitra terdekat dari mall ska?"}
    ]
    
    try:
        completion = client.chat.completions.create(
            model="llama-3.3-70b-versatile",
            messages=messages,
            tools=tools,
            tool_choice="auto"
        )
        msg = completion.choices[0].message
        print("Tool Calls:", msg.tool_calls)
        print("Content:", msg.content)
    except Exception as e:
        print("Error:", e)

if __name__ == "__main__":
    from dotenv import load_dotenv
    load_dotenv(dotenv_path="../.env")
    test_groq()
