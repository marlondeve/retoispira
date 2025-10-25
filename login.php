<?php
session_start();
$mensaje = '';

// Usar la conexión centralizada
require_once __DIR__ . '/conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $stmt = $conn->prepare('SELECT id, nombre, password_hash, rol, activo FROM usuarios WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $nombre, $password_hash, $rol, $activo);
            $stmt->fetch();
            if (!$activo) {
                $mensaje = 'Tu cuenta está inactiva. Contacta al administrador.';
            } elseif (password_verify($password, $password_hash)) {
                $_SESSION['usuario_id'] = $id;
                $_SESSION['nombre'] = $nombre;
                $_SESSION['rol'] = $rol;
                header('Location: dashboard.php');
                exit;
            } else {
                $mensaje = 'Contraseña incorrecta.';
            }
        } else {
            $mensaje = 'Usuario no encontrado.';
        }
        $stmt->close();
    } else {
        $mensaje = 'Ingresa email y contraseña.';
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Acceso Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; }
        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 20px 35px rgba(0,0,0,0.15); }
        .gradient-primary { background: linear-gradient(135deg, #fe6901 0%, #ff8c42 100%); }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-50 p-4">
    <div class="w-full max-w-md">
        <div class="relative overflow-hidden rounded-3xl shadow-2xl p-8 gradient-primary text-white hover-lift">
            <div class="absolute -top-6 -right-6 w-24 h-24 rounded-full opacity-20 pointer-events-none" style="background: linear-gradient(45deg, #ffffff, transparent);"></div>
            <div class="absolute -bottom-6 -left-6 w-32 h-32 rounded-full opacity-10 pointer-events-none" style="background: linear-gradient(45deg, #ffffff, transparent);"></div>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-shield text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold">Acceso Administrador</h1>
            </div>
            <p class="opacity-90 mb-6">Inicia sesión para gestionar participantes, eventos y finanzas en tu panel.</p>
            <?php if ($mensaje !== ''): ?>
                <div class="mb-4 bg-white text-red-700 rounded-xl px-4 py-3 shadow">
                    <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($mensaje); ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="login.php" class="space-y-4">
                <div>
                    <label class="block text-white font-semibold mb-2">Correo electrónico</label>
                    <input type="email" name="email" required placeholder="tu@correo.com" class="w-full rounded-xl px-4 py-3 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500" />
                </div>
                <div>
                    <label class="block text-white font-semibold mb-2">Contraseña</label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full rounded-xl px-4 py-3 bg-white text-gray-800 focus:outline-none focus:ring-2 focus:ring-orange-500" />
                </div>
                <button type="submit" class="w-full mt-2 px-8 py-4 rounded-xl font-bold border-2 border-white text-white transition-all hover:bg-white" style="--hover-color: #fe6901;" onmouseover="this.style.color='var(--hover-color)';" onmouseout="this.style.color='white';">
                    <i class="fas fa-sign-in-alt mr-2"></i>Entrar
                </button>
            </form>
            <div class="mt-6 text-sm relative z-10">
                <a href="index.php" class="underline text-white hover:text-orange-100"><i class="fas fa-arrow-left mr-2"></i>Volver al inicio</a>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js" defer></script>
</body>
</html>