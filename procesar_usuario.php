<?php
session_start();
require_once __DIR__ . '/conexion.php';

// Solo admin puede gestionar usuarios (crear/editar/inactivar)
if (!isset($_SESSION['usuario_id']) || ($_SESSION['rol'] ?? '') !== 'admin') {
    $_SESSION['mensaje_usuario'] = 'No autorizado.';
    $_SESSION['mensaje_tipo'] = 'error';
    header('Location: dashboard.php?view=usuarios-view');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear usuario
    if (isset($_POST['crear_usuario'])) {
        $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
        $email_usuario = trim($_POST['email_usuario'] ?? '');
        $password_usuario = $_POST['password_usuario'] ?? '';
        $rol_usuario = $_POST['rol_usuario'] ?? 'miembro';
        if (!$nombre_usuario || !$email_usuario || !$password_usuario) {
            $_SESSION['mensaje_usuario'] = 'Todos los campos son obligatorios.';
            $_SESSION['mensaje_tipo'] = 'error';
        } elseif (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensaje_usuario'] = 'El email no es válido.';
            $_SESSION['mensaje_tipo'] = 'error';
        } elseif (strlen($password_usuario) < 6) {
            $_SESSION['mensaje_usuario'] = 'La contraseña debe tener al menos 6 caracteres.';
            $_SESSION['mensaje_tipo'] = 'error';
        } else {
            // Validar que el email no exista
            $stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ?');
            $stmt->bind_param('s', $email_usuario);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $_SESSION['mensaje_usuario'] = 'El email ya está registrado. Usa otro email.';
                $_SESSION['mensaje_tipo'] = 'error';
            } else {
                $password_hash = password_hash($password_usuario, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('INSERT INTO usuarios (nombre, email, password_hash, rol, activo) VALUES (?, ?, ?, ?, 1)');
                $stmt->bind_param('ssss', $nombre_usuario, $email_usuario, $password_hash, $rol_usuario);
                if ($stmt->execute()) {
                    $_SESSION['mensaje_usuario'] = 'Usuario creado correctamente.';
                    $_SESSION['mensaje_tipo'] = 'exito';
                } else {
                    $_SESSION['mensaje_usuario'] = 'Error al crear el usuario: ' . htmlspecialchars($stmt->error);
                    $_SESSION['mensaje_tipo'] = 'error';
                }
            }
        }
    }

    // Editar usuario
    elseif (isset($_POST['editar_usuario'])) {
        $usuario_id = intval($_POST['usuario_id'] ?? 0);
        $nombre_usuario = trim($_POST['nombre_usuario'] ?? '');
        $email_usuario = trim($_POST['email_usuario'] ?? '');
        $password_usuario = $_POST['password_usuario'] ?? '';
        $rol_usuario = $_POST['rol_usuario'] ?? 'miembro';
        $activo = isset($_POST['activo']) ? (int)$_POST['activo'] : 1;

        if (!$usuario_id || !$nombre_usuario || !$email_usuario) {
            $_SESSION['mensaje_usuario'] = 'Datos incompletos para editar.';
            $_SESSION['mensaje_tipo'] = 'error';
        } elseif (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensaje_usuario'] = 'El email no es válido.';
            $_SESSION['mensaje_tipo'] = 'error';
        } else {
            // Verificar email único para otros usuarios
            $stmt = $conn->prepare('SELECT id FROM usuarios WHERE email = ? AND id <> ?');
            $stmt->bind_param('si', $email_usuario, $usuario_id);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $_SESSION['mensaje_usuario'] = 'El email ya está en uso por otro usuario.';
                $_SESSION['mensaje_tipo'] = 'error';
            } else {
                if ($password_usuario !== '') {
                    if (strlen($password_usuario) < 6) {
                        $_SESSION['mensaje_usuario'] = 'La nueva contraseña debe tener al menos 6 caracteres.';
                        $_SESSION['mensaje_tipo'] = 'error';
                    } else {
                        $password_hash = password_hash($password_usuario, PASSWORD_DEFAULT);
                        $stmt = $conn->prepare('UPDATE usuarios SET nombre = ?, email = ?, password_hash = ?, rol = ?, activo = ? WHERE id = ?');
                        $stmt->bind_param('ssssii', $nombre_usuario, $email_usuario, $password_hash, $rol_usuario, $activo, $usuario_id);
                        if ($stmt->execute()) {
                            $_SESSION['mensaje_usuario'] = 'Usuario actualizado correctamente.';
                            $_SESSION['mensaje_tipo'] = 'exito';
                        } else {
                            $_SESSION['mensaje_usuario'] = 'Error al actualizar: ' . htmlspecialchars($stmt->error);
                            $_SESSION['mensaje_tipo'] = 'error';
                        }
                    }
                } else {
                    $stmt = $conn->prepare('UPDATE usuarios SET nombre = ?, email = ?, rol = ?, activo = ? WHERE id = ?');
                    $stmt->bind_param('sssii', $nombre_usuario, $email_usuario, $rol_usuario, $activo, $usuario_id);
                    if ($stmt->execute()) {
                        $_SESSION['mensaje_usuario'] = 'Usuario actualizado correctamente.';
                        $_SESSION['mensaje_tipo'] = 'exito';
                    } else {
                        $_SESSION['mensaje_usuario'] = 'Error al actualizar: ' . htmlspecialchars($stmt->error);
                        $_SESSION['mensaje_tipo'] = 'error';
                    }
                }
            }
        }
    }

    // Inactivar usuario (soft delete)
    elseif (isset($_POST['inactivar_usuario'])) {
        $usuario_id = intval($_POST['usuario_id'] ?? 0);
        if ($usuario_id) {
            $stmt = $conn->prepare('UPDATE usuarios SET activo = 0 WHERE id = ?');
            $stmt->bind_param('i', $usuario_id);
            if ($stmt->execute()) {
                $_SESSION['mensaje_usuario'] = 'Usuario inactivado correctamente.';
                $_SESSION['mensaje_tipo'] = 'exito';
            } else {
                $_SESSION['mensaje_usuario'] = 'No se pudo inactivar el usuario.';
                $_SESSION['mensaje_tipo'] = 'error';
            }
        }
    }
}

header('Location: dashboard.php?view=usuarios-view');
exit;