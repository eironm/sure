<?php
$srcPath = 'scratch/sidebar_crop.png';
$src = imagecreatefrompng($srcPath);
$w = 250;
$h = 100;
$dst = imagecreatetruecolor($w * 3, $h * 3);
imagecopyresampled($dst, $src, 0, 0, 0, 0, $w * 3, $h * 3, $w, $h);
imagepng($dst, 'scratch/sidebar_zoom.png');
echo "Zoomed\n";
