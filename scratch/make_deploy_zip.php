<?php
$zip = new ZipArchive();
$zipFile = 'C:/xampp/htdocs/laofe-beer-deploy.zip';

if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $source = realpath('C:/xampp/htdocs/laofe-beer');
    
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($source) + 1);
            $relativePath = str_replace('\\', '/', $relativePath);
            
            // Skip scratch folder or temp deployment scripts
            if (strpos($relativePath, 'scratch/') === 0) continue;
            
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    echo "DEPLOY ZIP CREATED SUCCESSFULLY!\n";
} else {
    echo "FAILED TO CREATE ZIP\n";
}
?>
