<?php
$c = file_get_contents('http://127.0.0.1:8000/');
if (preg_match_all('/<img[^>]+hn-logo[^>]+>/', $c, $m)) {
    print_r($m[0]);
}
if (preg_match_all('/<img[^>]+logo[^>]+>/i', $c, $m2)) {
    print_r($m2[0]);
}
