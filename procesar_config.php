<?php
session_start();
header('Content-Type: application/json');

$accion = $_POST['accion'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $accion === 'cambiar_foto_grupo') {
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['success' => false, 'message' => 'No autenticado']);
        exit;
    }
    $rol = $_SESSION['rol'] ?? '';
    if ($rol !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'No autorizado']);
        exit;
    }

    if (!isset($_FILES['foto_grupo']) || !is_uploaded_file($_FILES['foto_grupo']['tmp_name'])) {
        echo json_encode(['success' => false, 'message' => 'Archivo no enviado']);
        exit;
    }

    $file = $_FILES['foto_grupo'];
    $type = $file['type'] ?? '';
    $size = intval($file['size'] ?? 0);
    $tmp = $file['tmp_name'] ?? '';

    // Solo JPG por compatibilidad con index.php
    $permitidos = ['image/jpeg' => 'jpg', 'image/pjpeg' => 'jpg'];
    if (!isset($permitidos[$type])) {
        echo json_encode(['success' => false, 'message' => 'Formato inválido. Solo se permite JPG']);
        exit;
    }
    if (!$tmp) {
        echo json_encode(['success' => false, 'message' => 'Archivo inválido']);
        exit;
    }

    $dest = __DIR__ . '/foto-grupo.jpg';

    // Eliminar anterior si existe
    if (file_exists($dest)) {
        @unlink($dest);
    }

    // Guardar nueva imagen
    if (!move_uploaded_file($tmp, $dest)) {
        echo json_encode(['success' => false, 'message' => 'No se pudo guardar la imagen']);
        exit;
    }

    @chmod($dest, 0664);

    echo json_encode(['success' => true, 'path' => 'foto-grupo.jpg']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Acción inválida']);