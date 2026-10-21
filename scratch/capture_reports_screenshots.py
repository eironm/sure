import os
import subprocess

# We will fetch the rendered HTML from PHP for each URL and feed it to headless Chrome
urls = [
    ("att1_flat_table.png", "/admin/reports/print?view_type=table"),
    ("att2_grouped_facility.png", "/admin/reports/print?view_type=grouped&group_by=facility"),
    ("att3_grouped_month.png", "/admin/reports/print?view_type=grouped&group_by=month"),
    ("att4_analytics_dashboard.png", "/admin/reports/print?view_type=analytics"),
    ("admin_reports_web_dashboard.png", "/admin/reports?report_type=reservations"),
]

artifact_dir = r"C:\Users\Steam Deck User\.gemini\antigravity-ide\brain\946f76ad-3e8a-4a86-8092-8677b758fb0a"
chrome_exe = r"C:\Program Files\Google\Chrome\Application\chrome.exe"

for filename, path in urls:
    # Use PHP to generate the rendered HTML as authenticated admin
    fetch_cmd = [
        "php", "-r",
        f"""
        require 'vendor/autoload.php';
        $app = require 'bootstrap/app.php';
        $kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);
        $kernel->bootstrap();
        $admin = App\\Models\\User::where('role', 'admin')->first() ?: App\\Models\\User::where('role', 'superadmin')->first();
        Auth::login($admin);
        $req = Illuminate\\Http\\Request::create('{path}', 'GET');
        $res = $app->handle($req);
        echo $res->getContent();
        """
    ]
    html_res = subprocess.run(fetch_cmd, capture_output=True, text=True, encoding='utf-8')
    temp_html = os.path.join(r"c:\Users\Steam Deck User\Documents\capstone\current\scratch", "temp_report.html")
    with open(temp_html, 'w', encoding='utf-8') as f:
        f.write(html_res.stdout)

    out_png = os.path.join(artifact_dir, filename)
    chrome_cmd = [
        chrome_exe,
        "--headless=new",
        "--disable-gpu",
        "--window-size=1280,1400" if "print" in path else "--window-size=1400,1200",
        f"--screenshot={out_png}",
        temp_html
    ]
    c_res = subprocess.run(chrome_cmd, capture_output=True, text=True)
    print(f"Captured {filename}: exit code {c_res.returncode}")

print("All screenshots captured!")
