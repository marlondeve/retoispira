<?php
// Script para verificar que todas las tablas estén creadas correctamente
require_once __DIR__ . '/conexion.php';

echo "<h2>🔍 Verificación de Instalación</h2>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .success { color: #10B981; font-weight: bold; }
    .error { color: #EF4444; font-weight: bold; }
    .warning { color: #F59E0B; font-weight: bold; }
    .info { color: #3B82F6; font-weight: bold; }
    .table { border-collapse: collapse; width: 100%; margin: 20px 0; }
    .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .table th { background-color: #f2f2f2; }
</style>";

$tablas_requeridas = [
    'usuarios' => 'Tabla de usuarios del sistema',
    'eventos' => 'Tabla de eventos/reuniones',
    'evento_participantes' => 'Relación entre eventos y participantes'
];

$tablas_existentes = [];
$tablas_faltantes = [];

echo "<h3>📋 Verificando tablas...</h3>";

foreach ($tablas_requeridas as $tabla => $descripcion) {
    $result = $conn->query("SHOW TABLES LIKE '$tabla'");
    if ($result->num_rows > 0) {
        $tablas_existentes[] = $tabla;
        echo "<div class='success'>✅ $tabla - $descripcion</div>";
    } else {
        $tablas_faltantes[] = $tabla;
        echo "<div class='error'>❌ $tabla - $descripcion (FALTANTE)</div>";
    }
}

echo "<h3>📊 Resumen</h3>";
echo "<div class='info'>Tablas existentes: " . count($tablas_existentes) . "/" . count($tablas_requeridas) . "</div>";

if (!empty($tablas_faltantes)) {
    echo "<div class='error'>Tablas faltantes: " . implode(', ', $tablas_faltantes) . "</div>";
    echo "<div class='warning'>Ejecuta el archivo 'database' en phpMyAdmin para crear las tablas faltantes.</div>";
}

// Verificar datos iniciales
echo "<h3>🔧 Verificando datos iniciales...</h3>";

// Verificar usuarios
$result = $conn->query("SELECT COUNT(*) as count FROM usuarios");
$row = $result->fetch_assoc();
if ($row['count'] > 0) {
    echo "<div class='success'>✅ Usuarios encontrados (" . $row['count'] . " usuarios)</div>";
} else {
    echo "<div class='error'>❌ No hay usuarios en el sistema</div>";
}

// Verificar usuarios admin
$result = $conn->query("SELECT COUNT(*) as count FROM usuarios WHERE rol = 'admin'");
$row = $result->fetch_assoc();
if ($row['count'] > 0) {
    echo "<div class='success'>✅ Usuarios administradores encontrados (" . $row['count'] . " admin)</div>";
} else {
    echo "<div class='error'>❌ No hay usuarios administradores</div>";
}

// Verificar usuarios miembros
$result = $conn->query("SELECT COUNT(*) as count FROM usuarios WHERE rol = 'miembro' AND activo = 1");
$row = $result->fetch_assoc();
if ($row['count'] > 0) {
    echo "<div class='success'>✅ Usuarios miembros activos encontrados (" . $row['count'] . " miembros)</div>";
} else {
    echo "<div class='warning'>⚠️ No hay usuarios miembros activos</div>";
}

echo "<h3>🎯 Estado del Sistema</h3>";

if (empty($tablas_faltantes) && $row['count'] > 0) {
    echo "<div class='success'>🎉 ¡El sistema está listo para usar!</div>";
    echo "<p><a href='dashboard.php' style='background: #3B82F6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ir al Dashboard</a></p>";
} else {
    echo "<div class='error'>⚠️ El sistema necesita configuración adicional</div>";
    
    if (!empty($tablas_faltantes)) {
        echo "<h4>Pasos para completar la instalación:</h4>";
        echo "<ol>";
        echo "<li>Ejecuta el archivo 'database' en phpMyAdmin</li>";
        echo "<li>Ejecuta <a href='inicializar_bd.php'>inicializar_bd.php</a></li>";
        echo "<li>Ejecuta <a href='crear_admin.php'>crear_admin.php</a></li>";
        echo "<li>Crea usuarios miembros en el sistema</li>";
        echo "</ol>";
    }
    
    if ($row['count'] == 0) {
        echo "<h4>Para crear usuarios:</h4>";
        echo "<ol>";
        echo "<li>Ejecuta <a href='crear_admin.php'>crear_admin.php</a> para crear el administrador</li>";
        echo "<li>Accede al sistema y crea usuarios miembros</li>";
        echo "</ol>";
    }
}

echo "<h3>📝 Próximos pasos</h3>";
echo "<ul>";
echo "<li>Crear usuarios miembros en la sección Usuarios</li>";

echo "</ul>";

$conn->close();
?>