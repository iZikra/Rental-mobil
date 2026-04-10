f = open('rag_engine.py', 'r', encoding='utf-8')
content = f.read()
f.close()

old1 = '            if has_car_intent and not selected_city and not is_question:\n                return jsonify({"answer": with_greeting("Siap Kak. Mau cari mobil di kota mana nih?")})\n'
new1 = '            if has_car_intent and not selected_city and not is_question and not is_price_question:\n                return jsonify({"answer": with_greeting("Siap Kak. Mau cari mobil di kota mana nih?")})\n'
old2 = '            if (wants_any_filter or wants_list_explicitly) and not selected_city and not is_question:\n                return jsonify({"answer": with_greeting("Sip. Cari mobilnya di kota mana nih?")})\n'
new2 = '            if (wants_any_filter or wants_list_explicitly) and not selected_city and not is_question and not is_price_question:\n                return jsonify({"answer": with_greeting("Sip. Cari mobilnya di kota mana nih?")})\n'

print('match1:', old1 in content)
print('match2:', old2 in content)
content = content.replace(old1, new1).replace(old2, new2)

f = open('rag_engine.py', 'w', encoding='utf-8')
f.write(content)
f.close()
print('done')
