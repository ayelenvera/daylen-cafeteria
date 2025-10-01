<?php
echo "<h1>🚀 Test con ruta ABSOLUTA</h1>";

// Usar ruta ABSOLUTA correcta
require '/app/vendor/autoload.php';

try {
    $app = require_once '/app/bootstrap/app.php';
    echo "<p style='color: green;'>✅ Laravel cargado EXITOSAMENTE con ruta absoluta</p>";
    
    // Probar configuración
    $config = $app->make('config');
    $appName = $config->get('app.name');
    echo "<p>✅ Config app.name: " . ($appName ?: 'NO CARGADO') . "</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
