<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$nombre = $_SESSION['nombre'] ?? '';
$rol = $_SESSION['rol'] ?? '';

require_once __DIR__ . '/conexion.php';

// Función para cargar datos de participantes
function cargar_participantes_dashboard() {
    $dataDir = __DIR__ . DIRECTORY_SEPARATOR . 'database';
    $dataFile = $dataDir . DIRECTORY_SEPARATOR . 'participantes.json';
    
    // Fallback si no existe database/
    if (!file_exists($dataFile)) {
        $dataFile = __DIR__ . DIRECTORY_SEPARATOR . 'participantes.json';
    }
    
    if (!file_exists($dataFile)) {
        return ['items' => []];
    }
    
    $raw = file_get_contents($dataFile);
    if ($raw === false || $raw === '') {
        return ['items' => []];
    }
    
    $decoded = json_decode($raw, true);
    if (!is_array($decoded) || !isset($decoded['items'])) {
        return ['items' => []];
    }
    
    return $decoded;
}

// Función para cargar datos de finanzas
function cargar_finanzas_dashboard() {
    $dataFile = __DIR__ . DIRECTORY_SEPARATOR . 'finanzas.json';
    
    if (!file_exists($dataFile)) {
        return ['items' => []];
    }
    
    $raw = file_get_contents($dataFile);
    if ($raw === false || $raw === '') {
        return ['items' => []];
    }
    
    $decoded = json_decode($raw, true);
    if (!is_array($decoded) || !isset($decoded['items'])) {
        return ['items' => []];
    }
    
    return $decoded;
}

// Cargar datos para estadísticas
$participantes_data = cargar_participantes_dashboard();
$participantes = $participantes_data['items'] ?? [];

$finanzas_data = cargar_finanzas_dashboard();
$finanzas = $finanzas_data['items'] ?? [];

// Calcular estadísticas de participantes
$total_participantes = count($participantes);
$total_puntos = 0;
$participantes_con_puntos = [];

foreach ($participantes as $p) {
    $puntos = intval($p['puntos'] ?? 0);
    $total_puntos += $puntos;
    if ($puntos > 0) {
        $participantes_con_puntos[] = [
            'nombre' => $p['nombre'] ?? 'Sin nombre',
            'puntos' => $puntos,
            'organizacion' => $p['organizacion'] ?? ''
        ];
    }
}

// Ordenar por puntos y obtener top 3
usort($participantes_con_puntos, function($a, $b) {
    return $b['puntos'] - $a['puntos'];
});
$top_participantes = array_slice($participantes_con_puntos, 0, 3);

// Calcular estadísticas de finanzas
$total_ingresos = 0;
$total_gastos = 0;
$transacciones_recientes = 0;

foreach ($finanzas as $f) {
    $monto = floatval($f['monto'] ?? 0);
    $tipo = $f['tipo'] ?? '';
    
    if ($tipo === 'ingreso') {
        $total_ingresos += $monto;
    } elseif ($tipo === 'gasto') {
        $total_gastos += $monto;
    }
    
    // Contar transacciones de los últimos 30 días
    $fecha_transaccion = $f['fecha'] ?? '';
    if ($fecha_transaccion && strtotime($fecha_transaccion) > strtotime('-30 days')) {
        $transacciones_recientes++;
    }
}

