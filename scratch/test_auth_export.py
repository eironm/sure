import requests
import re

session = requests.Session()

# 1. Get login page to get CSRF token and session
login_page = session.get("http://127.0.0.1:8000/login")
match = re.search(r'name="_token"\s+value="([^"]+)"', login_page.text)
token = match.group(1) if match else None

# 2. Login as admin
login_resp = session.post("http://127.0.0.1:8000/login", data={
    "_token": token,
    "email": "admin@sure-system.com",
    "password": "admin123"
}, allow_redirects=True)

print("Logged in! Final URL:", login_resp.url)
print("Status:", login_resp.status_code)

# 3. Request export-csv
export_resp = session.get("http://127.0.0.1:8000/admin/reports/export-csv?report_type=analytics&date_preset=this_year", stream=True)
print("\n--- EXPORT CSV RESPONSE ---")
print("Status:", export_resp.status_code)
print("Headers:")
for k, v in export_resp.headers.items():
    print(f"  {k}: {v}")

content = export_resp.content
print("Content length:", len(content))
print("Preview:", content[:200])
