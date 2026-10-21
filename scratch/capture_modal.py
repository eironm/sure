import os
import subprocess
import time

# Create an HTML preview with the modal active to capture
html_content = '''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Booking Modal Preview</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f6fb;
            margin: 0;
            padding: 0;
        }
        .demo-bg {
            padding: 2.5rem;
            max-width: 1100px;
            margin: 0 auto;
        }
        .demo-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 1.75rem;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }
        .demo-banner {
            background: #fef3c7;
            border: 1.5px solid #f59e0b;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
    </style>
</head>
<body>
    <div class="demo-bg">
        <div class="demo-banner">
            <div>
                <strong style="color: #92400e; font-size: 1.1rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i> Cash Payment Pending — Ref: #RES-0164AC-09081518</strong>
                <p style="color: #78350f; margin: 0.35rem 0 0; font-size: 0.95rem;">Bring your payment slip to the cashier and pay <strong>₱40,000.00</strong> by Wednesday, September 16 at 11:00 PM.</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-dark btn-sm rounded-pill px-3"><i class="bi bi-printer"></i> Print Slip</button>
                <button class="btn btn-outline-danger btn-sm rounded-pill px-3"><i class="bi bi-x-circle"></i> Cancel</button>
            </div>
        </div>

        <div class="demo-card">
            <h4>University Main Auditorium</h4>
            <p class="text-muted">Schedule: Sep 23, 2026 - Sep 24, 2026</p>
        </div>
    </div>

'''

# Read modal component HTML
modal_file = os.path.join(os.path.dirname(__file__), '../resources/views/components/cancel-booking-modal.blade.php')
with open(modal_file, 'r', encoding='utf-8') as f:
    modal_content = f.read()

html_content += modal_content
html_content += '''
    <script>
        // Auto open modal on load for screenshot
        window.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('cancelBookingModal');
            var refEl = document.getElementById('cbmRef');
            var facEl = document.getElementById('cbmFacility');
            refEl.textContent = '#RES-0164AC-09081518';
            facEl.textContent = 'University Main Auditorium';
            modal.classList.add('cbm-active');
        });
    </script>
</body>
</html>
'''

preview_path = os.path.join(os.path.dirname(__file__), 'modal_preview.html')
with open(preview_path, 'w', encoding='utf-8') as f:
    f.write(html_content)

out_png = os.path.join(r"C:\Users\Steam Deck User\.gemini\antigravity-ide\brain\946f76ad-3e8a-4a86-8092-8677b758fb0a", "cancel_modal_screenshot.png")
chrome_cmd = [
    r"C:\Program Files\Google\Chrome\Application\chrome.exe",
    "--headless=new",
    "--disable-gpu",
    "--window-size=1280,900",
    f"--screenshot={out_png}",
    preview_path
]

res = subprocess.run(chrome_cmd, capture_output=True, text=True)
print("Chrome exit code:", res.returncode)
print("Screenshot saved to:", out_png)