$balance_total = $total_ingresos - $total_gastos;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="LOGOTIPO_light.png" type="image/png">
    <title>Dashboard | Reto Inspira</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Animaciones personalizadas */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 5px rgba(59, 130, 246, 0.5); }
            50% { box-shadow: 0 0 20px rgba(59, 130, 246, 0.8); }
        }
        
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
        
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
            40%, 43% { transform: translate3d(0, -30px, 0); }
            70% { transform: translate3d(0, -15px, 0); }
            90% { transform: translate3d(0, -4px, 0); }
        }
        
        /* Clases de animación */
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }
        
        .animate-pulse-slow {
            animation: pulse 2s infinite;
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        
        .animate-glow {
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        .animate-bounce-slow {
            animation: bounce 2s infinite;
        }
        
        /* Efectos hover mejorados */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .hover-scale {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-scale:hover {
            transform: scale(1.05);
        }
        
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
        
        .gradient-warning {
            background: linear-gradient(135deg, #ff8c42 0%, #ff6b35 100%);
        }
        
        /* Sidebar mejorado */
        .sidebar {
            background: #fe6901;
            backdrop-filter: blur(10px);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(254, 105, 1, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .sidebar-item:hover::before {
            left: 100%;
        }
        
        .sidebar-item:hover {
            transform: translateX(10px);
            background: rgba(254, 105, 1, 0.3);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(254, 105, 1, 0.3);
        }
        
        .sidebar-item.active {
            background: rgba(254, 105, 1, 0.4);
            box-shadow: 0 4px 12px rgba(254, 105, 1, 0.4);
            border-left: 4px solid #fe6901;
        }
        
        /* Cards con efectos */
        .card-modern {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-modern:hover {
            background: rgba(255, 255, 255, 1);
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Spinner mejorado */
        .spinner { 
            border: 4px solid rgba(255, 107, 53, 0.1); 
            border-top: 4px solid #ff6b35; 
            border-radius: 50%; 
            width: 40px; 
            height: 40px; 
            animation: spin 1s linear infinite; 
            margin: 0 auto;
            box-shadow: 0 0 20px rgba(255, 107, 53, 0.3);
        }
        
        /* Modal mejorado */
        .modal-bg { 
            background: rgba(15, 23, 42, 0.8); 
            backdrop-filter: blur(8px);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        /* Asegurar que cualquier modal con class hidden realmente se oculte */
        .modal-bg.hidden {
            display: none !important;
        }
        
        /* Asegurar que los modales estén ocultos por defecto */
        #modal-confirmar-eliminar,
        #modal-asignar-puntos {
            display: none !important;
        }
        
        #modal-confirmar-eliminar:not(.hidden),
        #modal-asignar-puntos:not(.hidden) {
            display: flex !important;
        }
        
        /* Debug: Forzar que la vista inicial se muestre si no hay ninguna activa */
        .dashboard-view.hidden {
            display: none !important;
        }
        
        .dashboard-view:not(.hidden) {
            display: block !important;
        }
        
        /* Estilos para notificaciones */
        .notification {
            animation: slideInRight 0.3s ease-out;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            font-weight: 500;
            backdrop-filter: blur(10px);
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(100%);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        

        
        .modal-content {
            animation: fadeInUp 0.4s ease-out;
            overflow-y: auto;
        }
        
        /* Botones modernos */
        .btn-modern {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .btn-modern:hover::before {
            left: 100%;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(255, 107, 53, 0.4);
        }
        
        /* Stats cards */
        .stat-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
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
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #e55a2b 0%, #e68a1a 100%);
        }
        
        /* Loading animation */
        .loading-dots {
            display: inline-block;
        }
        
        .loading-dots::after {
            content: '';
            animation: dots 1.5s steps(5, end) infinite;
        }
        
        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }
    </style>
    <script>

    let spinner = null;
    
    function showSpinner(colId) {
        spinner = document.createElement('div');
        spinner.className = 'spinner my-4';
        document.getElementById(colId).prepend(spinner);
    }
    
    function hideSpinner() {
        if (spinner) spinner.remove();
    }
    
    // Función para agregar efectos de entrada
    function addEntranceEffects() {
        const elements = document.querySelectorAll('.animate-on-scroll');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        });
        
        elements.forEach(el => observer.observe(el));
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOMContentLoaded ejecutándose...');
        
        // Sidebar SPA con animaciones
        const links = document.querySelectorAll('.sidebar-link');
        const views = document.querySelectorAll('.dashboard-view');
        
        console.log('Links encontrados:', links.length);
        console.log('Views encontradas:', views.length);
        
        function mostrarVista(target) {
            // Remover clases activas
            links.forEach(l => l.classList.remove('active'));
            const linkToActivate = document.querySelector('.sidebar-link[data-view="' + target + '"]');
            if (linkToActivate) linkToActivate.classList.add('active');

            // Ocultar todas las vistas con animación
            views.forEach(v => {
                v.style.opacity = '0';
                v.style.transform = 'translateY(20px)';
                setTimeout(() => v.classList.add('hidden'), 200);
            });

            const targetView = document.getElementById(target);
            setTimeout(() => {
                if (targetView) {
                    targetView.classList.remove('hidden');
                    targetView.style.opacity = '1';
                    targetView.style.transform = 'translateY(0)';
                    console.log('Vista mostrada:', target);
                } else {
                    console.error('Vista no encontrada:', target);
                }
            }, 250);
        }

        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const target = this.getAttribute('data-view');
                console.log('Click en sidebar link:', target);
                mostrarVista(target);
                // Actualizar URL con pushState
                const url = new URL(window.location.href);
                url.searchParams.set('view', target);
                window.history.pushState({ view: target }, '', url.toString());
            });
        });

        // Manejar navegación del navegador (atrás/adelante)
        window.addEventListener('popstate', function(e) {
            const params = new URLSearchParams(window.location.search);
            const target = params.get('view') || 'inicio-view';
            mostrarVista(target);
        });
        
        // Activar vista según parámetro ?view=...
        const params = new URLSearchParams(window.location.search);
        const view = params.get('view');
        console.log('Parámetro view en URL:', view);
        
        if (view && document.querySelector('.sidebar-link[data-view="' + view + '"]')) {
            console.log('Activando vista desde URL:', view);
            mostrarVista(view);
        } else {
            console.log('Activando vista por defecto: inicio-view');
            mostrarVista('inicio-view');
            const url = new URL(window.location.href);
            url.searchParams.set('view', 'inicio-view');
            window.history.replaceState({ view: 'inicio-view' }, '', url.toString());
        }
        
        // Verificación final: asegurar que al menos una vista esté visible
        setTimeout(() => {
            const visibleViews = document.querySelectorAll('.dashboard-view:not(.hidden)');
            console.log('Vistas visibles después de inicialización:', visibleViews.length);
            
            if (visibleViews.length === 0) {
                console.log('No hay vistas visibles, mostrando inicio-view');
                const inicioView = document.getElementById('inicio-view');
                if (inicioView) {
                    inicioView.classList.remove('hidden');
                    inicioView.style.opacity = '1';
                    inicioView.style.transform = 'translateY(0)';
                }
            }
        }, 500);

        // Configurar cerrar modal de nueva tarjeta
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'close-modal-puntos') {
                document.getElementById('modal-asignar-puntos').classList.add('hidden');
            }
            if (e.target && e.target.id === 'cancel-modal-puntos') {
                document.getElementById('modal-asignar-puntos').classList.add('hidden');
            }
        });
        
        
        // Asegurar que los modales estén ocultos al cargar la página
        const modalAsignarPuntos = document.getElementById('modal-asignar-puntos');
        

        
        if (modalAsignarPuntos) {
            modalAsignarPuntos.classList.add('hidden');
            console.log('Modal asignar puntos oculto al cargar la página');
        }
        
        // Manejador de envío del formulario de puntos (robusto en dashboard)
        const formPuntos = document.getElementById('form-asignar-puntos');
        if (formPuntos && !window.puntosFormListenerAttached) {
            window.puntosFormListenerAttached = true;
            formPuntos.addEventListener('submit', function(e) {
                e.preventDefault();
                const fd = new FormData(formPuntos);
                const operacion = fd.get('operacion');
                const submitBtn = document.getElementById('submit-puntos-btn');
                const originalText = submitBtn ? submitBtn.innerHTML : '';

                if (submitBtn) {
                    submitBtn.disabled = true;
                    const actionText = operacion === 'asignar' ? 'Asignando...' : 'Restando...';
                    submitBtn.innerHTML = `<div class=\"spinner w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin\"></div> ${actionText}`;
                }

                fetch('procesar_participantes.php', { method: 'POST', body: fd })
                    .then(r => r.text())
                    .then(t => {
                        try {
                            const j = JSON.parse(t);
                            if (j.success) {
                                document.getElementById('modal-asignar-puntos').classList.add('hidden');
                                const successText = operacion === 'asignar' ? 'Puntos asignados correctamente' : 'Puntos restados correctamente';
                                if (window.mostrarNotificacion) { window.mostrarNotificacion(successText, 'success'); }
                                // Recargar la página para actualizar estadísticas y vistas
                                setTimeout(() => window.location.reload(), 300);
                            } else {
                                const errorText = operacion === 'asignar' ? 'Error al asignar puntos' : 'Error al restar puntos';
                                if (window.mostrarNotificacion) { window.mostrarNotificacion(j.message || errorText, 'error'); }
                                else { alert(j.message || errorText); }
                            }
                        } catch (e) {
                            if (window.mostrarNotificacion) { window.mostrarNotificacion('Respuesta no válida del servidor', 'error'); }
                            alert('Respuesta no válida del servidor (gestionar puntos):\n' + t);
                        }
                    })
                    .catch(() => { 
                        const errorText = operacion === 'asignar' ? 'Error de red al asignar puntos' : 'Error de red al restar puntos';
                        if (window.mostrarNotificacion) { window.mostrarNotificacion(errorText, 'error'); } 
                        else { alert(errorText); } 
                    })
                    .finally(() => { 
                        if (submitBtn) { 
                            submitBtn.disabled = false; 
                            submitBtn.innerHTML = originalText; 
                        } 
                    });
            });
        }
        
        // Función para mostrar notificaciones modernas
        window.mostrarNotificacion = function(mensaje, tipo = 'success') {
            const notificacion = document.createElement('div');
            notificacion.className = `fixed top-4 right-4 z-50 p-4 notification ${
                tipo === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notificacion.textContent = mensaje;
            document.body.appendChild(notificacion);
            
            setTimeout(() => {
                notificacion.remove();
            }, 3000);
        };
        
        // Agregar efectos de entrada
        addEntranceEffects();
        
        // Animación de bienvenida
        setTimeout(() => {
            const welcomeCard = document.querySelector('.welcome-card');
            if (welcomeCard) {
                welcomeCard.classList.add('animate-fade-in-up');
            }
        }, 500);
    });
    </script>
</head>
    <body class="bg-gradient-to-br from-gray-50 via-white to-gray-100 min-h-screen flex">
    <!-- Sidebar Moderno -->
    <aside class="sidebar w-64 text-white flex flex-col py-8 px-4 min-h-screen shadow-2xl relative overflow-hidden">
        <!-- Efecto de fondo animado -->
        <div style="background: linear-gradient(135deg, #fe6901 0%, #ff8c42 100%);" class="absolute inset-0 bg-gradient-to-br from-orange-600/20 to-orange-500/20 animate-pulse-slow"></div>
        
        <div class="relative z-10">
            <div class="flex flex-col items-center mb-10 animate-fade-in-up">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mb-4 animate-float">
                    <img src="logo.png" alt="Reto Inspira Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-2xl font-bold text-white mb-2">Reto Inspira</h1>
                <span class="text-white text-sm text-center font-medium">Bienvenido, <?php echo htmlspecialchars($nombre); ?></span>
                <div class="mt-2 px-3 py-1 bg-white/30 rounded-full text-xs text-white font-semibold">
                    <i class="fas fa-crown mr-1"></i><?php echo ucfirst($rol); ?>
                </div>
            </div>
            
            <nav class="flex-1">
                <ul class="space-y-3">
                    <li>
                        <a href="#" class="sidebar-link sidebar-item flex items-center gap-4 px-4 py-4 rounded-xl font-semibold text-white transition-all duration-300" data-view="inicio-view">
                            <i class="fas fa-home text-lg text-white"></i>
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link sidebar-item flex items-center gap-4 px-4 py-4 rounded-xl font-semibold text-white transition-all duration-300" data-view="participantes-view">
                            <i class="fas fa-user-plus text-lg text-white"></i>
                            <span>Participantes</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="sidebar-link sidebar-item flex items-center gap-4 px-4 py-4 rounded-xl font-semibold text-white transition-all duration-300" data-view="usuarios-view">
                            <i class="fas fa-users text-lg text-white"></i>
                            <span>Usuarios</span>
                        </a>
                    </li>
                    <?php if ($rol === 'admin'): ?>
                    <li>
                        <a href="#" class="sidebar-link sidebar-item flex items-center gap-4 px-4 py-4 rounded-xl font-semibold text-white transition-all duration-300" data-view="eventos-view">
                            <i class="fas fa-calendar-alt text-lg text-white"></i>
                            <span>Desafíos</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    <li>
                        <a href="#" class="sidebar-link sidebar-item flex items-center gap-4 px-4 py-4 rounded-xl font-semibold text-white transition-all duration-300" data-view="finanzas-view">
                            <i class="fas fa-chart-pie text-lg text-white"></i>
                            <span>Finanzas</span>
                        </a>
                    </li>
                    <?php if ($rol !== 'admin'): ?>
                    <!-- Para no-admin solo mostramos Finanzas -->
                    <?php endif; ?>
                </ul>
            </nav>
            
            <div class="mt-10">
                <a href="logout.php" class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 text-center flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                    <i class="fas fa-sign-out-alt"></i>
                    Cerrar sesión
                </a>
            </div>
        </div>
    </aside>
    
    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">
        <!-- INICIO -->
        <section id="inicio-view" class="dashboard-view hidden" style="transition: all 0.3s ease;">
            <div class="animate-fade-in-up">
                <h2 class="text-4xl font-bold text-gray-900 mb-8 flex items-center gap-3">
                    <i class="fas fa-home text-gray-700"></i>
                    Dashboard
                </h2>
                
                <!-- Welcome Card -->
                <div class="welcome-card rounded-2xl shadow-xl p-8 mb-8 gradient-primary">
                    <div class="flex flex-col lg:flex-row items-center gap-8">
                        <div class="flex-1">
                            <h3 class="text-3xl font-bold text-white mb-4">¡Bienvenido a tu Dashboard, <?php echo htmlspecialchars($nombre); ?>!</h3>
                            <p class="text-xl text-white opacity-90 mb-6">Administra participantes y controla las finanzas de tu proyecto con herramientas modernas e intuitivas.</p>
                            <div class="flex flex-wrap gap-4">
                                <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full">
                                    <i class="fas fa-users text-white"></i>
                                    <span class="text-white"><?php echo $total_participantes; ?> Participantes</span>
                                </div>
                                <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full">
                                    <i class="fas fa-bolt text-white"></i>
                                    <span class="text-white"><?php echo number_format($total_puntos); ?> Puntos</span>
                                </div>
                                <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-full">
                                    <i class="fas fa-chart-line text-white"></i>
                                    <span class="text-white">$<?php echo number_format($balance_total, 0, ',', '.'); ?> Balance</span>
                                </div>
                            </div>
                        </div>
                        <div class="animate-float">
                            <i class="fas fa-rocket text-white text-8xl opacity-80"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="stat-card rounded-2xl p-6 hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-700 text-sm font-medium">Participantes Registrados</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo $total_participantes; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-orange-700 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card rounded-2xl p-6 hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-700 text-sm font-medium">Total Puntos</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo number_format($total_puntos); ?></p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bolt text-orange-700 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card rounded-2xl p-6 hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-700 text-sm font-medium">Balance Total</p>
                                <p class="text-3xl font-bold text-gray-900 <?php echo $balance_total >= 0 ? 'text-green-700' : 'text-red-700'; ?>">
                                    $<?php echo number_format($balance_total, 0, ',', '.'); ?>
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-orange-700 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="stat-card rounded-2xl p-6 hover-lift">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-700 text-sm font-medium">Transacciones (30d)</p>
                                <p class="text-3xl font-bold text-gray-900"><?php echo $transacciones_recientes; ?></p>
                            </div>
                            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-exchange-alt text-orange-700 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions & Top Participantes -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Top 3 Participantes -->
                    <div class="card-modern rounded-2xl p-6 hover-lift">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-trophy text-gray-700"></i>
                            Top 3 Participantes
                        </h3>
                        <div class="space-y-3">
                            <?php if (!empty($top_participantes)): ?>
                                <?php foreach ($top_participantes as $index => $participante): ?>
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r <?php 
                                        echo $index === 0 ? 'from-orange-50 to-orange-100' : 
                                             ($index === 1 ? 'from-orange-100 to-orange-200' : 'from-orange-200 to-orange-300'); 
                                    ?>">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center <?php 
                                            echo $index === 0 ? 'bg-orange-500 text-gray-300' : 
                                                 ($index === 1 ? 'bg-orange-600 text-gray-300' : 'bg-orange-700 text-gray-300'); 
                                        ?>">
                                            <?php echo $index + 1; ?>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($participante['nombre']); ?></p>
                                            <?php if (!empty($participante['organizacion'])): ?>
                                                <p class="text-sm text-gray-700"><?php echo htmlspecialchars($participante['organizacion']); ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center gap-1 font-bold text-gray-900">
                                                <i class="fas fa-bolt text-orange-600"></i>
                                                <?php echo $participante['puntos']; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-6 text-gray-600">
                                    <i class="fas fa-users text-4xl mb-3 opacity-50"></i>
                                    <p class="font-medium">No hay participantes con puntos aún</p>
                                    <p class="text-sm font-medium">¡Comienza asignando puntos!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Acciones Rápidas y Finanzas -->
                    <div class="card-modern rounded-2xl p-6 hover-lift">
                        <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <i class="fas fa-tachometer-alt text-orange-700"></i>
                            Panel de Control
                        </h3>
                        <div class="space-y-4">
                            <!-- Acciones Rápidas -->
                            <div class="space-y-3">
                                <button onclick="document.querySelector('[data-view=\'participantes-view\']').click()" class="w-full text-left p-3 rounded-xl bg-orange-50 hover:bg-orange-100 transition-colors flex items-center gap-3">
                                    <i class="fas fa-user-plus text-orange-700"></i>
                                    <span class="text-gray-900 font-medium">Registrar participante</span>
                                </button>
                                <button onclick="document.querySelector('[data-view=\'finanzas-view\']').click()" class="w-full text-left p-3 rounded-xl bg-orange-50 hover:bg-orange-100 transition-colors flex items-center gap-3">
                                    <i class="fas fa-chart-pie text-orange-700"></i>
                                    <span class="text-gray-900 font-medium">Gestionar finanzas</span>
                                </button>
                            </div>
                            
                            <!-- Resumen Financiero -->
                            <div class="border-t pt-4">
                                <h4 class="font-semibold text-gray-900 mb-2">Resumen Financiero</h4>
                                <div class="grid grid-cols-2 gap-3 text-sm">
                                    <div class="bg-green-50 p-2 rounded-lg">
                                        <p class="text-gray-700 font-medium">Ingresos</p>
                                        <p class="font-bold text-green-700">$<?php echo number_format($total_ingresos, 0, ',', '.'); ?></p>
                                    </div>
                                    <div class="bg-red-50 p-2 rounded-lg">
                                        <p class="text-gray-700 font-medium">Gastos</p>
                                        <p class="font-bold text-red-700">$<?php echo number_format($total_gastos, 0, ',', '.'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        
        <!-- PARTICIPANTES -->
        <section id="participantes-view" class="dashboard-view hidden" style="transition: all 0.3s ease;">
            <?php include __DIR__ . '/vistas/participantes.php'; ?>
        </section>
        
        <?php if ($rol === 'admin'): ?>
        <!-- USUARIOS (solo admin) -->
        <section id="usuarios-view" class="dashboard-view hidden" style="transition: all 0.3s ease;">
            <?php include __DIR__ . '/vistas/usuarios.php'; ?>
        </section>
        <?php endif; ?>

        <!-- FINANZAS (todos los roles, con permisos por API) -->
        <section id="finanzas-view" class="dashboard-view hidden" style="transition: all 0.3s ease;">
            <?php include __DIR__ . '/vistas/finanzas.php'; ?>
        </section>

        <?php if ($rol === 'admin'): ?>
        <!-- EVENTOS (solo admin) -->
        <section id="eventos-view" class="dashboard-view hidden" style="transition: all 0.3s ease;">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-orange-600"></i>
                    Desafíos
                </h2>

                <div id="evento-alert" class="hidden mb-4 p-3 rounded-lg"></div>

                <!-- Formulario Crear Evento -->
                <div class="bg-white rounded-xl shadow p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Crear nuevo desafío</h3>
                    <form id="form-crear-evento" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                            <input type="text" name="titulo" id="titulo" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500" placeholder="Ej: Taller de creatividad" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo *</label>
                            <select name="tipo" id="tipo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500">
                                <option value="reto">Reto</option>
                                <option value="evento">Evento</option>
                                <option value="mision">Misión</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea name="descripcion" id="descripcion" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500" placeholder="Detalles del evento"></textarea>
                        </div>

                        <!-- Inicio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha inicio *</label>
                            <input type="date" id="fecha_inicio_dia" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hora inicio *</label>
                            <input type="time" id="fecha_inicio_hora" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500" required />
                        </div>

                        <!-- Fin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha fin *</label>
                            <input type="date" id="fecha_fin_dia" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500" required />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Hora fin *</label>
                            <input type="time" id="fecha_fin_hora" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500" required />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ubicación</label>
                            <input type="text" name="ubicacion" id="ubicacion" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500" placeholder="Ej: Sala 3, Zoom..." />
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="importante" id="importante" class="text-orange-600 focus:ring-orange-500" />
                            <label for="importante" class="text-sm font-medium text-gray-700">Marcar como importante</label>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Puntos otorgados (opcional)</label>
                            <input type="number" name="puntos_evento" id="puntos_evento" min="0" step="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500" placeholder="0" />
                        </div>
                        
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" style="background: linear-gradient(135deg, #fe6901 0%, #ff8c42 100%);" class="text-white font-bold px-5 py-2 rounded-lg">Crear desafío</button>
                        </div>
                    </form>
                </div>

                <!-- Listado de eventos -->
                <div class="bg-white rounded-xl shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Listado</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-600">
                                    <th class="py-2 px-3">Inicio</th>
                                    <th class="py-2 px-3">Fin</th>
                                    <th class="py-2 px-3">Título</th>
                                    <th class="py-2 px-3">Ubicación</th>
                                    <th class="py-2 px-3">Importante</th>
                                    <th class="py-2 px-3">Puntos</th>
                                    <th class="py-2 px-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="eventos-tbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <script>
            (function() {
                function combinarFechaHora(d, h) {
                    if (!d) return null;
                    if (!h) return d + ' 00:00:00';
                    return d + ' ' + h + ':00';
                }

                function formatoFecha(dt) {
                    if (!dt) return '';
                    try {
                        const f = new Date(dt.replace(' ', 'T'));
                        if (isNaN(f.getTime())) return dt;
                        return f.toLocaleString();
                    } catch (_) { return dt; }
                }

                function setAlert(msg, ok) {
                    const box = document.getElementById('evento-alert');
                    box.textContent = msg;
                    box.className = 'mb-4 p-3 rounded-lg ' + (ok ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800');
                }

                async function cargarEventos() {
                    try {
                        const res = await fetch('procesar_evento.php?listar_eventos=1', { credentials: 'include' });
                        const data = await res.json();
                        const lista = data.eventos || data.data || [];
                        const tbody = document.getElementById('eventos-tbody');
                        tbody.innerHTML = '';
                        if (!Array.isArray(lista) || lista.length === 0) {
                            tbody.innerHTML = '<tr><td class="py-2 px-3 text-gray-500" colspan="7">Sin desafíos</td></tr>';
                            return;
                        }
                        for (const ev of lista) {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td class="py-2 px-3">${formatoFecha(ev.fecha_inicio || ev.fecha_hora || '')}</td>
                                <td class="py-2 px-3">${formatoFecha(ev.fecha_fin || '')}</td>
                                <td class="py-2 px-3 font-medium text-gray-800">${ev.titulo || ''}</td>
                                <td class="py-2 px-3">${ev.ubicacion || ''}</td>
                                <td class="py-2 px-3">${(ev.importante == 1 || ev.importante === true) ? '<i class="fas fa-star text-orange-600"></i>' : ''}</td>
                                <td class="py-2 px-3">${(ev.puntos != null && ev.puntos !== '') ? ev.puntos : ''}</td>
                                <td class="py-2 px-3">
                                    ${ev.tipo === 'evento' ? `
                                        <button class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200" onclick="abrirSubidaFotos(${ev.id})">
                                            <i class="fas fa-images text-orange-600"></i> Subir
                                        </button>
                                        <input type="file" id="input-fotos-${ev.id}" class="hidden" multiple accept="image/*"/>
                                    ` : ''}
                                    <button class="px-3 py-1 rounded bg-red-100 hover:bg-red-200 ml-2" onclick="eliminarEvento(${ev.id})">
                                        <i class="fas fa-trash text-red-600"></i> Eliminar
                                    </button>
                                </td>
                            `;
                            tbody.appendChild(tr);
                        }
                    } catch (err) {
                        setAlert('Error cargando desafíos', false);
                    }
                }

                async function crearEvento(e) {
                    e.preventDefault();
                    const titulo = document.getElementById('titulo').value.trim();
                    const tipo = document.getElementById('tipo').value;
                    const descripcion = document.getElementById('descripcion').value.trim();
                    const ubicacion = document.getElementById('ubicacion').value.trim();
                    const importante = document.getElementById('importante').checked ? '1' : '0';
                    const puntosEventoEl = document.getElementById('puntos_evento');
                    const puntosEvento = puntosEventoEl ? puntosEventoEl.value.trim() : '';
                    const fi = combinarFechaHora(document.getElementById('fecha_inicio_dia').value, document.getElementById('fecha_inicio_hora').value);
                    const ff = combinarFechaHora(document.getElementById('fecha_fin_dia').value, document.getElementById('fecha_fin_hora').value);

                    if (!titulo || !fi || !ff) {
                        setAlert('Título, inicio y fin (fecha y hora) son obligatorios', false);
                        return;
                    }

                    const fd = new FormData();
                    fd.append('crear_evento', '1');
                    fd.append('titulo', titulo);
                    fd.append('tipo', tipo);
                    fd.append('descripcion', descripcion);
                    fd.append('fecha_inicio', fi || '');
                    fd.append('fecha_fin', ff || '');
                    fd.append('ubicacion', ubicacion);
                    fd.append('importante', importante);
                    if (puntosEvento !== '') { fd.append('puntos', puntosEvento); }

                    try {
                        const res = await fetch('procesar_evento.php', { method: 'POST', body: fd, credentials: 'include' });
                        const data = await res.json();
                        if (data.success) {
                            setAlert('Desafío creado correctamente', true);
                            document.getElementById('form-crear-evento').reset();
                            cargarEventos();
                        } else {
                            setAlert(data.message || 'No se pudo crear el desafío', false);
                        }
                    } catch (err) {
                        setAlert('Error al crear desafío', false);
                    }
                }

                // Subir fotos
                window.abrirSubidaFotos = function(eventoId) {
                    const input = document.getElementById('input-fotos-' + eventoId);
                    if (!input) return;
                    input.onchange = () => {
                        if (input.files && input.files.length) {
                            subirFotosEvento(eventoId, input.files);
                        }
                    };
                    input.click();
                };
                async function subirFotosEvento(eventoId, files) {
                    const fd = new FormData();
                    fd.append('accion', 'subir_fotos_evento');
                    fd.append('evento_id', String(eventoId));
                    Array.from(files).forEach(f => fd.append('fotos[]', f));
                    try {
                        const res = await fetch('procesar_evento.php', { method: 'POST', body: fd, credentials: 'include' });
                        const data = await res.json();
                        if (data.success) {
                            setAlert('Fotos subidas correctamente', true);
                        } else {
                            setAlert(data.message || 'No se pudieron subir las fotos', false);
                        }
                    } catch (e) {
                        setAlert('Error al subir fotos', false);
                    }
                }

                // Eliminar evento
                window.eliminarEvento = async function(eventoId) {
                    if (!confirm('¿Eliminar este evento? Esta acción no se puede deshacer.')) return;
                    const fd = new FormData();
                    fd.append('accion', 'eliminar_evento');
                    fd.append('evento_id', String(eventoId));
                    try {
                        const res = await fetch('procesar_evento.php', { method: 'POST', body: fd, credentials: 'include' });
                        const data = await res.json();
                        if (data.success) {
                            setAlert('Evento eliminado', true);
                            cargarEventos();
                        } else {
                            setAlert(data.message || 'No se pudo eliminar', false);
                        }
                    } catch (e) {
                        setAlert('Error al eliminar evento', false);
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('form-crear-evento');
                    if (form) form.addEventListener('submit', crearEvento);
                    // Cargar inicialmente
                    cargarEventos();
                    // Refrescar al abrir desde el sidebar
                    const link = document.querySelector('.sidebar-link[data-view="eventos-view"]');
                    if (link) link.addEventListener('click', () => setTimeout(cargarEventos, 50));
                });
            })();
            </script>
        </section>
        <?php endif; ?>
    </main>
    

    

    
    <!-- Modal Asignar Puntos (Nivel superior) -->
    <div id="modal-asignar-puntos" class="fixed inset-0 z-[9999] flex items-center justify-center hidden modal-bg">
        <div class="modal-content bg-white rounded-2xl shadow-2xl p-8 relative overflow-y-auto max-w-md w-full mx-4">
            <button id="close-modal-puntos" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl hover-scale z-10">&times;</button>
            
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                    <i class="fas fa-bolt text-orange-600"></i>
                    <span id="modal-title">Asignar Puntos</span>
                </h3>
                <p class="text-gray-600" id="participante-info">Gestionar puntos del participante</p>
            </div>

            <form id="form-asignar-puntos" class="space-y-4" method="post" action="procesar_participantes.php">
                <input type="hidden" name="action" value="assign_points" />
                <input type="hidden" name="participante_id" id="puntos-participante-id" />
                
                <!-- Selector de operación -->
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Operación *</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-300 transition-colors" id="asignar-label">
                            <input type="radio" name="operacion" value="asignar" checked class="mr-3 text-orange-600 focus:ring-orange-500">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-plus-circle text-green-600"></i>
                                <span class="font-medium">Asignar</span>
                            </div>
                        </label>
                        <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-orange-300 transition-colors" id="restar-label">
                            <input type="radio" name="operacion" value="restar" class="mr-3 text-orange-600 focus:ring-orange-500">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-minus-circle text-red-600"></i>
                                <span class="font-medium">Restar</span>
                            </div>
                        </label>
                    </div>
                </div>
                
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Cantidad de puntos *</label>
                    <input type="number" name="puntos" id="puntos" min="0" step="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500" placeholder="0" />
                </div>
                
                <div>
                    <label class="block text-gray-800 font-semibold mb-2">Motivo (opcional)</label>
                    <textarea name="motivo" id="motivo-puntos" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800"
                              placeholder="Ej: Participación en evento, completar actividad..."></textarea>
                </div>
                
                <div class="flex gap-3 pt-2">
                    <button type="button" id="cancel-modal-puntos" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">
                        Cancelar
                    </button>
                    <button style="background: linear-gradient(135deg, #fe6901 0%, #ff8c42 100%);" type="submit" class="flex-1 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300" id="submit-puntos-btn">
                        <i class="fas fa-bolt mr-2"></i>
                        <span id="submit-btn-text">Asignar Puntos</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>