<?php
// Asegurar que se devuelva JSON
header('Content-Type: application/json');

// Manejo de errores para evitar que se generen errores de PHP
error_reporting(0);
ini_set('display_errors', 0);

try {
    session_start();
    require_once 'conexion.php';

    // Verificar si el usuario está logueado
    if (!isset($_SESSION['usuario_id'])) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    // Función para verificar y corregir la estructura de la tabla eventos
    function verificarEstructuraTablaEventos($conn) {
        // Crear tabla eventos si no existe
        $sql_crear_tabla = "CREATE TABLE IF NOT EXISTS eventos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            descripcion TEXT,
            fecha_hora DATETIME NOT NULL,
            tipo ENUM('reto', 'evento', 'mision') DEFAULT 'evento',
            creado_por INT NOT NULL,
            fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (creado_por) REFERENCES usuarios(id) ON DELETE CASCADE
        )";
        
        if (!$conn->query($sql_crear_tabla)) {
            return false;
        }
        
        // Verificar y agregar columnas faltantes (nueva estructura extendida)
        $columnas_requeridas = [
            'fecha_hora' => "ALTER TABLE eventos ADD COLUMN fecha_hora DATETIME NOT NULL AFTER descripcion",
            'tipo' => "ALTER TABLE eventos ADD COLUMN tipo ENUM('reto', 'evento', 'mision') DEFAULT 'evento' AFTER fecha_hora",
            'creado_por' => "ALTER TABLE eventos ADD COLUMN creado_por INT NOT NULL AFTER tipo",
            'fecha_creacion' => "ALTER TABLE eventos ADD COLUMN fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER creado_por",
            // Nuevos campos para la personalización del mosaico
            'fecha_inicio' => "ALTER TABLE eventos ADD COLUMN fecha_inicio DATETIME NOT NULL AFTER descripcion",
            'fecha_fin' => "ALTER TABLE eventos ADD COLUMN fecha_fin DATETIME NULL AFTER fecha_inicio",
            'ubicacion' => "ALTER TABLE eventos ADD COLUMN ubicacion VARCHAR(255) NULL AFTER descripcion",
            'importante' => "ALTER TABLE eventos ADD COLUMN importante TINYINT(1) NOT NULL DEFAULT 0 AFTER ubicacion",
            // Nuevo campo: puntos otorgados (opcional)
            'puntos' => "ALTER TABLE eventos ADD COLUMN puntos INT NULL AFTER importante"
        ];

        foreach ($columnas_requeridas as $columna => $sql_alter) {
            $result = $conn->query("SHOW COLUMNS FROM eventos LIKE '$columna'");
            if ($result && $result->num_rows == 0) {
                if (!$conn->query($sql_alter)) {
                    return false;
                }
            }
        }

        // Asegurar que el ENUM de tipo tenga los nuevos valores
        $resultTipo = $conn->query("SHOW COLUMNS FROM eventos LIKE 'tipo'");
        if ($resultTipo && $row = $resultTipo->fetch_assoc()) {
            if (strpos($row['Type'] ?? '', "enum('reto','evento','mision')") === false && strpos($row['Type'] ?? '', "enum('reto', 'evento', 'mision')") === false) {
                // Mapear tipos antiguos a los nuevos antes de modificar el ENUM
                $conn->query("UPDATE eventos SET tipo='evento' WHERE tipo='reunion'");
                $conn->query("UPDATE eventos SET tipo='reto' WHERE tipo='tarea'");
                $conn->query("UPDATE eventos SET tipo='mision' WHERE tipo='recordatorio'");
                if (!$conn->query("ALTER TABLE eventos MODIFY COLUMN tipo ENUM('reto','evento','mision') DEFAULT 'evento'")) {
                    return false;
                }
            }
        }
        
        return true;
    }

    // Procesar creación de evento (soporta nuevos campos)
    // Subida de fotos para eventos (admin)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'subir_fotos_evento') {
        if (!isset($_SESSION['usuario_id'])) {
            echo json_encode(['success' => false, 'message' => 'No autenticado']);
            exit;
        }
        $evento_id = intval($_POST['evento_id'] ?? 0);
        if ($evento_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Evento inválido']);
            exit;
        }
        $uploadDir = __DIR__ . '/uploads/eventos/' . $evento_id;
        if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0775, true); }
        $permitidos = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $guardadas = [];
        if (!empty($_FILES['fotos']['name']) && is_array($_FILES['fotos']['name'])) {
            foreach ($_FILES['fotos']['name'] as $i => $name) {
                $tmp = $_FILES['fotos']['tmp_name'][$i] ?? null;
                $type = $_FILES['fotos']['type'][$i] ?? '';
                $size = $_FILES['fotos']['size'][$i] ?? 0;
                if (!$tmp || !isset($permitidos[$type])) { continue; }
                $ext = $permitidos[$type];
                $safe = preg_replace('/[^a-zA-Z0-9_-]+/', '-', pathinfo($name, PATHINFO_FILENAME));
                $fname = $safe . '-' . time() . '-' . $i . '.' . $ext;
                $dest = $uploadDir . '/' . $fname;
                if (move_uploaded_file($tmp, $dest)) {
                    $guardadas[] = 'uploads/eventos/' . $evento_id . '/' . $fname;
                }
            }
        }
        echo json_encode(['success' => true, 'fotos' => $guardadas]);
        exit;
    }

    // Crear evento
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crear_evento'])) {
        // Campos antiguos (compatibilidad)
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $fecha = $_POST['fecha'] ?? '';
        $hora = $_POST['hora'] ?? '';
        $tipo = strtolower(trim($_POST['tipo'] ?? 'evento'));
        $tipo_permitidos = ['reto','evento','mision'];
        if (!in_array($tipo, $tipo_permitidos)) { $tipo = 'evento'; }
        $creado_por = $_SESSION['usuario_id'];
        
        // Nuevos campos
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $hora_inicio = $_POST['hora_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? '';
        $hora_fin = $_POST['hora_fin'] ?? '';
        $ubicacion = trim($_POST['ubicacion'] ?? '');
        $importante = isset($_POST['importante']) ? (int)($_POST['importante'] ? 1 : 0) : 0;
        $puntos = (isset($_POST['puntos']) && $_POST['puntos'] !== '') ? intval($_POST['puntos']) : null;
        
        // Validación mínima
        if (empty($titulo)) {
            echo json_encode(['success' => false, 'message' => 'El título es obligatorio']);
            exit;
        }
        
        // Verificar y corregir estructura de la tabla
        if (!verificarEstructuraTablaEventos($conn)) {
            echo json_encode(['success' => false, 'message' => 'Error al verificar la estructura de la tabla de desafíos']);
            exit;
        }
        
        // Resolver fechas: preferir nuevos campos; si no, usar antiguos
        $fecha_inicio_dt = null;
        $fecha_fin_dt = null;
        
        if (!empty($fecha_inicio)) {
            $hora_inicio = $hora_inicio ?: '00:00';
            $fecha_inicio_dt = $fecha_inicio . ' ' . $hora_inicio . ':00';
        } elseif (!empty($fecha) && !empty($hora)) {
            $fecha_inicio_dt = $fecha . ' ' . $hora . ':00';
        }
        
        if (!empty($fecha_fin)) {
            $hora_fin = $hora_fin ?: '23:59';
            $fecha_fin_dt = $fecha_fin . ' ' . $hora_fin . ':00';
        }
        
        if (empty($fecha_inicio_dt)) {
            echo json_encode(['success' => false, 'message' => 'Debes proporcionar la fecha de inicio (y opcionalmente hora)']);
            exit;
        }
        
        // Validar que el usuario creador existe para evitar fallo de FK
        $chk = $conn->prepare('SELECT id FROM usuarios WHERE id = ? LIMIT 1');
        if ($chk) {
            $chk->bind_param('i', $creado_por);
            $chk->execute();
            $chk->store_result();
            if ($chk->num_rows === 0) {
                echo json_encode(['success' => false, 'message' => 'Usuario no válido. Inicia sesión nuevamente.']);
                $chk->close();
                exit;
            }
            $chk->close();
        }
        
        // Para compatibilidad, mantener fecha_hora igual a fecha_inicio
        $fecha_hora = $fecha_inicio_dt;
        
        // Insertar evento con nuevos campos
        $stmt = $conn->prepare('INSERT INTO eventos (titulo, descripcion, fecha_hora, tipo, creado_por, fecha_inicio, fecha_fin, ubicacion, importante, puntos) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        if (!$stmt) {
            echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta: ' . $conn->error]);
            exit;
        }
        
        $stmt->bind_param('ssssisssii', $titulo, $descripcion, $fecha_hora, $tipo, $creado_por, $fecha_inicio_dt, $fecha_fin_dt, $ubicacion, $importante, $puntos);
        
        if ($stmt->execute()) {
            $evento_id = $conn->insert_id;
            echo json_encode([
                'success' => true,
                'message' => 'Desafío creado correctamente',
                'evento_id' => $evento_id
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al crear el desafío: ' . $stmt->error]);
        }
        
        $stmt->close();
        exit;
    }

    // Endpoint nuevo: listar eventos completos (admin)
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['listar_eventos'])) {
        if (!verificarEstructuraTablaEventos($conn)) {
            echo json_encode(['success' => true, 'eventos' => []]);
            exit;
        }
        
        $sql = 'SELECT e.*, u.nombre as creador_nombre FROM eventos e JOIN usuarios u ON e.creado_por = u.id ORDER BY e.fecha_fin DESC, e.id DESC';
        $result = $conn->query($sql);
        $eventos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $eventos[] = $row;
            }
        }
        echo json_encode(['success' => true, 'eventos' => $eventos]);
        exit;
    }

    // Obtener eventos del mes
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['obtener_eventos'])) {
        $mes = intval($_GET['mes'] ?? date('n'));
        $año = intval($_GET['año'] ?? date('Y'));
        
        // Verificar y corregir estructura de la tabla
        if (!verificarEstructuraTablaEventos($conn)) {
            echo json_encode(['success' => true, 'eventos' => []]);
            exit;
        }
        
        $fecha_inicio = sprintf('%04d-%02d-01', $año, $mes);
        $fecha_fin = sprintf('%04d-%02d-%02d', $año, $mes, date('t', mktime(0, 0, 0, $mes, 1, $año)));
        
        $stmt = $conn->prepare('
            SELECT e.*, u.nombre as creador_nombre 
            FROM eventos e 
            JOIN usuarios u ON e.creado_por = u.id 
            WHERE DATE(e.fecha_hora) BETWEEN ? AND ? 
            ORDER BY e.fecha_hora ASC
        ');
        
        if ($stmt) {
            $stmt->bind_param('ss', $fecha_inicio, $fecha_fin);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $eventos = [];
            while ($row = $result->fetch_assoc()) {
                $fecha = date('Y-m-d', strtotime($row['fecha_hora']));
                if (!isset($eventos[$fecha])) {
                    $eventos[$fecha] = [];
                }
                $eventos[$fecha][] = $row;
            }
            
            echo json_encode(['success' => true, 'eventos' => $eventos]);
            $stmt->close();
        } else {
            echo json_encode(['success' => true, 'eventos' => []]);
        }
        exit;
    }

    // Obtener eventos de un día específico
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['obtener_eventos_dia'])) {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        
        // Verificar y corregir estructura de la tabla
        if (!verificarEstructuraTablaEventos($conn)) {
            echo json_encode(['success' => true, 'eventos' => []]);
            exit;
        }
        
        $stmt = $conn->prepare('
            SELECT e.*, u.nombre as creador_nombre 
            FROM eventos e 
            JOIN usuarios u ON e.creado_por = u.id 
            WHERE DATE(e.fecha_hora) = ? 
            ORDER BY e.fecha_hora ASC
        ');
        
        if ($stmt) {
            $stmt->bind_param('s', $fecha);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $eventos = [];
            while ($row = $result->fetch_assoc()) {
                $eventos[] = $row;
            }
            
            echo json_encode(['success' => true, 'eventos' => $eventos]);
            $stmt->close();
        } else {
            echo json_encode(['success' => true, 'eventos' => []]);
        }
        exit;
    }

    // Listar fotos de evento
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['listar_fotos_evento'])) {
        $evento_id = intval($_GET['evento_id'] ?? 0);
        $dir = __DIR__ . '/uploads/eventos/' . $evento_id;
        $fotos = [];
        if ($evento_id > 0 && is_dir($dir)) {
            $files = glob($dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
            foreach ($files as $f) {
                $fotos[] = str_replace(__DIR__ . '/', '', $f);
            }
        }
        echo json_encode(['success' => true, 'fotos' => $fotos]);
        exit;
    }

    // Eliminar evento
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'eliminar_evento') {
        $evento_id = intval($_POST['evento_id'] ?? 0);
        if ($evento_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            exit;
        }

        // Borrar carpeta de fotos del evento
        $dir = __DIR__ . '/uploads/eventos/' . $evento_id;
        if (is_dir($dir)) {
            $files = glob($dir . '/*');
            foreach ($files as $f) { if (is_file($f)) @unlink($f); }
            @rmdir($dir);
        }

        $stmt = $conn->prepare('DELETE FROM eventos WHERE id = ?');
        if ($stmt) {
            $stmt->bind_param('i', $evento_id);
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Error interno']);
        }
        exit;
    }

    // Obtener próximos eventos
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['obtener_proximos_eventos'])) {
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        
        // Verificar y corregir estructura de la tabla
        if (!verificarEstructuraTablaEventos($conn)) {
            echo json_encode(['success' => true, 'eventos' => []]);
            exit;
        }
        
        $stmt = $conn->prepare('
            SELECT e.*, u.nombre as creador_nombre 
            FROM eventos e 
            JOIN usuarios u ON e.creado_por = u.id 
            WHERE DATE(e.fecha_hora) >= ? 
            ORDER BY e.fecha_hora ASC 
            LIMIT 10
        ');
        
        if ($stmt) {
            $stmt->bind_param('s', $fecha);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $eventos = [];
            while ($row = $result->fetch_assoc()) {
                $eventos[] = $row;
            }
            
            echo json_encode(['success' => true, 'eventos' => $eventos]);
            $stmt->close();
        } else {
            echo json_encode(['success' => true, 'eventos' => []]);
        }
        exit;
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error interno del servidor']);
}
?>