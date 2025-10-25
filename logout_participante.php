<?php
session_start();
// Obtener el id de participante si existe
$partId = isset($_COOKIE['part_id']) ? $_COOKIE['part_id'] : '';
// Borrar cookies de participante
$expire = time() - 3600;
setcookie('part_id', '', $expire, '/', '', false, false);
setcookie('part_nombre', '', $expire, '/', '', false, false);
setcookie('part_correo', '', $expire, '/', '', false, false);
// Borrar cookie de celebración si existe
if ($partId !== '') {
    $celeKey = 'cele_part_' . $partId;
    setcookie($celeKey, '', $expire, '/', '', false, false);
}
// Limpiar variables de sesión relacionadas
unset($_SESSION['participante_id'], $_SESSION['participante_nombre'], $_SESSION['participante_correo']);
// Redirigir al inicio
header('Location: index.php');
exit;