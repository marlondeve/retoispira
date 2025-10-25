<?php
session_start();
// Evitar que warnings/notice rompan el JSON de salida
@ini_set('display_errors', '0');
@error_reporting(0);
header('Content-Type: application/json; charset=utf-8');

// Solo admin
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'database';
$dataFile = $dataDir . DIRECTORY_SEPARATOR . 'finanzas.json';

// Resolver ruta de almacenamiento con fallback si no es posible crear la carpeta database/
if (!is_dir($dataDir)) {
    if (!@mkdir($dataDir, 0775, true)) {
        // Fallback a raíz del proyecto
        $dataDir = __DIR__;
        $dataFile = $dataDir . DIRECTORY_SEPARATOR . 'finanzas.json';
    }
}

// Cargar datos actuales
function load_finanzas($file)
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
    return $decoded;
}

function save_finanzas($file, $data)
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

$method = $_SERVER['REQUEST_METHOD'];
$rolSesion = $_SESSION['rol'] ?? '';
$usuarioSesion = intval($_SESSION['usuario_id'] ?? 0);

if ($method === 'GET') {
    $data = load_finanzas($dataFile);
    // Filtros opcionales
    $mes = isset($_GET['mes']) ? trim($_GET['mes']) : '';
    $anio = isset($_GET['anio']) ? trim($_GET['anio']) : '';
    $tipo = isset($_GET['tipo']) ? trim($_GET['tipo']) : '';

    $proyeccion = isset($_GET['proyeccion']) ? trim($_GET['proyeccion']) : '';
    $usuarioIdFiltro = isset($_GET['usuario_id']) ? intval($_GET['usuario_id']) : 0;

    $items = $data['items'];
    // Para no-admin, si proyección es usuario, filtrar por el mismo usuario; si es empresa, también filtrar por su usuario para evitar ver datos de otros
    if ($rolSesion !== 'admin') {
        $usuarioIdFiltro = $usuarioSesion;
        $proyeccion = 'usuario';
    }
    $items = array_values(array_filter($items, function ($it) use ($mes, $anio, $tipo, $proyeccion, $usuarioIdFiltro, $rolSesion) {
        $ok = true;
        if ($mes !== '') $ok = $ok && (sprintf('%02d', intval($it['mes'])) === sprintf('%02d', intval($mes)));
        if ($anio !== '') $ok = $ok && ((string)($it['anio'] ?? '') === (string)intval($anio));
        if ($tipo !== '') $ok = $ok && (($it['tipo'] ?? '') === $tipo);
        if ($proyeccion === 'usuario') {
            $owner = isset($it['usuario_id']) ? intval($it['usuario_id']) : intval($it['creado_por'] ?? 0);
            if ($usuarioIdFiltro > 0) {
                $ok = $ok && ($owner === $usuarioIdFiltro);
            }
        } else {
            // Empresa: para no-admin no mostrar registros de otros usuarios
            if ($rolSesion !== 'admin') {
                $owner = isset($it['usuario_id']) ? intval($it['usuario_id']) : intval($it['creado_por'] ?? 0);
                $ok = $ok && ($owner === $usuarioIdFiltro);
            }
        }
        return $ok;
    }));

    echo json_encode(['success' => true, 'items' => $items]);
    exit;
}

if ($method === 'POST') {
    $action = $_POST['action'] ?? '';
    $store = load_finanzas($dataFile);
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

        $tipo = trim($_POST['tipo'] ?? ''); // ingreso | gasto | costo | otro
        $categoria = trim($_POST['categoria'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        // Normalizar monto: quitar separador de miles y usar punto como decimal
        $montoRaw = (string)($_POST['monto'] ?? '0');
        $montoNorm = str_replace(["\xC2\xA0", ' '], '', $montoRaw); // espacios normales y NBSP
        $montoNorm = str_replace('.', '', $montoNorm);
        $montoNorm = str_replace(',', '.', $montoNorm);
        $monto = floatval($montoNorm);
        $mes = intval($_POST['mes'] ?? date('m'));
        $anio = intval($_POST['anio'] ?? date('Y'));
        // El propietario siempre es el usuario de sesión
        $usuarioId = $usuarioSesion;

        $tiposPermitidos = ['ingreso','gasto','costo','otro'];
        if (!in_array($tipo, $tiposPermitidos, true)) {
            echo json_encode(['success' => false, 'message' => 'Tipo no válido']);
            exit;
        }
        if (!is_finite($monto) || $monto === 0) {
            echo json_encode(['success' => false, 'message' => 'Monto inválido o cero']);
            exit;
        }

        $id = uniqid('fin_', true);
        $items[] = [
            'id' => $id,
            'tipo' => $tipo,
            'categoria' => $categoria,
            'descripcion' => $descripcion,
            'monto' => $monto,
            'mes' => $mes,
            'anio' => $anio,
            'creado_por' => $usuarioSesion ?: null,
            'usuario_id' => $usuarioId ?: ($usuarioSesion ?: null),
            'creado_en' => date('c')
        ];
        $store['items'] = $items;
        if (!save_finanzas($dataFile, $store)) {
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
        $tipo = trim($_POST['tipo'] ?? '');
        $categoria = trim($_POST['categoria'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $montoRaw = (string)($_POST['monto'] ?? '0');
        $montoNorm = str_replace(["\xC2\xA0", ' '], '', $montoRaw);
        $montoNorm = str_replace('.', '', $montoNorm);
        $montoNorm = str_replace(',', '.', $montoNorm);
        $monto = floatval($montoNorm);
        $mes = intval($_POST['mes'] ?? date('m'));
        $anio = intval($_POST['anio'] ?? date('Y'));
        // Usuario dueño no se cambia desde el update

        $found = false;
        foreach ($items as &$it) {
            if (($it['id'] ?? '') === $id) {
                // Permisos: no-admin solo puede editar sus propios registros
                if ($rolSesion !== 'admin') {
                    $owner = isset($it['usuario_id']) ? intval($it['usuario_id']) : intval($it['creado_por'] ?? 0);
                    if ($owner !== $usuarioSesion) {
                        echo json_encode(['success' => false, 'message' => 'No autorizado para editar este registro']);
                        exit;
                    }
                }
                $it['tipo'] = $tipo !== '' ? $tipo : ($it['tipo'] ?? 'otro');
                $it['categoria'] = $categoria;
                $it['descripcion'] = $descripcion;
                $it['monto'] = $monto;
                $it['mes'] = $mes;
                $it['anio'] = $anio;
                if ($rolSesion === 'admin') {
                    if ($usuarioId > 0) { $it['usuario_id'] = $usuarioId; }
                } else {
                    $it['usuario_id'] = $usuarioSesion;
                }
                $it['actualizado_en'] = date('c');
                $found = true;
                break;
            }
        }
        unset($it);
        if (!$found) {
            echo json_encode(['success' => false, 'message' => 'Registro no encontrado']);
            exit;
        }
        $store['items'] = $items;
        if (!save_finanzas($dataFile, $store)) {
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
            if ($rolSesion === 'admin') return false;
            $owner = isset($it['usuario_id']) ? intval($it['usuario_id']) : intval($it['creado_por'] ?? 0);
            return $owner !== $usuarioSesion; // si no es dueño, no eliminar
        }));
        if ($prev === count($items)) {
            echo json_encode(['success' => false, 'message' => 'No existe el elemento']);
            exit;
        }
        $store['items'] = $items;
        if (!save_finanzas($dataFile, $store)) {
            echo json_encode(['success' => false, 'message' => 'No se pudo guardar']);
            exit;
        }
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Acción no soportada']);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Método no permitido']);
exit;

