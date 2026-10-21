<?php
$srcPath = 'C:/Users/Steam Deck User/.gemini/antigravity-ide/brain/c2198268-85ca-4dde-b6e8-e6bbfe958f54/.user_uploaded/media_1792218555226.png';
$src = imagecreatefrompng($srcPath);
$dst = imagecreatetruecolor(300, 200);
imagecopy($dst, $src, 0, 0, 0, 0, 300, 200);
imagepng($dst, __DIR__ . '/sidebar_crop.png');
echo "Cropped successfully\n";
