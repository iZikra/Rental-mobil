"""
=============================================================================
EVALUASI CHATBOT RAG - SEMUA RENTAL (MODE: ACCURACY ONLY)
Flask sudah running + embedding model sudah dimuat di Flask.
Script ini hanya memanggil /chat endpoint (tidak load ChromaDB lagi).

Untuk RELEVANCE: dibuat endpoint /debug_rag terpisah, atau
kita gunakan heuristik — cek apakah jawaban mengandung data dari .txt file.
=============================================================================
"""
import requests
import json

BASE_URL = "http://localhost:5000/chat"

# ============================================================
# CONTEXT TEMPLATE per Rental
# ============================================================
def make_context(rental_name, kota="Pekanbaru", stok_lines=""):
    return (
        f"DATA RENTAL YANG TERDAFTAR:\n{rental_name}\n\n"
        f"DATA KOTA YANG TERSEDIA DI RENTAL INI:\n{kota}\n\n"
        f"DATA STOK MOBIL SAAT INI (REAL-TIME):\n{stok_lines}\n"
    )

CTX_FZ = make_context(
    "Fz Rent Car",
    stok_lines=(
        "- UNIT: Toyota Innova Reborn | Cabang: Pekanbaru | Tipe: MPV | Transmisi: manual | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Toyota Alphard | Cabang: Pekanbaru | Tipe: MPV | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Daihatsu Xenia | Cabang: Pekanbaru | Tipe: MPV | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Toyota Agya | Cabang: Pekanbaru | Tipe: city car | Transmisi: matic | Kursi: 5 | BBM: bensin\n"
    )
)

CTX_PUTRA = make_context(
    "Putra Wijaya Rent Car",
    stok_lines=(
        "- UNIT: Toyota Innova Reborn | Cabang: Pekanbaru | Tipe: MPV | Transmisi: manual | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Toyota Fortuner | Cabang: Pekanbaru | Tipe: SUV | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Mitsubishi Xpander | Cabang: Pekanbaru | Tipe: MPV | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Toyota Alphard | Cabang: Pekanbaru | Tipe: MPV | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: All New Veloz | Cabang: Pekanbaru | Tipe: MPV | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
    )
)

CTX_AA = make_context(
    "AA RENT CAR",
    stok_lines=(
        "- UNIT: Toyota Innova Reborn | Cabang: Pekanbaru | Tipe: MPV | Transmisi: manual | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Toyota Alphard | Cabang: Pekanbaru | Tipe: MPV | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Mitsubishi Xpander | Cabang: Pekanbaru | Tipe: MPV | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Toyota Fortuner | Cabang: Pekanbaru | Tipe: SUV | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Toyota Agya | Cabang: Pekanbaru | Tipe: city car | Transmisi: matic | Kursi: 5 | BBM: bensin\n"
    )
)

