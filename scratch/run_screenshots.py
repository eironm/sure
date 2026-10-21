import os
import subprocess

artifact_dir = r"C:\Users\Steam Deck User\.gemini\antigravity-ide\brain\946f76ad-3e8a-4a86-8092-8677b758fb0a"
scratch_dir = r"c:\Users\Steam Deck User\Documents\capstone\current\scratch"
chrome_exe = r"C:\Program Files\Google\Chrome\Application\chrome.exe"

files = [
    ("att1_flat_table.html", "report_att1_flat_table.png", "1280,1200"),
    ("att2_grouped_facility.html", "report_att2_grouped_facility.png", "1280,1200"),
    ("att3_grouped_month.html", "report_att3_grouped_month.png", "1280,1200"),
    ("att4_analytics_dashboard.html", "report_att4_analytics_dashboard.png", "1280,1050"),
    ("admin_reports_dashboard.html", "report_admin_web_dashboard.png", "1440,1100"),
]

for html_file, png_name, win_size in files:
    in_html = os.path.join(scratch_dir, html_file)
    out_png = os.path.join(artifact_dir, png_name)
    cmd = [
        chrome_exe,
        "--headless=new",
        "--disable-gpu",
        f"--window-size={win_size}",
        f"--screenshot={out_png}",
        in_html
    ]
    res = subprocess.run(cmd, capture_output=True, text=True)
    print(f"Captured {png_name} (status {res.returncode})")

print("Done all screenshots!")
