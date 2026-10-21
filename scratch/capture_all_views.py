import os
import subprocess

artifact_dir = r"C:\Users\Steam Deck User\.gemini\antigravity-ide\brain\946f76ad-3e8a-4a86-8092-8677b758fb0a"
chrome_exe = r"C:\Program Files\Google\Chrome\Application\chrome.exe"
scratch_dir = r"c:\Users\Steam Deck User\Documents\capstone\current\scratch"

views = [
    ("report_admin_web_dashboard.png", "web_dashboard.html", "1400,1200"),
    ("report_logistics_dispatch_web.png", "web_dispatch.html", "1400,1200"),
    ("report_cancellations_audit_web.png", "web_cancellations.html", "1400,1200"),
    ("report_dispatch_print.png", "print_dispatch.html", "1280,1400"),
    ("report_cancellations_print.png", "print_cancellations.html", "1280,1400"),
    ("report_att1_flat_table.png", "print_flat_table.html", "1280,1400"),
    ("report_att4_analytics_dashboard.png", "print_analytics.html", "1280,1400"),
]

for out_name, in_html, win_size in views:
    in_path = os.path.join(scratch_dir, in_html)
    out_png = os.path.join(artifact_dir, out_name)
    cmd = [
        chrome_exe,
        "--headless=new",
        "--disable-gpu",
        f"--window-size={win_size}",
        f"--screenshot={out_png}",
        in_path
    ]
    res = subprocess.run(cmd, capture_output=True, text=True)
    print(f"Captured {out_name}: exit code {res.returncode}")

print("Done capturing all screenshots!")