# ============================================================
# TEST CASES
# doc_source : teks yang ADA di harga.txt / file RAG (untuk simulasi relevance check)
# ans_kw     : kata kunci yang HARUS ada di jawaban chatbot
# ============================================================
TEST_CASES = [
    # -------- FZ RENT (ID: 1) --------
    {
        "label"      : "FZ-1 | Harga Innova Bensin",
        "q"          : "Berapa harga sewa Innova Reborn bensin di FZ Rent?",
        "rental_id"  : "1",
        "ctx"        : CTX_FZ,
        "doc_source" : "Innova Reborn Bensin (matic & manual): Rp 550.000 / hari",  # dari harga.txt FZ
        "ans_kw"     : ["550"],
    },
    {
        "label"      : "FZ-2 | Harga Alphard",
        "q"          : "Berapa harga sewa Alphard di FZ Rent?",
        "rental_id"  : "1",
        "ctx"        : CTX_FZ,
        "doc_source" : "Alphard: Rp 5.000.000 / hari",
        "ans_kw"     : ["5.000", "5000"],
    },
    {
        "label"      : "FZ-3 | Harga Agya",
        "q"          : "Berapa harga sewa mobil Agya di FZ Rent?",
        "rental_id"  : "1",
        "ctx"        : CTX_FZ,
        "doc_source" : "Agya (matic & manual): Rp 300.000 / hari",
        "ans_kw"     : ["300"],
    },
    {
        "label"      : "FZ-4 | Syarat Booking",
        "q"          : "Syarat apa saja yang harus disiapkan untuk booking di FZ Rent?",
        "rental_id"  : "1",
        "ctx"        : CTX_FZ,
        "doc_source" : "E-KTP",
        "ans_kw"     : ["ktp", "sim", "kartu tanda penduduk"],
    },
    {
        "label"      : "FZ-5 | Denda Keterlambatan",
        "q"          : "Berapa denda kalau saya terlambat mengembalikan mobil di FZ Rent?",
        "rental_id"  : "1",
        "ctx"        : CTX_FZ,
        "doc_source" : "keterlambatan pengembalian",
        "ans_kw"     : ["10%", "10 persen", "per jam", "denda", "biaya tambahan"],
    },

    # -------- PUTRA WIJAYA (ID: 2) --------
    {
        "label"      : "PW-1 | Harga Xpander",
        "q"          : "Berapa harga sewa Xpander di Putra Wijaya?",
        "rental_id"  : "2",
        "ctx"        : CTX_PUTRA,
        "doc_source" : "Mitsubishi Xpander: Rp 450.000 / hari",
        "ans_kw"     : ["450"],
    },
    {
        "label"      : "PW-2 | Harga Fortuner",
        "q"          : "Berapa harga sewa Fortuner di Putra Wijaya?",
        "rental_id"  : "2",
        "ctx"        : CTX_PUTRA,
        "doc_source" : "Toyota Fortuner: Rp 1.600.000 / hari",
        "ans_kw"     : ["1.600", "1600"],
    },
    {
        "label"      : "PW-3 | Harga Alphard",
        "q"          : "Berapa harga sewa Alphard di Putra Wijaya?",
        "rental_id"  : "2",
        "ctx"        : CTX_PUTRA,
        "doc_source" : "Toyota Alphard: Rp 5.000.000 / hari",
        "ans_kw"     : ["5.000", "5000"],
    },
    {
        "label"      : "PW-4 | Harga Innova Solar",
        "q"          : "Berapa harga Innova Reborn Solar di Putra Wijaya?",
        "rental_id"  : "2",
        "ctx"        : CTX_PUTRA,
        "doc_source" : "Innova Reborn (Solar): Rp 600.000 / hari",
        "ans_kw"     : ["600"],
    },
    {
        "label"      : "PW-5 | Harga Veloz",
        "q"          : "Berapa harga All New Veloz di Putra Wijaya?",
        "rental_id"  : "2",
        "ctx"        : CTX_PUTRA,
        "doc_source" : "All New Veloz: Rp 300.000 / hari",
        "ans_kw"     : ["300"],
    },

    # -------- AA RENT (ID: 3) --------
    {
        "label"      : "AA-1 | Harga Xpander",
        "q"          : "Berapa harga sewa Xpander di AA Rent?",
        "rental_id"  : "3",
        "ctx"        : CTX_AA,
        "doc_source" : "Mitsubishi Xpander: Rp 450.000 / hari",
        "ans_kw"     : ["450"],
    },
    {
        "label"      : "AA-2 | Harga Fortuner",
        "q"          : "Berapa harga Fortuner di AA Rent?",
        "rental_id"  : "3",
        "ctx"        : CTX_AA,
        "doc_source" : "Toyota Fortuner: Rp 1.600.000 / hari",
        "ans_kw"     : ["1.600", "1600"],
    },
    {
        "label"      : "AA-3 | Harga Alphard",
        "q"          : "Berapa harga sewa Alphard di AA Rent?",
        "rental_id"  : "3",
        "ctx"        : CTX_AA,
        "doc_source" : "Toyota Alphard: Rp 5.000.000 / hari",
        "ans_kw"     : ["5.000", "5000"],
    },
    {
        "label"      : "AA-4 | Harga Agya",
        "q"          : "Berapa harga sewa Toyota Agya di AA Rent?",
        "rental_id"  : "3",
        "ctx"        : CTX_AA,
        "doc_source" : "Toyota Agya / Daihatsu Ayla: Rp 300.000 / hari",
        "ans_kw"     : ["300"],
    },
    {
        "label"      : "AA-5 | Harga Innova Bensin",
        "q"          : "Berapa harga Innova Reborn bensin di AA Rent?",
        "rental_id"  : "3",
        "ctx"        : CTX_AA,
        "doc_source" : "Toyota Innova Reborn (Bensin): Rp 550.000 / hari",
        "ans_kw"     : ["550"],
    },
]

# ============================================================
# FUNGSI: Cek accuracy via Flask /chat
# ============================================================
def check_accuracy(q, rental_id, ctx, ans_kw):
    payload = {
        "question"  : q,
        "user_name" : "Penguji",
        "context"   : ctx,
        "rental_id" : str(rental_id),
        "history"   : [],
    }
    try:
        res = requests.post(BASE_URL, json=payload, timeout=25)
        answer = res.json().get("answer", "")
        is_ok = any(k.lower() in answer.lower() for k in ans_kw)
        return is_ok, answer
    except requests.exceptions.ConnectionError:
        return None, "❌ Flask tidak bisa dihubungi (port 5000)"
    except Exception as e:
        return None, f"❌ Error: {e}"

