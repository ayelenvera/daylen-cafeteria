<?php
// migrate.php - ARCHIVO TEMPORAL para ejecutar migraciones
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

echo "<h3>Ejecutando migraciones en Byet.host...</h3>";

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    echo "<p>✅ Aplicación cargada correctamente</p>";
    
    // Ejecutar migraciones
    $kernel->call('migrate', ['--force' => true]);
    
    echo "<p>✅ Migraciones ejecutadas exitosamente!</p>";
    echo "<p>✅ Tablas de base de datos creadas</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE: Borra este archivo después de usarlo</strong></p>";
echo "<p><a href='/daylen2/public/'>Ir al sistema</a></p>";
?>