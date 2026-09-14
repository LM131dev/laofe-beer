<?php
// scratch/parse_menu.php

$file = 'c:/xampp/htdocs/laofe-beer/digital_menu.html';
if (!file_exists($file)) {
    die("File not found: $file");
}

echo "Reading file...\n";
$content = file_get_contents($file);
if ($content === false) {
    die("Failed to read file.");
}

$target = '<div class="lp">ເມນູທັງໝົດ</div>';
$replacement = '<div class="lp">ເມນູທັງໝົດ</div><a href="menu.php" style="margin-bottom:clamp(12px,2.5vh,24px); text-decoration:none; color:#D4A843; border:1px solid rgba(212,168,67,.5); padding:8px 18px; border-radius:28px; font-size:13px; font-family:inherit; transition:all 0.2s; box-shadow:0 3px 14px rgba(0,0,0,.5); background:linear-gradient(135deg,#4a1e00,#8a3e14); display:inline-block; cursor:pointer;">&#8592; ກັບຄືນສູ່ເວັບໄຊ / Back to Website</a>';

if (strpos($content, $target) !== false) {
    echo "Target found. Replacing...\n";
    $new_content = str_replace($target, $replacement, $content);
    $result = file_put_contents($file, $new_content);
    if ($result !== false) {
        echo "Replacement done successfully. Written $result bytes.\n";
    } else {
        echo "Failed to write file.\n";
    }
} else {
    echo "Target not found in file.\n";
}
?>
