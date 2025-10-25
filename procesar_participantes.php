<?php
session_start();
// Evitar que warnings/notice rompan el JSON de salida
@ini_set('display_errors', '0');
@error_reporting(0);
header('Content-Type: application/json; charset=utf-8');

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'database';
$dataFile = $dataDir . DIRECTORY_SEPARATOR . 'participantes.json';

// Resolver ruta de almacenamiento con fallback si no es posible crear la carpeta database/
if (!is_dir($dataDir)) {
    if (!@mkdir($dataDir, 0775, true)) {
        // Fallback a raíz del proyecto
        $dataDir = __DIR__;
        $dataFile = $dataDir . DIRECTORY_SEPARATOR . 'participantes.json';
    }
}

// Cargar datos actuales
function load_participantes($file)
{
    if (!file_exists($file)) {
        return [ 'items' => [] ];
    }
    $raw = file_get_contents($file);
    if ($raw === false || $raw === '') {
        return [ 'items' => [] ];
    }
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        return [ 'items' => [] ];
    }
    if (!isset($decoded['items']) || !is_array($decoded['items'])) {
        $decoded['items'] = [];
    }
    
    // Migrar participantes que no tengan estructura de puntos
    $needsSave = false;
    foreach ($decoded['items'] as &$item) {
        if (!isset($item['puntos'])) {
            $item['puntos'] = 0;
            $needsSave = true;
        }
        if (!isset($item['historial_puntos'])) {
            $item['historial_puntos'] = [];
            $needsSave = true;
        }
    }
    unset($item);
    
    // Guardar si se hicieron cambios de migración (solo en modo escritura)
    if ($needsSave && is_writable(dirname($file))) {
        save_participantes($file, $decoded);
    }
    
    return $decoded;
}

function save_participantes($file, $data)
{
    $fp = fopen($file, 'c+');
    if (!$fp) return false;
    // Bloqueo para concurrencia simple
    if (!flock($fp, LOCK_EX)) {
        fclose($fp);
        return false;
    }
    // Truncar y escribir
    ftruncate($fp, 0);
    rewind($fp);
    $ok = fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
    return $ok !== false;
}

// Función para validar correo electrónico
function validar_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Función para verificar duplicidad de correo
function existe_correo($items, $correo, $excluir_id = null) {
    $correo = strtolower(trim($correo));
    foreach ($items as $item) {
        if ($excluir_id && ($item['id'] ?? '') === $excluir_id) {
            continue; // No comparar consigo mismo en caso de actualización
        }
        if (strtolower(trim($item['correo'] ?? '')) === $correo) {
            return true;
        }
    }
    return false;
}

$method = $_SERVER['REQUEST_METHOD'];
$rolSesion = $_SESSION['rol'] ?? '';
$usuarioSesion = intval($_SESSION['usuario_id'] ?? 0);

