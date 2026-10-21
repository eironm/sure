<?php
$cookieJar = tempnam(sys_get_temp_dir(), 'cj');

// 1. Get login page
$ch = curl_init('http://127.0.0.1:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
$loginPage = curl_exec($ch);
curl_close($ch);

preg_match('/name="_token" value="([^"]+)"/', $loginPage, $m);
$token = $m[1] ?? '';

// 2. Post login
$ch = curl_init('http://127.0.0.1:8000/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    '_token' => $token,
    'email' => 'superadmin@sure-system.com',
    'password' => 'password',
]));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$res = curl_exec($ch);
curl_close($ch);

// 3. Fetch /superadmin/settings/logo
$ch = curl_init('http://127.0.0.1:8000/superadmin/settings/logo');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
$logoPage = curl_exec($ch);
curl_close($ch);

preg_match('/name="_token" value="([^"]+)"/', $logoPage, $m);
$logoToken = $m[1] ?? '';

// 4. Create a test image to upload
$tmpFile = tempnam(sys_get_temp_dir(), 'upl');
$im = imagecreatetruecolor(80, 80);
$gold = imagecolorallocate($im, 255, 215, 0);
imagefilledellipse($im, 40, 40, 60, 60, $gold);
imagepng($im, $tmpFile);

$postData = [
    '_token' => $logoToken,
    '_method' => 'PUT',
    'settings[logo_white]' => new CURLFile($tmpFile, 'image/png', 'uploaded_seal.png'),
];

$ch = curl_init('http://127.0.0.1:8000/superadmin/settings/system');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$uploadRes = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Upload HTTP Code: " . $httpCode . "\n";

// Now fetch /superadmin/settings/logo again to see what is rendered in the HTML
$ch = curl_init('http://127.0.0.1:8000/superadmin/settings/logo');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
$newPage = curl_exec($ch);
curl_close($ch);

// Brand wrapper
if (preg_match('/<div class="brand-wrapper">.*?<\/div>/s', $newPage, $m)) {
    echo "Brand Wrapper:\n" . $m[0] . "\n\n";
}

// Previews
if (preg_match_all('/<div class="lv-preview".*?<\/div>/s', $newPage, $matches)) {
    echo "Previews:\n";
    foreach ($matches[0] as $m) {
        echo $m . "\n";
    }
}

// File Labels
if (preg_match_all('/<span class="file-label".*?<\/span>/s', $newPage, $matches)) {
    echo "File Labels:\n";
    foreach ($matches[0] as $m) {
        echo $m . "\n";
    }
}
