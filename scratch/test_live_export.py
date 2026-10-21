import urllib.request
import http.cookiejar

# Let's test hitting http://127.0.0.1:8000/admin/reports/export-csv
# First let's check if the server is up and what response we get
try:
    req = urllib.request.Request("http://127.0.0.1:8000/admin/reports/export-csv?report_type=analytics&date_preset=this_year")
    with urllib.request.urlopen(req) as resp:
        print("Status:", resp.status)
        print("Headers:")
        for k, v in resp.getheaders():
            print(f"  {k}: {v}")
        body = resp.read()
        print("Body length:", len(body))
        print("Preview:", body[:200])
except Exception as e:
    print("Error:", e)
