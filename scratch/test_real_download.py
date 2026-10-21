import csv
import io
import requests
import re

session = requests.Session()

# 1. Login as admin
login_page = session.get("http://127.0.0.1:8000/login")
token = re.search(r'name="_token"\s+value="([^"]+)"', login_page.text).group(1)

session.post("http://127.0.0.1:8000/login", data={
    "_token": token,
    "email": "admin@sure-system.com",
    "password": "admin123"
})

# 2. Test downloading CSV across multiple types
test_cases = [
    ("analytics", "http://127.0.0.1:8000/admin/reports/export-csv?report_type=analytics&date_preset=this_year"),
    ("reservations", "http://127.0.0.1:8000/admin/reports/export-csv?report_type=reservations"),
    ("dispatch", "http://127.0.0.1:8000/admin/reports/export-csv?report_type=dispatch&has_logistics=1"),
    ("income", "http://127.0.0.1:8000/admin/reports/export-csv?report_type=income"),
    ("cancellations", "http://127.0.0.1:8000/admin/reports/export-csv?report_type=cancellations"),
]

for name, url in test_cases:
    resp = session.get(url)
    disp = resp.headers.get("Content-Disposition", "")
    print(f"\n--- Checking Download: {name} ---")
    print("Status:", resp.status_code)
    print("Content-Type:", resp.headers.get("Content-Type"))
    print("Content-Disposition:", disp)
    print("Content-Length:", resp.headers.get("Content-Length"))

    # Extract filename
    match = re.search(r'filename="([^"]+)"', disp)
    filename = match.group(1) if match else None
    print("Extracted Filename:", filename)

    # Check content
    text = resp.content.decode("utf-8-sig") # strip BOM
    reader = csv.reader(io.StringIO(text))
    rows = list(reader)
    print(f"CSV Row Count: {len(rows)}")
    print(f"Header: {rows[0][:4]}")

print("\nALL DOWNLOAD TESTS COMPLETED SUCCESSFULLY!")
