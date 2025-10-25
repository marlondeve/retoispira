<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="LOGOTIPO_light.png" type="image/png">
    <title>Reto Inspira</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Animaciones personalizadas */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-fade-in-up { animation: fadeInUp 0.6s ease-out; }
        .animate-fade-in-left { animation: fadeInLeft 0.6s ease-out; }
        .animate-fade-in-right { animation: fadeInRight 0.6s ease-out; }
        .animate-pulse-slow { animation: pulse 2s infinite; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        
        /* Gradientes modernos */
        .gradient-primary {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        }
        
        .gradient-secondary {
            background: linear-gradient(135deg, #ff8c42 0%, #ff6b35 100%);
        }
        
        .gradient-success {
            background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
        }
        
        .gradient-hero {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #ff8c42 100%);
        }
        
        /* Efectos hover */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
        /* Cards modernas */
        .card-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-modern:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /*Eventos*/
        .event-card-important { box-shadow: 0 0 25px rgba(255, 137, 0, 0.45); }
        .event-card-locked { filter: blur(6px) grayscale(30%); opacity: 0.85; }
        .card-modern:hover .event-content.event-card-locked { filter: none; opacity: 1; }
        .card-modern:hover .event-lock-overlay { opacity: 0; pointer-events: none; transition: opacity 200ms ease; }
        .event-lock-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(128,128,128,0.2);
            color: #fe6901;
            box-shadow: none;
            z-index: 2;
            border-radius: inherit;
        }
        .event-lock-overlay::before {
            content: "";
            width: 96px;
            height: 96px;
            background: rgba(255, 255, 255, 0.75);
            border-radius: 9999px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        .event-lock-overlay .fa-lock { font-size: 56px; position: relative; z-index: 1; }
        
        .event-carousel { position: relative; width: 100%; height: 180px; border-radius: 1rem; overflow: hidden; background: #f7fafc; }
        .event-carousel .carousel-slide { position: absolute; inset: 0; opacity: 0; transition: opacity 800ms ease, transform 800ms ease; transform: scale(1.02); }
        .event-carousel .carousel-slide.active { opacity: 1; transform: scale(1); }
        .event-carousel img { width: 100%; height: 100%; object-fit: cover; display: block; }
        
        /* Scrollbar personalizada */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 107, 53, 0.1);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border-radius: 10px;
        }
        
        /* Hero pattern */
        .hero-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255, 255, 255, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
        }
        /* Overlay celebración estrella */
        #estrella-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99999;
        }
        #estrella-overlay .content {
            background: radial-gradient(1200px 600px at 50% -10%, rgba(255, 223, 107, 0.25), rgba(255,255,255,0) 60%), #ffffff;
            border-radius: 24px;
            padding: 56px 40px;
            text-align: center;
            box-shadow: 0 35px 70px -20px rgba(0, 0, 0, 0.55), 0 0 0 8px rgba(245, 158, 11, 0.08);
            position: relative;
            overflow: hidden;
            max-width: 680px;
        }
        #estrella-overlay .big-star {
            font-size: 120px;
            color: #f59e0b;
            animation: popIn 0.6s ease-out both, starPulse 1.8s ease-in-out 0.6s infinite;
            text-shadow: 0 0 20px rgba(245, 158, 11, 0.6), 0 0 40px rgba(245, 158, 11, 0.35);
        }
        #estrella-overlay .big-bolt {
            font-size: 160px;
            color: #f59e0b;
            animation: popIn 0.6s ease-out both, starPulse 1.8s ease-in-out 0.6s infinite;
            text-shadow: 0 0 26px rgba(245, 158, 11, 0.7), 0 0 50px rgba(245, 158, 11, 0.4);
        }
        #estrella-overlay h3 {
            font-size: 36px;
            font-weight: 800;
            color: #1f2937;
            margin-top: 12px;
        }
        #estrella-overlay p {
            color: #4b5563;
            margin-top: 6px;
            font-size: 18px;
        }
        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(1); }
        }
        @keyframes starPulse {
            0%, 100% { transform: scale(1) rotate(0deg); filter: brightness(1); }
            50% { transform: scale(1.06) rotate(3deg); filter: brightness(1.15); }
        }
        .confetti-star {
            position: absolute;
            color: #f59e0b;
            opacity: 0.9;
            animation: fall 1.6s linear forwards;
            filter: drop-shadow(0 2px 2px rgba(0,0,0,0.2));
        }
        @keyframes fall {
            0% { transform: translateY(-40px) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            100% { transform: translateY(300px) rotate(360deg); opacity: 0; }
        }
        .sparkle {
            position: absolute;
            color: #ffe8a3;
            opacity: 0.0;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.25));
            animation: sparkleUp 1.4s ease-out forwards;
        }
        @keyframes sparkleUp {
            0% { transform: translateY(20px) scale(0.4) rotate(0deg); opacity: 0; }
            20% { opacity: 1; }
            100% { transform: translateY(-40px) scale(1) rotate(180deg); opacity: 0; }
        }
        .burst-ring {
            position: absolute;
            left: 50%;
            top: 50%;
            width: 10px;
            height: 10px;
            border-radius: 9999px;
            border: 3px solid rgba(245, 158, 11, 0.45);
            transform: translate(-50%, -50%);
            animation: ringPop 0.9s ease-out forwards;
        }
        @keyframes ringPop {
            0% { width: 10px; height: 10px; opacity: 0.9; }
            100% { width: 420px; height: 420px; opacity: 0; }
        }
    </style>
