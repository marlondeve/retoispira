<?php
// API pública para obtener datos de participantes (solo lectura)
// No requiere autenticación para mostrar en landing page

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Solo permitir método GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'database';
$dataFile = $dataDir . DIRECTORY_SEPARATOR . 'participantes.json';

// Fallback si no existe database/
if (!file_exists($dataFile)) {
    $dataFile = __DIR__ . DIRECTORY_SEPARATOR . 'participantes.json';
}

// Función para cargar participantes (solo lectura)
function load_participantes_publico($file) {
    if (!file_exists($file)) {
        return ['items' => []];
    }
    
    $raw = file_get_contents($file);
    if ($raw === false || $raw === '') {
        return ['items' => []];
    }
    
    $decoded = json_decode($raw, true);
    if (!is_array($decoded)) {
        return ['items' => []];
    }
    
    if (!isset($decoded['items']) || !is_array($decoded['items'])) {
        $decoded['items'] = [];
    }
    
    return $decoded;
}

try {
    $data = load_participantes_publico($dataFile);
    $items = $data['items'];
    
    // Solo devolver datos públicos necesarios para la landing
    $publicItems = [];
    foreach ($items as $item) {
        $publicItems[] = [
            'id' => $item['id'] ?? '',
            'nombre' => $item['nombre'] ?? '',
            'organizacion' => $item['organizacion'] ?? '',
            'puntos' => intval($item['puntos'] ?? 0)
        ];
    }
    
    echo json_encode([
        'success' => true, 
        'items' => $publicItems,
        'total' => count($publicItems)
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Error al cargar datos',
        'error' => $e->getMessage()
    ]);
}
?>