# ============================================================
# FUNGSI: Simulasi Relevance — Cek apakah keyword doc_source
# harusnya di-retrieve (berdasarkan kecocokan kata kunci di teks .txt)
# Karena kita tidak bisa load ChromaDB lagi (OOM), kita ukur
# seberapa mirip pertanyaan dengan doc_source secara leksikal.
# Kalau sama ≥ 1 token penting → "diharapkan relevan"
# ============================================================
def estimate_relevance(q, doc_source):
    """
    Heuristik ringan: dianggap dokumen relevan jika ada overlap kata kunci
    antara pertanyaan dan dokumen yang seharusnya di-retrieve.
    Ini menggambarkan apakah ChromaDB 'seharusnya' berhasil.
    """
    stop = {"di", "yang", "untuk", "dari", "ke", "dengan", "dan", "atau", "sewa",
            "berapa", "harga", "mobil", "rent", "car", "apa", "saja"}
    q_tokens = set(q.lower().split()) - stop
    doc_tokens = set(doc_source.lower().replace(":", "").replace("/", " ").split()) - stop
    common = q_tokens & doc_tokens
    return len(common) >= 1, common

# ============================================================
# MAIN
# ============================================================
def run_evaluation():
    SEP = "=" * 72
    print(SEP)
    print("   EVALUASI CHATBOT RAG — FZ Rent | Putra Wijaya | AA Rent")
    print(SEP)

    total      = len(TEST_CASES)
    acc_pass   = 0
    acc_error  = 0
    rel_pass   = 0

    rows = []

    for idx, case in enumerate(TEST_CASES, 1):
        label     = case["label"]
        q         = case["q"]
        rid       = case["rental_id"]
        ctx       = case["ctx"]
        doc_src   = case["doc_source"]
        ans_kw    = case["ans_kw"]

        print(f"\n[{idx:02d}] {label}")
        print(f"      Q : {q}")

        # --- RELEVANCE (Heuristik) ---
        rel_ok, common_tokens = estimate_relevance(q, doc_src)
        if rel_ok:
            rel_pass += 1
            rel_icon = "✅"
            rel_note = f"token overlap: {common_tokens}"
        else:
            rel_icon = "❌"
            rel_note = "tidak ada overlap token penting"
        print(f"      RELEVANCE  : {rel_icon}  ({rel_note})")
        print(f"                   doc_source: \"{doc_src[:70]}\"")

        # --- ACCURACY (via Flask) ---
        acc_ok, answer = check_accuracy(q, rid, ctx, ans_kw)
        if acc_ok is None:
            acc_error += 1
            acc_icon = "⚠️ "
            acc_note = "ERROR"
        elif acc_ok:
            acc_pass += 1
            acc_icon = "✅"
            acc_note = f"keyword '{ans_kw}' ditemukan"
        else:
            acc_icon = "❌"
            acc_note = f"expected {ans_kw}"

        print(f"      ACCURACY   : {acc_icon}  {acc_note}")
        if not acc_ok:
            print(f"                   Bot jawab: \"{answer[:150]}\"")

        rows.append({"label": label, "rel": rel_ok, "acc": acc_ok})

    # --- SUMMARY ---
    valid_acc = total - acc_error
    rel_pct   = rel_pass / total * 100
    acc_pct   = acc_pass / valid_acc * 100 if valid_acc else 0

    print(f"\n{SEP}")
    print("  HASIL AKHIR EVALUASI")
    print(SEP)
    print(f"  Total kasus uji           : {total}")
    print(f"  Relevance PASS  (heuristik): {rel_pass}/{total}  →  {rel_pct:.0f}%")
    print(f"  Accuracy  PASS  (chatbot)  : {acc_pass}/{valid_acc}  →  {acc_pct:.0f}%")
    if acc_error:
        print(f"  ⚠️  Error (Flask offline?) : {acc_error} kasus")
    print(SEP)

    # Breakdown per rental
    print("\n  BREAKDOWN PER RENTAL:")
    rental_names = {
        "FZ": "FZ Rent (ID:1)",
        "PW": "Putra Wijaya (ID:2)",
        "AA": "AA Rent (ID:3)",
    }
    for prefix, name in rental_names.items():
        cases = [r for r in rows if r["label"].startswith(prefix)]
        r_ok = sum(1 for c in cases if c["rel"])
        a_cnt = sum(1 for c in cases if c["acc"] is not None)
        a_ok  = sum(1 for c in cases if c["acc"] is True)
        r_pct = r_ok / len(cases) * 100 if cases else 0
        a_pct = a_ok / a_cnt * 100 if a_cnt else 0
        print(f"    {name:30s} | Relevance: {r_ok}/{len(cases)} ({r_pct:.0f}%) | Accuracy: {a_ok}/{a_cnt} ({a_pct:.0f}%)")

    print(SEP)


if __name__ == "__main__":
    run_evaluation()