</head>
    <body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <?php $partNombre = $_COOKIE['part_nombre'] ?? ''; $partId = $_COOKIE['part_id'] ?? ''; $isPartLogged = ($partNombre !== ''); ?>
    <nav class="fixed top-0 w-full z-50 shadow-lg" style="background-color: #fe6901;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center">
                        <img src="logo.png" alt="Reto Inspira Logo" class="w-full h-full object-contain">
                    </div>
                    <h1 class="text-2xl font-bold text-white">
                        Reto Inspira
                    </h1>
                </div>
                <div class="hidden md:flex items-center gap-8">
                    <a href="#inicio" class="text-white hover:text-orange-200 transition-colors">Inicio</a>
                    <a href="#registro" class="text-white hover:text-orange-200 transition-colors">Registro</a>
                    <a href="#eventos" class="text-white hover:text-orange-200 transition-colors">Eventos</a>
                    <a href="#posiciones" class="text-white hover:text-orange-200 transition-colors">Posiciones</a>
                    <?php if ($isPartLogged): ?>
                        <span class="text-white font-semibold hidden sm:inline">Hola, <?php echo htmlspecialchars($partNombre); ?></span>
                        <a href="logout_participante.php" class="bg-white hover:bg-orange-50 px-6 py-2 rounded-xl hover:shadow-lg transition-all" style="color: #fe6901;">
                            <i class="fas fa-sign-out-alt mr-2"></i>Salir
                        </a>
                    <?php else: ?>
                        <a href="login_participante.php" class="bg-white hover:bg-orange-50 px-6 py-2 rounded-xl hover:shadow-lg transition-all" style="color: #fe6901;">
                            <i class="fas fa-sign-in-alt mr-2"></i>Acceder
                        </a>
                    <?php endif; ?>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-btn" class="text-white hover:text-orange-200">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden" style="background-color: #fe6901;">
            <div class="px-4 py-4 space-y-3">
                <a href="#inicio" class="block text-white hover:text-orange-200 transition-colors">Inicio</a>
                <a href="#registro" class="block text-white hover:text-orange-200 transition-colors">Registro</a>
                <a href="#eventos" class="block text-white hover:text-orange-200 transition-colors">Eventos</a>
                <a href="#posiciones" class="block text-white hover:text-orange-200 transition-colors">Posiciones</a>
                <a href="login_participante.php" class="block bg-white hover:bg-orange-50 px-6 py-2 rounded-xl text-center" style="color: #fe6901;">
                    <i class="fas fa-sign-in-alt mr-2"></i>Acceder
                </a>
            </div>
        </div>
    </nav>

    <!-- Overlay Celebración -->
    <div id="estrella-overlay">
        <div class="content">
            <div class="big-bolt"><i class="fas fa-bolt"></i></div>
            <h3>¡Ganaste 1 rayo!</h3>
            <p>Gracias por participar. ¡Sigue acumulando puntos!</p>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="inicio" class="pt-24 pb-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="animate-fade-in-left">
                    <h1 class="text-5xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Conquistemos Valencia Juntos
                        <span class="block" style="color: #fe6901;">
                            Reto Inspira
                        </span>
                    </h1>
                    <p class="text-xl text-gray-700 mb-8 leading-relaxed">
                        Únete a nuestra comunidad fitness para participar en eventos mensuales que mejoran tus destrezas físicas. <strong class="text-gray-900">¡Si ganas te llevas 50 euros en efectivo!</strong>
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#registro" class="text-white px-8 py-4 rounded-xl font-bold hover:shadow-xl transition-all hover-scale" style="background-color: #fe6901;">
                            <i class="fas fa-user-plus mr-2"></i>
                            Registrarse Ahora
                        </a>
                        <a href="#posiciones" class="border-2 px-8 py-4 rounded-xl font-bold transition-all" style="border-color: #fe6901; color: #fe6901;" onmouseover="this.style.backgroundColor='#fe6901'; this.style.color='white';" onmouseout="this.style.backgroundColor='transparent'; this.style.color='#fe6901';">
                            <i class="fas fa-trophy mr-2"></i>
                            Ver Posiciones
                        </a>
                    </div>
                </div>
                <div class="animate-fade-in-right">
                    <div class="relative">
                        <div class="w-full h-96 rounded-3xl flex items-center justify-center animate-float shadow-lg" style="background: linear-gradient(135deg, #fe6901 0%, #ff8c42 100%);">
                            <img src="foto-grupo.jpg?t=<?php echo @filemtime(__DIR__ . '/foto-grupo.jpg'); ?>" alt="Foto de Grupo" class="w-full h-full object-cover rounded-3xl">
                        </div>
                        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full flex items-center justify-center animate-pulse-slow shadow-lg" style="background-color: #fe6901;">
                            <i class="fas fa-bolt text-white text-4xl"></i>
                        </div>
                        <div class="absolute -bottom-4 -left-4 w-20 h-20 rounded-full flex items-center justify-center animate-pulse-slow shadow-lg" style="background-color: #fe6901;">
                            <img src="logo.png" alt="Logo" class="w-full h-full object-contain rounded-full">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" id="stats-section">
                <!-- Las estadísticas se cargarán dinámicamente -->
            </div>
        </div>
    </section>
    <section id="posiciones" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in-up">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-trophy mr-3" style="color: #fe6901;"></i>
                    Tabla de Posiciones
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Descubre quiénes están liderando el reto y motívate a alcanzar el top
                </p>
            </div>
            
            <div class="card-modern rounded-2xl p-8 max-w-4xl mx-auto">
                <div id="leaderboard">
                    <!-- La tabla se cargará dinámicamente -->
                    <div class="text-center py-8">
                        <div class="spinner w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
                        <p class="text-gray-600">Cargando posiciones...</p>
                    </div>
                </div>
                
                <!-- Controles de visualización para tabla de posiciones -->
                <div id="posiciones-controls" class="hidden mt-6 flex justify-center">
                    <button id="toggle-posiciones-view" class="px-6 py-3 text-sm font-medium text-white rounded-xl transition-all hover:shadow-lg" style="background-color: #fe6901;" onmouseover="this.style.backgroundColor='#ff8c42';" onmouseout="this.style.backgroundColor='#fe6901';">
                        <i class="fas fa-expand-alt mr-2"></i>
                        <span id="toggle-posiciones-text">Ver Todos los Participantes</span>
                    </button>
                </div>
            </div>
        </div>
    </section>
    <!-- Eventos Section -->
    <section id="eventos" class="py-20 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-in-up">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    Desafíos
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-6">
                    Participa en nuestros emocionantes eventos fitness y gana puntos por tu participación y asistencia
                </p>
                <div class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-semibold" style="background-color: #fe6901; color: white;">
                    <i class="fas fa-info-circle"></i>
                    <span>Los puntos se otorgan únicamente por participar y asistir a eventos</span>
                </div>
            </div>
            
            <?php
            require_once __DIR__ . '/conexion.php';
            $eventos = [];
            try {
                $rs = $conn->query("SELECT id, titulo, descripcion, fecha_inicio, fecha_fin, ubicacion, importante, puntos, tipo FROM eventos ORDER BY fecha_fin DESC, id DESC");
                if ($rs) {
                    while ($row = $rs->fetch_assoc()) { $eventos[] = $row; }
                }
            } catch (Exception $e) { /* silencio seguro */ }
            ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (empty($eventos)): ?>
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-10 text-gray-600">
                        <i class="fas fa-calendar-times text-4xl mb-3 opacity-50"></i>
                        <p class="font-medium">Vaya, parece que ahora mismo no hay ningun evento.</p>
                    </div>
                <?php else: ?>
                    <?php 
                        $pastShown = false; 
                        foreach ($eventos as $ev): 
                        $inicio = !empty($ev['fecha_inicio']) ? new DateTime($ev['fecha_inicio']) : (isset($ev['fecha_hora']) ? new DateTime($ev['fecha_hora']) : null);
                        $fin = !empty($ev['fecha_fin']) ? new DateTime($ev['fecha_fin']) : null;
                        $ahora = new DateTime();
                        $no_iniciado = $inicio ? ($inicio > $ahora) : false;
                        $es_pasado = (!$no_iniciado) && (($fin && $fin < $ahora) || (!$fin && $inicio && $inicio < $ahora));
                        $solo_evento = (isset($ev['tipo']) && $ev['tipo'] === 'evento');
                        $important = intval($ev['importante'] ?? 0) === 1;
                        if ($es_pasado && (!$solo_evento || $pastShown)) { continue; }
                    ?>
                        <div class="card-modern rounded-2xl p-6 hover-lift relative <?php echo $important ? 'event-card-important' : ''; ?>">
                            <?php if ($no_iniciado): ?>
                                <div class="event-lock-overlay"><i class="fas fa-lock"></i></div>
                            <?php endif; ?>
                            <div class="event-content <?php echo $no_iniciado ? 'event-card-locked' : ''; ?>">
                                <div class="w-16 h-16 rounded-xl flex items-center justify-center mb-4" style="background-color: #fe6901;">
                                    <i class="fas fa-calendar-alt text-white text-2xl"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-3"><?php echo htmlspecialchars($ev['titulo']); ?></h3>
                                <?php if (!empty($ev['descripcion'])): ?>
                                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($ev['descripcion']); ?></p>
                                <?php endif; ?>
                                <?php 
                                $images = [];
                                if ($es_pasado && $solo_evento) {
                                    $dir = __DIR__ . '/uploads/eventos/' . intval($ev['id']);
                                    if (is_dir($dir)) {
                                        $files = glob($dir . '/*.{jpg,jpeg,png,webp}', GLOB_BRACE);
                                        foreach ($files as $f) { 
                                            $rel = str_replace(__DIR__ . '/', '', $f);
                                            $images[] = $rel; 
                                        }
                                    }
                                }
                                ?>
                                <?php if ($es_pasado && $solo_evento && !empty($images)): ?>
                                    <div class="event-carousel" data-event-id="<?php echo intval($ev['id']); ?>">
                                        <?php foreach ($images as $idx => $img): ?>
                                            <div class="carousel-slide <?php echo $idx === 0 ? 'active' : ''; ?>">
                                                <img src="<?php echo htmlspecialchars($img); ?>" alt="Foto evento" />
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="space-y-1 text-sm text-gray-700 mb-4">
                                   <?php if ($inicio): ?>
                                     <div><i class="fas fa-play mr-2 text-orange-600"></i><?php echo $inicio->format('d/m/Y H:i'); ?></div>
                                   <?php endif; ?>
                                   <?php if ($fin): ?>
                                     <div><i class="fas fa-flag-checkered mr-2 text-orange-600"></i><?php echo $fin->format('d/m/Y H:i'); ?></div>
                                   <?php endif; ?>
                                   <?php if (!empty($ev['ubicacion'])): ?>
                                     <div><i class="fas fa-map-marker-alt mr-2 text-orange-600"></i><?php echo htmlspecialchars($ev['ubicacion']); ?></div>
                                   <?php endif; ?>
                                   <?php if (isset($ev['puntos']) && $ev['puntos'] !== null): ?>
                                     <div><i class="fas fa-bolt mr-2 text-orange-600"></i><?php echo intval($ev['puntos']); ?> puntos</div>
                                   <?php endif; ?>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="px-3 py-1 rounded-full text-sm text-white" style="background-color: #fe6901;">
                                       <?php echo $no_iniciado ? 'Próximo' : 'Activo'; ?>
                                    </span>
                                    <?php if ($important): ?>
                                        <span class="text-orange-600 font-semibold flex items-center gap-1"><i class="fas fa-star"></i> Importante</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php 
                        if ($es_pasado && $solo_evento) { $pastShown = true; }
                        endforeach; 
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </section>
 <!-- Registro Section -->
 <section id="registro" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Formulario de Registro -->
                <div class="animate-fade-in-left">
                    <div class="card-modern rounded-2xl p-8">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color: #fe6901;">
                                <i class="fas fa-user-plus text-white text-2xl"></i>
                            </div>
                            <h2 class="text-3xl font-bold text-gray-900 mb-2">
                                Únete al Reto
                            </h2>
                            <p class="text-gray-600">
                                Regístrate y comienza a ganar puntos participando en nuestros eventos de ejercicio
                            </p>
                        </div>

                        <form id="form-registro" class="space-y-6">
                            <div>
                                <label class="block text-gray-800 font-semibold mb-2">Nombre completo *</label>
                                <input type="text" name="nombre" id="reg-nombre" required 
                                       placeholder="Ej: Ana García" 
                                       class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800 transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-gray-800 font-semibold mb-2">Correo electrónico *</label>
                                <input type="email" name="correo" id="reg-correo" required 
                                       placeholder="Ej: ana@ejemplo.com" 
                                       class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800 transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-gray-800 font-semibold mb-2">Teléfono (opcional)</label>
                                <input type="tel" name="telefono" id="reg-telefono" 
                                       placeholder="Ej: +56912345678" 
                                       class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800 transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-gray-800 font-semibold mb-2">Organización (opcional)</label>
                                <input type="text" name="organizacion" id="reg-organizacion" 
                                       placeholder="Ej: Empresa ABC, Gimnasio XYZ" 
                                       class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800 transition-all">
                            </div>
                            
                            <div>
                                <label class="block text-gray-800 font-semibold mb-2">Cargo/Rol (opcional)</label>
                                <input type="text" name="cargo" id="reg-cargo" 
                                       placeholder="Ej: Atleta, Entrenador, Participante" 
                                       class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800 transition-all">
                            </div>

                            <button type="submit" class="w-full text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 hover:shadow-xl hover-scale" style="background-color: #fe6901;">
                                <i class="fas fa-running mr-2"></i>
                                <span>Comenzar Mi Reto</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Ranking Card -->
                <div class="animate-fade-in-right">
                    <div class="card-modern rounded-2xl p-8 h-full">
                        <div class="text-center mb-6">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color: #fe6901;">
                                <i class="fas fa-trophy text-white text-2xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">
                                Ranking Actual
                            </h3>
                            <p class="text-gray-600">
                                Los participantes que más puntos han ganado
                            </p>
                        </div>

                        <div id="ranking-widget">
                            <div class="text-center py-8">
                                <div class="spinner w-8 h-8 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-4"></div>
                                <p class="text-gray-600">Cargando ranking...</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="#posiciones" class="block w-full text-center text-white font-semibold py-3 px-4 rounded-xl transition-all" style="background-color: #fe6901;">
                                <i class="fas fa-list mr-2"></i>
                                Ver Tabla Completa
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Tabla de Posiciones -->
    

    <!-- Call to Action -->
    <section id="unete" class="py-20" style="background-color: #fe6901;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="animate-fade-in-up">
                <h2 class="text-4xl font-bold text-white mb-6">
                    ¿Listo para el desafío?
                </h2>
                <p class="text-xl text-orange-100 mb-8 max-w-2xl mx-auto">
                    Únete a miles de personas que ya están transformando su vida. 
                    Tu journey fitness comienza con un solo clic.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="login_participante.php" class="bg-white hover:bg-orange-50 px-8 py-4 rounded-xl font-bold hover:shadow-xl transition-all hover-scale" style="color: #fe6901;">
                        <i class="fas fa-user-plus mr-2"></i>
                        Unirse Ahora
                    </a>
                    <a href="#posiciones" class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white transition-all" style="--hover-color: #fe6901;" onmouseover="this.style.color='#fe6901';" onmouseout="this.style.color='white';">
                        <i class="fas fa-info-circle mr-2"></i>
                        Más Información
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-white py-12" style="background-color: #fe6901;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                        <img src="logo.png" alt="Reto Inspira Logo" class="w-full h-full object-contain">
                    </div>
                    <h3 class="text-xl font-bold text-white">
                        Reto Inspira
                    </h3>
                </div>
                    <p class="text-orange-100">
                        Transformando vidas a través del ejercicio y la comunidad.
                        Tu bienestar es nuestra misión.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-white">Enlaces</h4>
                    <ul class="space-y-2 text-orange-100">
                        <li><a href="#inicio" class="hover:text-white transition-colors">Inicio</a></li>
                        <li><a href="#eventos" class="hover:text-white transition-colors">Eventos</a></li>
                        <li><a href="#posiciones" class="hover:text-white transition-colors">Posiciones</a></li>
                        <?php if ($isPartLogged): ?>
    <li><a href="logout_participante.php" class="hover:text-white transition-colors">Salir</a></li>