if ($method === 'GET') {
    $data = load_participantes($dataFile);
    $items = $data['items'];
    
    // Filtros opcionales
    $buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
    $organizacion = isset($_GET['organizacion']) ? trim($_GET['organizacion']) : '';
    
    // Aplicar filtros
    if ($buscar !== '' || $organizacion !== '') {
        $items = array_values(array_filter($items, function ($it) use ($buscar, $organizacion) {
            $ok = true;
            
            // Filtro de búsqueda por nombre o correo
            if ($buscar !== '') {
                $buscarLower = strtolower($buscar);
                $nombre = strtolower($it['nombre'] ?? '');
                $correo = strtolower($it['correo'] ?? '');
                $ok = $ok && (strpos($nombre, $buscarLower) !== false || strpos($correo, $buscarLower) !== false);
            }
            
            // Filtro por organización
            if ($organizacion !== '') {
                $ok = $ok && (($it['organizacion'] ?? '') === $organizacion);
            }
            
            return $ok;
        }));
    }
    
    echo json_encode(['success' => true, 'items' => $items]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    $store = load_participantes($dataFile);
    $items = $store['items'];

    if ($action === 'add') {
        // Validar permisos de escritura antes de intentar guardar
        if (file_exists($dataFile) && !is_writable($dataFile)) {
            echo json_encode(['success' => false, 'message' => 'El archivo no es escribible: ' . basename($dataFile)]);
            exit;
        }
        if (!file_exists($dataFile) && !is_writable($dataDir)) {
            echo json_encode(['success' => false, 'message' => 'El directorio no es escribible: ' . basename($dataDir)]);
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $organizacion = trim($_POST['organizacion'] ?? '');
        $cargo = trim($_POST['cargo'] ?? '');

        // Validaciones
        if ($nombre === '') {
            echo json_encode(['success' => false, 'message' => 'El nombre es requerido']);
            exit;
        }
        
        if ($correo === '') {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico es requerido']);
            exit;
        }
        
        if (!validar_email($correo)) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido']);
            exit;
        }
        
        // Verificar duplicidad de correo
        if (existe_correo($items, $correo)) {
            echo json_encode(['success' => false, 'message' => 'Ya existe un participante registrado con este correo electrónico']);
            exit;
        }

        $id = uniqid('part_', true);
        $items[] = [
            'id' => $id,
            'nombre' => $nombre,
            'correo' => $correo,
            'telefono' => $telefono,
            'organizacion' => $organizacion,
            'cargo' => $cargo,
            'puntos' => 0,
            'historial_puntos' => [],
            'creado_por' => $usuarioSesion,
            'creado_en' => date('c')
        ];
        
        $store['items'] = $items;
        if (!save_participantes($dataFile, $store)) {
            $perm = @substr(sprintf('%o', @fileperms($dataFile)), -4);
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar. Verifica permisos en database/ (perm=' . ($perm ?: 'n/a') . ')']);
            exit;
        }
        echo json_encode(['success' => true, 'id' => $id]);
        exit;
    }

    if ($action === 'update') {
        $id = trim($_POST['id'] ?? '');
        if ($id === '') {
            echo json_encode(['success' => false, 'message' => 'ID requerido']);
            exit;
        }
        
        $nombre = trim($_POST['nombre'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $organizacion = trim($_POST['organizacion'] ?? '');
        $cargo = trim($_POST['cargo'] ?? '');

        // Validaciones
        if ($nombre === '') {
            echo json_encode(['success' => false, 'message' => 'El nombre es requerido']);
            exit;
        }
        
        if ($correo === '') {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico es requerido']);
            exit;
        }
        
        if (!validar_email($correo)) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico no es válido']);
            exit;
        }
        
        // Verificar duplicidad de correo (excluyendo el registro actual)
        if (existe_correo($items, $correo, $id)) {
            echo json_encode(['success' => false, 'message' => 'Ya existe otro participante registrado con este correo electrónico']);
            exit;
        }

        $found = false;
        foreach ($items as &$it) {
            if (($it['id'] ?? '') === $id) {
                // Solo admin puede editar cualquier registro, usuarios normales solo pueden editar los suyos
                if ($rolSesion !== 'admin') {
                    $creador = intval($it['creado_por'] ?? 0);
                    if ($creador !== $usuarioSesion) {
                        echo json_encode(['success' => false, 'message' => 'No autorizado para editar este registro']);
                        exit;
                    }
                }
                
                $it['nombre'] = $nombre;
                $it['correo'] = $correo;
                $it['telefono'] = $telefono;
                $it['organizacion'] = $organizacion;
                $it['cargo'] = $cargo;
                $it['actualizado_en'] = date('c');
                $found = true;
                break;
            }
        }
        unset($it);
        
        if (!$found) {
            echo json_encode(['success' => false, 'message' => 'Participante no encontrado']);
            exit;
        }
        
        $store['items'] = $items;
        if (!save_participantes($dataFile, $store)) {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar cambios']);
            exit;
        }
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'delete') {
        $id = $_POST['id'] ?? '';
        $prev = count($items);
        $items = array_values(array_filter($items, function ($it) use ($id, $rolSesion, $usuarioSesion) {
            if (($it['id'] ?? '') !== $id) return true;
            
            // Solo admin puede eliminar cualquier registro, usuarios normales solo pueden eliminar los suyos
            if ($rolSesion === 'admin') return false;
            
            $creador = intval($it['creado_por'] ?? 0);
            return $creador !== $usuarioSesion; // si no es creador, no eliminar
        }));
        
        if ($prev === count($items)) {
            echo json_encode(['success' => false, 'message' => 'No existe el participante o no tienes permisos para eliminarlo']);
            exit;
        }
        
        $store['items'] = $items;
        if (!save_participantes($dataFile, $store)) {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar']);
            exit;
        }
        echo json_encode(['success' => true]);
        exit;
    }
    
    if ($action === 'assign_points') {

        $participante_id = trim($_POST['participante_id'] ?? '');
        $puntos = intval($_POST['puntos'] ?? 0);
        $motivo = trim($_POST['motivo'] ?? '');
        $operacion = trim($_POST['operacion'] ?? 'asignar');
        
        if ($participante_id === '') {
            echo json_encode(['success' => false, 'message' => 'ID de participante requerido']);
            exit;
        }
        
        if ($puntos <= 0) {
            echo json_encode(['success' => false, 'message' => 'La cantidad de puntos debe ser mayor a 0']);
            exit;
        }
        
        $found = false;
        foreach ($items as &$it) {
            if (($it['id'] ?? '') === $participante_id) {
                // Asegurar que el participante tenga la estructura de puntos
                if (!isset($it['puntos'])) {
                    $it['puntos'] = 0;
                }
                if (!isset($it['historial_puntos'])) {
                    $it['historial_puntos'] = [];
                }
                
                $puntosActuales = intval($it['puntos']);
                
                // Aplicar operación según el tipo seleccionado
                if ($operacion === 'restar') {
                    // Verificar que no se resten más puntos de los que tiene
                    if ($puntos > $puntosActuales) {
                        echo json_encode(['success' => false, 'message' => 'No se pueden restar más puntos de los que tiene el participante']);
                        exit;
                    }
                    $it['puntos'] = $puntosActuales - $puntos;
                    $puntosHistorial = -$puntos; // Guardar como negativo en el historial
                } else {
                    // Asignar puntos (sumar)
                    $it['puntos'] = $puntosActuales + $puntos;
                    $puntosHistorial = $puntos;
                }
                
                // Guardar historial de puntos
                $it['historial_puntos'][] = [
                    'puntos' => $puntosHistorial,
                    'operacion' => $operacion,
                    'motivo' => $motivo,
                    'fecha' => date('c'),
                    'asignado_por' => $usuarioSesion
                ];
                
                $it['actualizado_en'] = date('c');
                $found = true;
                break;
            }
        }
        unset($it);
        
        if (!$found) {
            echo json_encode(['success' => false, 'message' => 'Participante no encontrado']);
            exit;
        }
        
        $store['items'] = $items;
        if (!save_participantes($dataFile, $store)) {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar los puntos']);
            exit;
        }
        echo json_encode(['success' => true, 'message' => 'Puntos asignados correctamente']);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Acción no soportada']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Método no permitido']);
exit;
