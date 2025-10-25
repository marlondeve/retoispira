<?php
// Script para inicializar la base de datos con datos necesarios
// Ejecutar este script una vez después de crear las tablas

require_once __DIR__ . '/conexion.php';

echo "<h2>Inicializando base de datos...</h2>";

// Verificar si ya existe un usuario admin y crearlo si no
$admin_email = 'admin@retoinspira.com';
$admin_password = 'Platino5..';
$admin_nombre = 'Admin Reto Inspira';
$admin_rol = 'admin';

$stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
$stmt->bind_param('s', $admin_email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo "<p>Creando usuario administrador...</p>";
    $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);
    $stmt->close();
    $stmt = $conn->prepare('INSERT INTO usuarios (nombre, email, password_hash, rol, activo) VALUES (?, ?, ?, ?, 1)');
    $stmt->bind_param('ssss', $admin_nombre, $admin_email, $password_hash, $admin_rol);
    if ($stmt->execute()) {
        echo "<p>✅ Usuario administrador creado con éxito.</p>";
    } else {
        echo "<p>❌ Error al crear el usuario administrador: " . $conn->error . "</p>";
    }
} else {
    echo "<p>✅ Usuario administrador ya existe.</p>";
}
$stmt->close();
$conn->close();

echo "<h3>Inicialización completada</h3>";
echo "<p><a href='dashboard.php'>Ir al Dashboard</a></p>";
?>