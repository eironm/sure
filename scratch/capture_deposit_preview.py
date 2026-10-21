import os, subprocess

preview_path = r'c:\Users\Steam Deck User\Documents\capstone\current\scratch\modal_preview.html'
with open(preview_path, 'r', encoding='utf-8') as f:
    c = f.read()

c = c.replace('cbmDepositAlert" class="cbm-deposit-alert d-none"', 'cbmDepositAlert" class="cbm-deposit-alert"')
with open(preview_path, 'w', encoding='utf-8') as f:
    f.write(c)

out_png = r'C:\Users\Steam Deck User\.gemini\antigravity-ide\brain\946f76ad-3e8a-4a86-8092-8677b758fb0a\cancel_modal_with_deposit_warning.png'
chrome_cmd = [
    r'C:\Program Files\Google\Chrome\Application\chrome.exe',
    '--headless=new',
    '--disable-gpu',
    '--window-size=1280,900',
    f'--screenshot={out_png}',
    preview_path
]
subprocess.run(chrome_cmd)
print('Saved deposit warning screenshot to:', out_png)
