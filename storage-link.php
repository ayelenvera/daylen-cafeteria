<?php
// storage-link.php - Crear enlace simbólico para storage
echo "<h3>Creando enlace de storage...</h3>";

try {
    $target = __DIR__ . '/storage/app/public';
    $link = __DIR__ . '/public/storage';
    
    // Verificar si ya existe
    if (file_exists($link)) {
        echo "<p>✅ El enlace de storage ya existe</p>";
    } else {
        // Crear el enlace
        if (symlink($target, $link)) {
            echo "<p>✅ Enlace de storage creado exitosamente!</p>";
        } else {
            echo "<p style='color: red;'>❌ Error creando el enlace</p>";
        }
    }
    
    // Verificar que funcione
    if (is_link($link)) {
        echo "<p>✅ Enlace verificado correctamente</p>";
    } else {
        echo "<p style='color: orange;'>⚠️ El enlace no se creó como symlink</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<p><strong>⚠️ IMPORTANTE: Borra este archivo después de usarlo</strong></p>";
echo "<p><a href='/daylen2/public/'>Ir al sistema</a></p>";
?>