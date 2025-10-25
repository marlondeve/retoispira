<?php
session_start();
require_once 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

echo "<h2>Verificando y creando tabla eventos...</h2>";

$tabla_existe = false;
try {
    $result = $conn->query("SHOW TABLES LIKE 'eventos'");
    $tabla_existe = $result->num_rows > 0;
    echo "Verificación de tabla eventos: " . ($tabla_existe ? "EXISTE" : "NO EXISTE") . "<br>";
} catch (Exception $e) {
    echo "Error al verificar tabla: " . $e->getMessage() . "<br>";
}

if (!$tabla_existe) {
    echo "Creando tabla eventos...<br>";
    
    $sql_crear_tabla = "CREATE TABLE eventos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titulo VARCHAR(255) NOT NULL,
        descripcion TEXT,
        fecha_hora DATETIME NOT NULL,
        tipo ENUM('reunion', 'tarea', 'recordatorio', 'evento') DEFAULT 'evento',
        creado_por INT NOT NULL,
        fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (creado_por) REFERENCES usuarios(id) ON DELETE CASCADE
    )";
    
    try {
        if ($conn->query($sql_crear_tabla)) {
            echo "✅ Tabla eventos creada correctamente<br>";
            
            $result = $conn->query("SHOW TABLES LIKE 'eventos'");
            if ($result->num_rows > 0) {
                echo "✅ Verificación: Tabla eventos existe<br>";
                
                $result = $conn->query("DESCRIBE eventos");
                echo "<h3>Estructura de la tabla eventos:</h3>";
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Default</th><th>Extra</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['Field'] . "</td>";
                    echo "<td>" . $row['Type'] . "</td>";
                    echo "<td>" . $row['Null'] . "</td>";
                    echo "<td>" . $row['Key'] . "</td>";
                    echo "<td>" . $row['Default'] . "</td>";
                    echo "<td>" . $row['Extra'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "❌ Error: La tabla no se creó correctamente<br>";
            }
        } else {
            echo "❌ Error al crear tabla: " . $conn->error . "<br>";
        }
    } catch (Exception $e) {
        echo "❌ Excepción al crear tabla: " . $e->getMessage() . "<br>";
    }
} else {
    echo "✅ La tabla eventos ya existe<br>";
    
    $result = $conn->query("DESCRIBE eventos");
    echo "<h3>Estructura de la tabla eventos:</h3>";
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Llave</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "<br><a href='dashboard.php?view=calendario-view' style='background: #8b5cf6; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px;'>Ir al Calendario</a>";
?> 