<?php else: ?>
    <li><a href="login_participante.php" class="hover:text-white transition-colors">Acceder</a></li>
<?php endif; ?>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4 text-white">Encuéntranos en</h4>
                    <ul class="space-y-2 text-orange-100">
                        <li><i class="fab fa-tiktok mr-2"></i> <a href="https://www.tiktok.com/@retooinspira" target="_blank" class="hover:text-white transition-colors">@retooinspira</a></li>
                        <li><i class="fab fa-instagram mr-2"></i> <a href="https://www.instagram.com/retooinspira/" target="_blank" class="hover:text-white transition-colors">@retooinspira</a></li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Valencia, España</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-orange-300 mt-8 pt-8 text-center text-orange-100">
                <p>&copy; 2025 Reto Inspira. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Celebración por hash #estrella
        function spawnConfettiStars(container) {
            // Generar más estrellitas con posiciones y delays aleatorios
            for (let i = 0; i < 42; i++) {
                const el = document.createElement('div');
                el.className = 'confetti-star';
                el.style.left = (Math.random() * 100) + '%';
                el.style.top = (-Math.random() * 60) + 'px';
                el.style.fontSize = (16 + Math.random() * 24) + 'px';
                el.style.animationDelay = (Math.random() * 0.9) + 's';
                el.innerHTML = '<i class="fas fa-star"></i>';
                container.appendChild(el);
                // Remover al terminar
                setTimeout(() => el.remove(), 2200);
            }

            // Chispas brillantes alrededor
            for (let i = 0; i < 18; i++) {
                const sp = document.createElement('div');
                sp.className = 'sparkle';
                sp.style.left = (30 + Math.random() * 40) + '%';
                sp.style.top = (30 + Math.random() * 40) + '%';
                sp.style.fontSize = (8 + Math.random() * 16) + 'px';
                sp.style.animationDelay = (Math.random() * 0.8) + 's';
                sp.innerHTML = '<i class="fas fa-star"></i>';
                container.appendChild(sp);
                setTimeout(() => sp.remove(), 1600);
            }

            // Anillo de explosión sutil
            const ring = document.createElement('div');
            ring.className = 'burst-ring';
            container.appendChild(ring);
            setTimeout(() => ring.remove(), 1000);
        }

        function showEstrellaCelebration() {
            const overlay = document.getElementById('estrella-overlay');
            if (!overlay) return;
            overlay.style.display = 'flex';
            // Ajustar mensaje dinámico según query ?rayos=N
            const params = new URLSearchParams(window.location.search);
            let rayos = parseInt(params.get('rayos') || '1', 10);
            if (!Number.isFinite(rayos) || rayos < 1) rayos = 1;
            const h3 = overlay.querySelector('h3');
            if (h3) h3.textContent = `¡Ganaste ${rayos} ${rayos === 1 ? 'rayo' : 'rayos'}!`;
            spawnConfettiStars(overlay.querySelector('.content'));
            // Cerrar al click o después de 4.2s
            const close = () => { overlay.style.display = 'none'; overlay.removeEventListener('click', close); };
            overlay.addEventListener('click', close);
            setTimeout(close, 4200);
        }

        function getCookie(name) {
            const parts = ('; ' + document.cookie).split('; ' + name + '=');
            if (parts.length === 2) return parts.pop().split(';').shift();
            return '';
        }
        function setCookie(name, value, days) {
            const d = new Date();
            d.setTime(d.getTime() + (days*24*60*60*1000));
            const expires = 'expires=' + d.toUTCString();
            document.cookie = name + '=' + encodeURIComponent(value || '') + '; ' + expires + '; path=/';
        }
        function checkHashEstrella() {
            if (window.location.hash && window.location.hash.toLowerCase() === '#estrella') {
                const params = new URLSearchParams(window.location.search);
                const cele = (params.get('cele') || '').trim();
                const partId = getCookie('part_id');
                const cookieKey = partId ? ('cele_part_' + partId) : 'cele_global';
                const last = getCookie(cookieKey);
                if (cele && last && last === cele) {
                    // Ya celebrada: limpiar hash para que no reaparezca
                    history.replaceState(null, '', window.location.pathname + window.location.search);
                    return;
                }
                // Mostrar celebración y marcarla como vista una vez
                showEstrellaCelebration();
                if (cele) setCookie(cookieKey, cele, 180);
                // Limpiar hash para evitar repetición en refresh
                history.replaceState(null, '', window.location.pathname + window.location.search);
            }
        }

        window.addEventListener('hashchange', checkHashEstrella);

        // Smooth scrolling para enlaces de navegación
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Cargar estadísticas
        function cargarEstadisticas() {
            console.log('🔄 Cargando estadísticas...');
            fetch('api_participantes_publico.php')
                .then(r => r.text())
                .then(t => {
                    console.log('📊 Respuesta RAW estadísticas:', t);
                    try {
                        const data = JSON.parse(t);
                        console.log('📊 Datos estadísticas parseados:', data);
                        if (data.success) {
                        const participantes = data.items || [];
                        const totalParticipantes = participantes.length;
                        const totalPuntos = participantes.reduce((sum, p) => sum + (parseInt(p.puntos) || 0), 0);
                        const promedioPuntos = totalParticipantes > 0 ? Math.round(totalPuntos / totalParticipantes) : 0;
                        
                        console.log('📈 Estadísticas calculadas:', {
                            totalParticipantes,
                            totalPuntos,
                            promedioPuntos
                        });

                        document.getElementById('stats-section').innerHTML = `
                            <div class="text-center animate-fade-in-up">
                                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color: #fe6901;">
                                    <i class="fas fa-users text-white text-2xl"></i>
                                </div>
                                <h3 class="text-3xl font-bold text-gray-800">${totalParticipantes}</h3>
                                <p class="text-gray-600">Participantes Activos</p>
                            </div>
                            <div class="text-center animate-fade-in-up">
                                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color: #fe6901;">
                                    <i class="fas fa-bolt text-white text-2xl"></i>
                                </div>
                                <h3 class="text-3xl font-bold text-gray-800">${totalPuntos.toLocaleString()}</h3>
                                <p class="text-gray-600">Puntos Totales</p>
                            </div>
                            <div class="text-center animate-fade-in-up">
                                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color: #fe6901;">
                                    <i class="fas fa-chart-line text-white text-2xl"></i>
                                </div>
                                <h3 class="text-3xl font-bold text-gray-800">${promedioPuntos}</h3>
                                <p class="text-gray-600">Promedio de Puntos</p>
                            </div>
                        `;
                        } else {
                            console.error('❌ Error en respuesta de estadísticas:', data.message);
                        }
                } catch (e) {
                    console.error('❌ Error parseando JSON estadísticas:', e);
                    console.error('❌ Respuesta raw estadísticas:', t);
                }
            })
            .catch(error => {
                console.error('❌ Error de red cargando estadísticas:', error);
            });
        }

        // Variables globales para el ranking
        let todosLosParticipantes = [];
        let mostrarTodos = false;

        // Cargar ranking widget (top 10 por defecto)
        function cargarRankingWidget() {
            console.log('🏆 Cargando ranking widget...');
            fetch('api_participantes_publico.php')
                .then(r => r.text())
                .then(t => {
                    console.log('📡 Respuesta RAW del servidor:', t);
                    try {
                        const data = JSON.parse(t);
                        console.log('🏆 Datos ranking parseados:', data);
                        if (data.success) {
                        todosLosParticipantes = data.items || [];
                        console.log('👥 Participantes para ranking:', todosLosParticipantes.length);
                        
                        // Ordenar por puntos descendente
                        todosLosParticipantes.sort((a, b) => (parseInt(b.puntos) || 0) - (parseInt(a.puntos) || 0));
                        
                        // Mostrar ranking inicial (top 10)
                        mostrarRanking();
                        
                        // Mostrar controles si hay más de 10 participantes
                        const controlesElement = document.getElementById('ranking-controls');
                        if (todosLosParticipantes.length > 10 && controlesElement) {
                            controlesElement.classList.remove('hidden');
                        }
                        
                    } else {
                        console.error('❌ Error en respuesta ranking:', data.message);
                        mostrarErrorRanking(data.message);
                    }
                } catch (e) {
                    console.error('❌ Error parseando JSON:', e);
                    console.error('❌ Respuesta raw:', t);
                    mostrarErrorRanking('Error al procesar datos');
                }
            })
            .catch(error => {
                console.error('❌ Error de red cargando ranking widget:', error);
                mostrarErrorRanking('Error de conexión');
            });
        }

        // Función para mostrar el ranking
        function mostrarRanking() {
            const participantesAMostrar = mostrarTodos ? todosLosParticipantes : todosLosParticipantes.slice(0, 10);
            const rankingElement = document.getElementById('ranking-widget');
            const toggleText = document.getElementById('toggle-text');
            
            if (!rankingElement) return;
            
            let html = '';
            
            if (participantesAMostrar.length === 0) {
                html = `
                    <div class="text-center py-8">
                        <i class="fas fa-trophy text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500 font-semibold">¡Sé el primero!</p>
                        <p class="text-gray-400 text-sm">Regístrate y comienza a ganar puntos</p>
                    </div>
                `;
            } else {
                html = '<div class="space-y-3">';
                
                participantesAMostrar.forEach((participante, index) => {
                    const puntos = parseInt(participante.puntos) || 0;
                    const posicion = index + 1;
                    const medallIcon = posicion === 1 ? 'fas fa-trophy text-yellow-500' : 
                                     posicion === 2 ? 'fas fa-award text-gray-400' : 
                                     posicion === 3 ? 'fas fa-award text-gray-300' : 
                                     'fas fa-user-circle text-gray-300';
                    
                    html += `
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-2">
                                <i class="${medallIcon}"></i>
                                <span class="font-bold text-lg text-gray-800">${posicion}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="font-semibold text-gray-800 truncate">${participante.nombre || 'Sin nombre'}</div>
                                ${participante.organizacion ? `<div class="text-sm text-gray-500 truncate">${participante.organizacion}</div>` : ''}
                            </div>
                            <div class="flex items-center gap-1 px-2 py-1 rounded-full text-sm font-bold" style="background-color: #fe6901; color: white;">
                                <i class="fas fa-bolt text-xs"></i>
                                ${puntos}
                            </div>
                        </div>
                    `;
                });
                
                html += '</div>';
            }
            
            rankingElement.innerHTML = html;
            
            // Actualizar texto del botón
            if (toggleText) {
                toggleText.textContent = mostrarTodos ? 'Ver Top 10' : 'Ver Todos';
            }
            
            console.log('✅ Ranking actualizado en DOM');
        }

        // Función para mostrar error
        function mostrarErrorRanking(mensaje) {
            const rankingElement = document.getElementById('ranking-widget');
            if (rankingElement) {
                rankingElement.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-red-500 text-2xl mb-2"></i>
                        <p class="text-gray-600 text-sm">Error: ${mensaje}</p>
                    </div>
                `;
            }
        }

        // Función para alternar vista
        function toggleRankingView() {
            mostrarTodos = !mostrarTodos;
            mostrarRanking();
        }

        // Variables globales para la tabla de posiciones
        let todosLosParticipantesPosiciones = [];
        let mostrarTodosPosiciones = false;

        // Cargar tabla de posiciones (top 10 por defecto)
        function cargarTablaposiciones() {
            fetch('api_participantes_publico.php')
                .then(r => r.text())
                .then(t => {
                    console.log('📊 Respuesta RAW tabla posiciones:', t);
                    try {
                        const data = JSON.parse(t);
                        console.log('📊 Datos tabla parseados:', data);
                        if (data.success) {
                        todosLosParticipantesPosiciones = data.items || [];
                        
                        // Ordenar por puntos descendente
                        todosLosParticipantesPosiciones.sort((a, b) => (parseInt(b.puntos) || 0) - (parseInt(a.puntos) || 0));
                        
                        // Mostrar tabla inicial (top 10)
                        mostrarTablaPosiciones();
                        
                        // Mostrar controles si hay más de 10 participantes
                        const controlesElement = document.getElementById('posiciones-controls');
                        if (todosLosParticipantesPosiciones.length > 10 && controlesElement) {
                            controlesElement.classList.remove('hidden');
                        }
                        
                        console.log('✅ Tabla de posiciones actualizada');
                    } else {
                        console.error('❌ Error en respuesta tabla:', data.message);
                        mostrarErrorTablaPosiciones(data.message);
                    }
                } catch (e) {
                    console.error('❌ Error parseando JSON tabla:', e);
                    console.error('❌ Respuesta raw tabla:', t);
                    mostrarErrorTablaPosiciones('Error al procesar datos de tabla');
                }
            })
            .catch(error => {
                console.error('❌ Error de red cargando tabla de posiciones:', error);
                mostrarErrorTablaPosiciones('Error de conexión al cargar tabla');
            });
        }

        // Función para mostrar la tabla de posiciones
        function mostrarTablaPosiciones() {
            const participantesAMostrar = mostrarTodosPosiciones ? todosLosParticipantesPosiciones : todosLosParticipantesPosiciones.slice(0, 10);
            const leaderboardElement = document.getElementById('leaderboard');
            const toggleText = document.getElementById('toggle-posiciones-text');
            
            if (!leaderboardElement) return;
            
            let html = '';
            
            if (participantesAMostrar.length === 0) {
                html = `
                    <div class="text-center py-12">
                        <i class="fas fa-trophy text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">¡Sé el primero!</h3>
                        <p class="text-gray-500">Aún no hay participantes registrados. ¡Únete y lidera la tabla!</p>
                    </div>
                `;
            } else {
                html = `
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-200">
                                    <th class="text-left py-4 px-2 font-semibold text-gray-700">Posición</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Participante</th>
                                    <th class="text-left py-4 px-4 font-semibold text-gray-700">Organización</th>
                                    <th class="text-center py-4 px-4 font-semibold text-gray-700">Puntos</th>
                                </tr>
                            </thead>
                            <tbody>
                `;
                
                participantesAMostrar.forEach((participante, index) => {
                    const puntos = parseInt(participante.puntos) || 0;
                    const posicion = index + 1;
                    const medallIcon = posicion === 1 ? 'fas fa-trophy text-yellow-500' : 
                                     posicion === 2 ? 'fas fa-award text-gray-400' : 
                                     posicion === 3 ? 'fas fa-award text-gray-300' : 
                                     'fas fa-user-circle text-gray-300';
                    const bgClass = posicion <= 3 ? 'bg-gradient-to-r from-yellow-50 to-yellow-100' : 
                                   posicion % 2 === 0 ? 'bg-gray-50' : 'bg-white';
                    
                    html += `
                        <tr class="${bgClass} hover:bg-blue-50 transition-colors">
                            <td class="py-4 px-2">
                                <div class="flex items-center gap-2">
                                    <i class="${medallIcon}"></i>
                                    <span class="font-bold text-lg">${posicion}</span>
                                </div>
                            </td>
                            <td class="py-4 px-4">
                                <div class="font-semibold text-gray-800">${participante.nombre || 'Sin nombre'}</div>
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-gray-600">${participante.organizacion || '-'}</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full font-bold" style="background-color: #fe6901; color: white;">
                                    <i class="fas fa-bolt"></i>
                                    ${puntos}
                                </div>
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                        </table>
                    </div>
                `;
            }
            
            leaderboardElement.innerHTML = html;
            
            // Actualizar texto del botón
            if (toggleText) {
                toggleText.textContent = mostrarTodosPosiciones ? 'Ver Top 10' : 'Ver Todos los Participantes';
            }
            
            console.log('✅ Tabla de posiciones actualizada en DOM');
        }

        // Función para mostrar error en tabla de posiciones
        function mostrarErrorTablaPosiciones(mensaje) {
            const leaderboardElement = document.getElementById('leaderboard');
            if (leaderboardElement) {
                leaderboardElement.innerHTML = `
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-triangle text-red-500 text-4xl mb-4"></i>
                        <p class="text-gray-600">Error: ${mensaje}</p>
                    </div>
                `;
            }
        }

        // Función para alternar vista de tabla de posiciones
        function togglePosicionesView() {
            mostrarTodosPosiciones = !mostrarTodosPosiciones;
            mostrarTablaPosiciones();
        }

        // Función para mostrar notificaciones
        function mostrarNotificacion(mensaje, tipo = 'success') {
            const notificacion = document.createElement('div');
            notificacion.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg notification ${
                tipo === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notificacion.innerHTML = `
                <div class="flex items-center gap-2">
                    <i class="fas fa-${tipo === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    <span>${mensaje}</span>
                </div>
            `;
            document.body.appendChild(notificacion);
            
            setTimeout(() => {
                notificacion.remove();
            }, 4000);
        }

        // Manejar formulario de registro
        document.getElementById('form-registro').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('nombre', document.getElementById('reg-nombre').value);
            formData.append('correo', document.getElementById('reg-correo').value);
            formData.append('telefono', document.getElementById('reg-telefono').value);
            formData.append('organizacion', document.getElementById('reg-organizacion').value);
            formData.append('cargo', document.getElementById('reg-cargo').value);
            
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<div class="spinner w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin mx-auto"></div>';
            
            fetch('procesar_participantes.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarNotificacion('¡Te has registrado exitosamente! Bienvenido al Reto Inspira', 'success');
                    this.reset();
                    // Recargar estadísticas y ranking
                    cargarEstadisticas();
                    cargarRankingWidget();
                    cargarTablaposiciones();
                } else {
                    mostrarNotificacion(data.message || 'Error al registrarse', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error de conexión al registrarse', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // Cargar datos al inicializar la página
        document.addEventListener('DOMContentLoaded', function() {
            cargarEstadisticas();
            cargarRankingWidget();
            cargarTablaposiciones();
            checkHashEstrella();
            
            // Event listener para el botón de alternar vista del ranking
            const toggleButton = document.getElementById('toggle-ranking-view');
            if (toggleButton) {
                toggleButton.addEventListener('click', toggleRankingView);
            }
            
            // Event listener para el botón de alternar vista de la tabla de posiciones
            const togglePosicionesButton = document.getElementById('toggle-posiciones-view');
            if (togglePosicionesButton) {
                togglePosicionesButton.addEventListener('click', togglePosicionesView);
            }
        });
    </script>
<script>
// Auto-avance de carruseles de eventos pasados
document.addEventListener('DOMContentLoaded', function() {
  const carousels = document.querySelectorAll('.event-carousel');
  carousels.forEach(carousel => {
    const slides = carousel.querySelectorAll('.carousel-slide');
    if (!slides.length) return;
    let idx = 0;
    setInterval(() => {
      slides[idx].classList.remove('active');
      idx = (idx + 1) % slides.length;
      slides[idx].classList.add('active');
    }, 4000);
  });
});
</script>
</body>
</html>
