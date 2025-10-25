<?php
session_start();
$mensaje = '';
$correo = '';

function cargar_participantes($ruta) {
    if (!file_exists($ruta)) return ['items' => []];
    $json = file_get_contents($ruta);
    $data = json_decode($json, true);
    return is_array($data) ? $data : ['items' => []];
}

function obtener_ultimos_rayos_ganados($participante) {
    $hist = $participante['historial_puntos'] ?? [];
    for ($i = count($hist) - 1; $i >= 0; $i--) {
        $h = $hist[$i];
        $p = intval($h['puntos'] ?? 0);
        $op = $h['operacion'] ?? '';
        if ($p > 0 && ($op === 'asignar' || $op === '')) {
            return $p;
        }
    }
    return 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo'] ?? '');
    if ($correo === '') {
        $mensaje = 'Por favor ingresa tu correo.';
    } else {
        $data = cargar_participantes(__DIR__ . '/participantes.json');
        $encontrado = null;
        foreach ($data['items'] as $p) {
            $pc = strtolower(trim($p['correo'] ?? ''));
            if ($pc !== '' && $pc === strtolower($correo)) { $encontrado = $p; break; }
        }
        if ($encontrado) {
            $_SESSION['participante_id'] = $encontrado['id'];
            $_SESSION['participante_nombre'] = $encontrado['nombre'];
            $_SESSION['participante_correo'] = $encontrado['correo'];
            setcookie('part_id', $encontrado['id'], time() + 60*60*24*180, '/', '', false, false);
            setcookie('part_nombre', $encontrado['nombre'], time() + 60*60*24*180, '/', '', false, false);
            setcookie('part_correo', $encontrado['correo'], time() + 60*60*24*180, '/', '', false, false);
            $rayos = obtener_ultimos_rayos_ganados($encontrado);
            $hist = $encontrado['historial_puntos'] ?? [];
            $cele = '';
            for ($i = count($hist) - 1; $i >= 0; $i--) {
                $h = $hist[$i];
                $p = intval($h['puntos'] ?? 0);
                $op = $h['operacion'] ?? '';
                if ($p > 0 && ($op === 'asignar' || $op === '')) { $cele = (string)($h['fecha'] ?? ''); break; }
            }
            $qs = 'rayos=' . urlencode($rayos);
            if ($cele !== '') { $qs .= '&cele=' . urlencode($cele); }
            header('Location: index.php?' . $qs . '#estrella');
            exit;
        } else {
            $mensaje = 'No encontramos tu correo. Verifica o regístrate.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="LOGOTIPO_light.png" type="image/png">
    <title>Acceso Participantes</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; } </style>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="relative overflow-hidden rounded-3xl shadow-2xl p-8" style="background: linear-gradient(135deg, #fe6901 0%, #ff8c42 100%);">
            <!-- Decorativos -->
            <div class="absolute -top-4 -right-4 w-24 h-24 rounded-full opacity-20 pointer-events-none" style="background: linear-gradient(45deg, #ffffff, transparent);"></div>
            <div class="absolute -bottom-4 -left-4 w-32 h-32 rounded-full opacity-10 pointer-events-none" style="background: linear-gradient(45deg, #ffffff, transparent);"></div>

            <div class="relative z-10 flex flex-col items-center mb-8">
                <div class="w-20 h-20 rounded-3xl flex items-center justify-center mb-4 bg-white/20 backdrop-blur-sm">
                    <img src="logo.png" alt="Reto Inspira Logo" class="w-20 h-20 object-contain">
                </div>
                <h1 class="text-3xl font-bold text-white mb-2 tracking-tight">Reto Inspira</h1>
                <p class="text-white/90 text-sm font-medium">Acceso de Participantes</p>
            </div>

            <?php if ($mensaje): ?>
                <div class="relative z-10 bg-red-50/90 backdrop-blur-sm border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-start shadow-lg">
                    <i class="fas fa-exclamation-circle w-5 h-5 mr-2 mt-0.5"></i>
                    <span class="font-medium"><?php echo htmlspecialchars($mensaje); ?></span>
                </div>
            <?php endif; ?>

            <form method="post" class="relative z-10 space-y-6" autocomplete="on">
                <div>
                    <label class="block text-white font-semibold mb-2 text-sm">Correo</label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-white/70 group-focus-within:text-white transition-colors">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="correo" value="<?php echo htmlspecialchars($correo); ?>" required placeholder="tu@correo.com" class="w-full bg-/10 backdrop-blur-sm border border-white/20 rounded-xl pl-12 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-white/50 transition-all placeholder-white/60 text-gray font-medium" />
                    </div>
                </div>
                <button type="submit" class="w-full px-8 py-4 rounded-xl font-bold border-2 border-white text-white transition-all hover:bg-white" style="--hover-color: #fe6901;" onmouseover="this.style.color='var(--hover-color)';" onmouseout="this.style.color='white';">
                    <span class="inline-flex items-center justify-center"><i class="fas fa-sign-in-alt mr-2"></i>Entrar</span>
                </button>
            </form>
            <div class="relative z-10 mt-8 text-center text-white/70 text-xs font-medium">
                ¿Eres administrador? <a href="login.php" class="text-white hover:underline font-semibold">Accede aquí</a>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>