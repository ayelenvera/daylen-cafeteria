<?php
echo "<h1>🚀 Diagnóstico Daylen</h1>";

// Verificar estructura de archivos
$paths = [
    '/app/vendor/autoload.php',
    '/app/public/vendor/autoload.php', 
    '/vendor/autoload.php',
    './vendor/autoload.php',
    '../vendor/autoload.php'
];

foreach ($paths as $path) {
    if (file_exists($path)) {
        echo "<p style='color: green;'>✅ $path - EXISTE</p>";
    } else {
        echo "<p style='color: red;'>❌ $path - NO EXISTE</p>";
    }
}

// Verificar directorio actual
echo "<p>📁 Directorio actual: " . getcwd() . "</p>";

// Listar archivos en directorio actual
echo "<p>📋 Archivos en directorio actual:</p>";
echo "<pre>";
print_r(scandir(getcwd()));
echo "</pre>";
?>
