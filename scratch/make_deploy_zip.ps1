Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

$sourcePath = "C:\xampp\htdocs\laofe-beer"
$zipPath = "C:\xampp\htdocs\laofe-beer-deploy.zip"

if (Test-Path $zipPath) { Remove-Item $zipPath -Force }

$zip = [System.IO.Compression.ZipFile]::Open($zipPath, [System.IO.Compression.ZipArchiveMode]::Create)

Get-ChildItem -Path $sourcePath -Recurse | ForEach-Object {
    if (-not $_.PSIsContainer) {
        $rel = $_.FullName.Substring($sourcePath.Length + 1).Replace('\', '/')
        if (-not $rel.StartsWith("scratch/") -and -not $rel.EndsWith("laofe-beer-deploy.zip")) {
            try {
                [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $_.FullName, $rel) | Out-Null
            } catch {
                Write-Host "Skipping locked file: $rel"
            }
        }
    }
}
$zip.Dispose()
Write-Host "LINUX ZIP SUCCESSFUL!"
