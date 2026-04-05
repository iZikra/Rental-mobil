import json
import sys
import time
import urllib.error
import urllib.request


def post_json(url: str, payload: dict, timeout_s: int = 20) -> dict:
    last_err: Exception | None = None
    for _ in range(12):
        try:
            data = json.dumps(payload).encode("utf-8")
            req = urllib.request.Request(
                url,
                data=data,
                headers={"Content-Type": "application/json"},
                method="POST",
            )
            with urllib.request.urlopen(req, timeout=timeout_s) as resp:
                body = resp.read().decode("utf-8")
                return json.loads(body)
        except urllib.error.URLError as e:
            last_err = e
            time.sleep(0.25)
    raise last_err or RuntimeError("Request failed")


def assert_contains(text: str, needle: str) -> None:
    if needle not in text:
        raise AssertionError(f"Expected to contain: {needle}\nActual: {text}")


def assert_not_contains(text: str, needle: str) -> None:
    if needle in text:
        raise AssertionError(f"Expected NOT to contain: {needle}\nActual: {text}")


def run() -> int:
    url = "http://localhost:5000/chat"

    context = (
        "DATA KOTA YANG TERSEDIA DI RENTAL INI:\n"
        "Pekanbaru, Jakarta\n\n"
        "DATA STOK MOBIL SAAT INI (REAL-TIME):\n"
        "- UNIT: Toyota Avanza | Cabang: Pekanbaru | Harga: Rp 300.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Daihatsu Xenia | Cabang: Pekanbaru | Harga: Rp 280.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Toyota Alphard | Cabang: Pekanbaru | Harga: Rp 600.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: bensin\n"
        "- UNIT: Toyota Agya | Cabang: Pekanbaru | Harga: Rp 220.000/hari | Tipe: city car | Transmisi: manual | Kursi: 5 | BBM: bensin\n"
        "- UNIT: Toyota Innova | Cabang: Jakarta | Harga: Rp 500.000/hari | Tipe: mpv | Transmisi: matic | Kursi: 7 | BBM: diesel\n"
    )

    cases = [
        {
            "name": "Sapaan doang",
            "payload": {"question": "halo", "user_name": "Tes", "context": context, "rental_id": "1", "history": []},
            "asserts": [
                lambda ans: assert_contains(ans, "Halo"),
                lambda ans: assert_contains(ans.lower(), "bisa saya bantu"),
            ],
        },
        {
            "name": "Cari mobil tanpa kota",
            "payload": {"question": "saya cari mobil", "user_name": "Tes", "context": context, "rental_id": "1", "history": []},
            "asserts": [
                lambda ans: assert_contains(ans.lower(), "kota"),
                lambda ans: assert_contains(ans.lower(), "cari mobil"),
            ],
        },
        {
            "name": "Kota tidak tersedia",
            "payload": {"question": "mau sewa mobil di solo", "user_name": "Tes", "context": context, "rental_id": "1", "history": []},
            "asserts": [
                lambda ans: assert_contains(ans.lower(), "maaf"),
                lambda ans: assert_contains(ans.lower(), "pekanbaru"),
                lambda ans: assert_contains(ans.lower(), "jakarta"),
                lambda ans: assert_not_contains(ans.lower(), "di solo tersedia"),
            ],
        },
        {
            "name": "Filter matic harus list semua di kota (Pekanbaru)",
            "payload": {
                "question": "matik",
                "user_name": "Tes",
                "context": context,
                "rental_id": "1",
                "history": [
                    {"user": "saya cari mobil di Pekanbaru", "bot": "Siap, di Pekanbaru bisa. Kakak cari yang matic atau manual?"},
                ],
            },
            "asserts": [
                lambda ans: assert_contains(ans.lower(), "pekanbaru"),
                lambda ans: assert_contains(ans, "1)"),
                lambda ans: assert_contains(ans, "2)"),
                lambda ans: assert_contains(ans.lower(), "avanza"),
                lambda ans: assert_contains(ans.lower(), "xenia"),
            ],
        },
        {
            "name": "Jawab iya setelah ditawari kota (tidak boleh nebak kota)",
            "payload": {
                "question": "iya",
                "user_name": "Tes",
                "context": context,
                "rental_id": "1",
                "history": [
                    {"user": "saya cari mobil", "bot": "Oke. Mau yang Pekanbaru atau Jakarta nih?"},
                ],
            },
            "asserts": [
                lambda ans: assert_contains(ans.lower(), "pekanbaru"),
                lambda ans: assert_contains(ans.lower(), "jakarta"),
                lambda ans: assert_contains(ans.lower(), "mau"),
            ],
        },
        {
            "name": "Filter kursi dari history (Pekanbaru, minimal 5 orang, matic)",
            "payload": {
                "question": "untuk 5 orang",
                "user_name": "Tes",
                "context": context,
                "rental_id": "1",
                "history": [
                    {"user": "saya cari mobil di Pekanbaru", "bot": "Siap, di Pekanbaru bisa. Kakak cari yang matic atau manual?"},
                    {"user": "matik", "bot": "Siap Kak. Di Pekanbaru yang sesuai ada: 1) Toyota Avanza (Rp 300.000/hari), 2) Daihatsu Xenia (Rp 280.000/hari). Mau yang mana?"},
                ],
            },
            "asserts": [
                lambda ans: assert_contains(ans.lower(), "pekanbaru"),
                lambda ans: assert_contains(ans, "1)"),
                lambda ans: assert_contains(ans.lower(), "avanza"),
                lambda ans: assert_contains(ans.lower(), "xenia"),
                lambda ans: assert_contains(ans.lower(), "alphard"),
            ],
        },
        {
            "name": "Memilih mobil pakai typo (alpard) tidak boleh ngulang listing",
            "payload": {
                "question": "alpard deh",
                "user_name": "Tes",
                "context": context,
                "rental_id": "1",
                "history": [
                    {"user": "saya cari mobil di Pekanbaru", "bot": "Siap, di Pekanbaru bisa. Kakak cari yang matic atau manual?"},
                    {"user": "matik", "bot": "Siap Kak. Di Pekanbaru yang sesuai ada: 1) Daihatsu Xenia (Rp 280.000/hari), 2) Toyota Avanza (Rp 300.000/hari), 3) Toyota Alphard (Rp 600.000/hari). Mau yang mana?"},
                    {"user": "untuk 5 orang", "bot": "Siap Kak. Untuk minimal 5 orang, di Pekanbaru yang sesuai ada: 1) Daihatsu Xenia (Rp 280.000/hari), 2) Toyota Avanza (Rp 300.000/hari), 3) Toyota Alphard (Rp 600.000/hari). Mau yang mana?"},
                ],
            },
            "asserts": [
                lambda ans: assert_contains(ans.lower(), "alphard"),
                lambda ans: assert_contains(ans.lower(), "booking"),
                lambda ans: assert_contains(ans.lower(), "konfirmasi booking"),
                lambda ans: assert_not_contains(ans.lower(), "yang sesuai ada"),
            ],
        },
        {
            "name": "Kalau hasil cuma 1 mobil, jangan minta saring lagi",
            "payload": {
                "question": "matik",
                "user_name": "Tes",
                "context": context,
                "rental_id": "1",
                "history": [
                    {"user": "jakarta", "bot": "Siap, di Jakarta bisa. Kakak cari yang matic atau manual?"},
                ],
            },
            "asserts": [
                lambda ans: assert_contains(ans.lower(), "jakarta"),
                lambda ans: assert_not_contains(ans.lower(), "saring lagi"),
                lambda ans: assert_contains(ans.lower(), "booking"),
                lambda ans: assert_contains(ans.lower(), "konfirmasi booking"),
            ],
        },
        {
            "name": "Jawab oke setelah panduan booking tidak boleh ngulang panduan",
            "payload": {
                "question": "okee",
                "user_name": "Tes",
                "context": context,
                "rental_id": "1",
                "history": [
                    {
                        "user": "matik",
                        "bot": "Siap Kak. Di Jakarta yang sesuai cuma Toyota Innova (Rp 500.000/hari).\n\nKalau Kakak mau booking, ini langkahnya:\n1) Buka menu Booking\n2) Pilih mobil Toyota Innova\n3) Isi tanggal & jam sewa (ambil/kembali)\n4) Upload KTP & SIM\n5) Klik Konfirmasi Booking, lalu lanjutkan pembayaran yang muncul",
                    }
                ],
            },
            "asserts": [
                lambda ans: assert_contains(ans.lower(), "tanggal"),
                lambda ans: assert_contains(ans.lower(), "hari"),
                lambda ans: assert_not_contains(ans.lower(), "1)"),
            ],
        },
        {
            "name": "Ganti pilihan setelah sempat pilih (gak jadi -> xenia)",
            "payload": {
                "question": "gak jadi, jadinya xenia r",
                "user_name": "Tes",
                "context": context,
                "rental_id": "1",
                "history": [
                    {"user": "saya cari mobil di Pekanbaru", "bot": "Siap, di Pekanbaru bisa. Kakak cari yang matic atau manual?"},
                    {"user": "matik", "bot": "Siap Kak. Di Pekanbaru yang sesuai ada: 1) Daihatsu Xenia (Rp 280.000/hari), 2) Toyota Avanza (Rp 300.000/hari), 3) Toyota Alphard (Rp 600.000/hari). Mau yang mana?"},
                    {"user": "alpard deh", "bot": "Oke Kak, dicatat: Toyota Alphard (cabang Pekanbaru).\n\nAku belum bisa booking-in dari chat, tapi caranya begini:\n1) Buka menu Booking\n2) Pilih mobil Toyota Alphard\n3) Isi tanggal & jam sewa (ambil/kembali)\n4) Upload KTP & SIM\n5) Klik Konfirmasi Booking, lalu lanjutkan pembayaran yang muncul"},
                ],
            },
            "asserts": [
                lambda ans: assert_contains(ans.lower(), "xenia"),
                lambda ans: assert_contains(ans.lower(), "booking"),
                lambda ans: assert_contains(ans.lower(), "konfirmasi booking"),
                lambda ans: assert_not_contains(ans.lower(), "yang sesuai ada"),
            ],
        },
    ]

    passed = 0
    failed = 0

    for c in cases:
        try:
            resp = post_json(url, c["payload"])
            ans = (resp.get("answer") or "").strip()
            if not ans:
                raise AssertionError(f"Empty answer. Full response: {resp}")
            for a in c["asserts"]:
                a(ans)
            passed += 1
            print(f"PASS: {c['name']}")
        except Exception as e:
            failed += 1
            print(f"FAIL: {c['name']}\n{e}\n")

    print(f"\nResult: {passed} passed, {failed} failed")
    return 0 if failed == 0 else 1


if __name__ == "__main__":
    try:
        raise SystemExit(run())
    except urllib.error.URLError as e:
        print("Tidak bisa konek ke Python service di http://localhost:5000/chat")
        print("Pastikan server bot jalan dulu: python python_service/rag_engine.py")
        print(f"Detail: {e}")
        raise SystemExit(2)
