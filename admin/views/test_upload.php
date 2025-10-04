<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>

<html>
<head><title>Test Upload</title></head>
<body>
<h1>Upload Directory Test</h1>
<?php
$dir = __DIR__ . '/uploads';
echo "Checking: $dir<br>";

if (!file_exists($dir)) {
    echo "Directory does not exist.";
} elseif (!is_writable($dir)) {
    echo "Directory is NOT writable.";
} else {
    echo "Directory is writable.";
}
?>
</body>
</html>
