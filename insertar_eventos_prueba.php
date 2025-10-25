<?php
session_start();
require_once 'conexion.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

// Verificar que la tabla eventos existe, si no, crearla
$tabla_existe = false;
try {
    $conn->query("SELECT 1 FROM eventos LIMIT 1");
    $tabla_existe = true;
} catch (Exception $e) {
    $tabla_existe = false;
}

if (!$tabla_existe) {
    // Crear tabla eventos con nuevos tipos
    $sql_crear_tabla = "CREATE TABLE eventos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        descripcion TEXT,
        fecha_hora DATETIME NOT NULL,
        tipo ENUM('reto', 'evento', 'mision') DEFAULT 'evento',
        creado_por INT NOT NULL,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (creado_por) REFERENCES usuarios(id) ON DELETE CASCADE
    )";
    
    if ($conn->query($sql_crear_tabla)) {
        echo "Tabla eventos creada correctamente.<br>";
    } else {
        echo "Error al crear tabla eventos: " . $conn->error . "<br>";
        exit;
    }
}

// Desafíos de prueba
$eventos_prueba = [
    [
        'titulo' => 'Reunión de Equipo',
        'descripcion' => 'Revisión semanal del progreso del proyecto',
        'fecha_hora' => date('Y-m-d') . ' 14:00:00',
        'tipo' => 'evento'
    ],
    [
        'titulo' => 'Entrega de Proyecto',
        'descripcion' => 'Finalización del módulo de calendario',
        'fecha_hora' => date('Y-m-d', strtotime('+1 day')) . ' 10:00:00',
        'tipo' => 'reto'
    ],
    [
        'titulo' => 'Revisión de Código',
        'descripcion' => 'Code review del sprint actual',
        'fecha_hora' => date('Y-m-d', strtotime('+3 days')) . ' 16:00:00',
        'tipo' => 'evento'
    ],
    [
        'titulo' => 'Recordatorio: Backup',
        'descripcion' => 'Realizar backup de la base de datos',
        'fecha_hora' => date('Y-m-d', strtotime('+5 days')) . ' 09:00:00',
        'tipo' => 'mision'
    ],
    [
        'titulo' => 'Evento de Networking',
        'descripcion' => 'Conferencia de desarrolladores',
        'fecha_hora' => date('Y-m-d', strtotime('+7 days')) . ' 18:00:00',
        'tipo' => 'evento'
    ]
];

$usuario_id = $_SESSION['usuario_id'];
$eventos_insertados = 0;

foreach ($eventos_prueba as $evento) {
    $stmt = $conn->prepare('INSERT INTO eventos (titulo, descripcion, fecha_hora, tipo, creado_por) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssi', $evento['titulo'], $evento['descripcion'], $evento['fecha_hora'], $evento['tipo'], $usuario_id);
    
    if ($stmt->execute()) {
        $eventos_insertados++;
        echo "Desafío insertado: " . $evento['titulo'] . "<br>";
    } else {
        echo "Error al insertar desafío: " . $evento['titulo'] . " - " . $stmt->error . "<br>";
    }
    
    $stmt->close();
}

echo "<br><strong>Se insertaron $eventos_insertados desafíos de prueba.</strong><br>";
echo "<a href='dashboard.php?view=calendario-view' class='btn-modern text-white px-4 py-2 rounded-lg'>Ver Calendario</a>";
?>