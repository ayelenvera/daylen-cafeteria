<?php
// test.php - Prueba básica de PHP y Laravel
echo "<h1>🚀 Test Daylen System</h1>";
echo "<p>Si ves esto, PHP funciona en Railway</p>";

// Probar PHP básico
echo "<p>✅ PHP Version: " . phpversion() . "</p>";

// Probar si Laravel carga
try {
    require __DIR__.'/vendor/autoload.php';
    $app = require_once __DIR__.'/bootstrap/app.php';
    echo "<p style='color: green;'>✅ Laravel cargado EXITOSAMENTE</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error Laravel: " . $e->getMessage() . "</p>";
}

// Probar conexión MySQL
try {
    $pdo = new PDO(
        "mysql:host=" . ($_ENV['DB_HOST'] ?? 'mysql.railway.internal') . ";dbname=" . ($_ENV['DB_DATABASE'] ?? 'railway'),
        $_ENV['DB_USERNAME'] ?? 'root',
        $_ENV['DB_PASSWORD'] ?? 'NzxQORHVjRZYcLAFPAjESKjcCWHXJbJc'
    );
    echo "<p style='color: green;'>✅ Conexión MySQL EXITOSA</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error MySQL: " . $e->getMessage() . "</p>";
}

echo "<hr><p>🔍 Esta prueba nos dirá exactamente qué componente falla.</p>";
?>
