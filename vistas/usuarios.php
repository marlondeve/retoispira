<?php
$mensaje_usuario = '';
$mensaje_tipo = '';
if (isset($_SESSION['mensaje_usuario'])) {
    $mensaje_usuario = $_SESSION['mensaje_usuario'];
    $mensaje_tipo = $_SESSION['mensaje_tipo'] ?? '';
    unset($_SESSION['mensaje_usuario'], $_SESSION['mensaje_tipo']);
}

// Obtener estadísticas de usuarios
$total_usuarios = $conn->query('SELECT COUNT(*) as total FROM usuarios')->fetch_assoc()['total'];
$usuarios_activos = $conn->query('SELECT COUNT(*) as activos FROM usuarios WHERE activo = 1')->fetch_assoc()['activos'];
$usuarios_admin = $conn->query('SELECT COUNT(*) as admins FROM usuarios WHERE rol = "admin"')->fetch_assoc()['admins'];
$usuarios_miembros = $conn->query('SELECT COUNT(*) as miembros FROM usuarios WHERE rol = "miembro"')->fetch_assoc()['miembros'];
?>

<div class="animate-fade-in-up">
    <!-- Header de la Vista -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-4xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                <i class="fas fa-users text-orange-600"></i>
                Gestión de Usuarios
            </h2>
            <p class="text-gray-600 text-lg">Administra los usuarios del sistema de manera eficiente</p>
        </div>
		<div class="flex items-center gap-4"></div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card rounded-2xl p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Usuarios</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $total_usuarios; ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-blue-700 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card rounded-2xl p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Usuarios Activos</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $usuarios_activos; ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user-check text-green-700 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card rounded-2xl p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Administradores</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $usuarios_admin; ?></p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-crown text-purple-700 text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="stat-card rounded-2xl p-6 hover-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Miembros</p>
                    <p class="text-3xl font-bold text-gray-800"><?php echo $usuarios_miembros; ?></p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-orange-700 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulario de Nuevo Usuario -->
        <div class="lg:col-span-1">
            <div class="card-modern rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-user-plus text-orange-600"></i>
                    Nuevo Usuario
                </h3>
                
                <?php if (!empty($mensaje_usuario)): ?>
                    <div class="mb-4 p-3 rounded-lg <?php echo $mensaje_tipo === 'exito' ? 'bg-green-100 text-green-800 border border-green-200' : 'bg-red-100 text-red-800 border border-red-200'; ?>">
                        <i class="fas <?php echo $mensaje_tipo === 'exito' ? 'fa-check-circle' : 'fa-exclamation-triangle'; ?> mr-2"></i>
                        <?php echo $mensaje_usuario; ?>
                    </div>
                <?php endif; ?>
                
                <form method="post" autocomplete="off" id="form-crear-usuario" action="procesar_usuario.php" class="space-y-4">
                    <input type="hidden" name="crear_usuario" value="1">
                    
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2" for="nombre_usuario">
                            <i class="fas fa-user text-orange-600 mr-2"></i>Nombre Completo
                        </label>
                        <input type="text" name="nombre_usuario" id="nombre_usuario" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800 transition-all duration-300"
                               placeholder="Ingresa el nombre completo">
                    </div>
                    
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2" for="email_usuario">
                            <i class="fas fa-envelope text-orange-600 mr-2"></i>Correo Electrónico
                        </label>
                        <input type="email" name="email_usuario" id="email_usuario" required 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800 transition-all duration-300"
                               placeholder="usuario@ejemplo.com">
                    </div>
                    
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2" for="password_usuario">
                            <i class="fas fa-lock text-orange-600 mr-2"></i>Contraseña
                        </label>
                        <input type="password" name="password_usuario" id="password_usuario" required minlength="6" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800 transition-all duration-300"
                               placeholder="Mínimo 6 caracteres">
                    </div>
                    
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2" for="rol_usuario">
                            <i class="fas fa-user-tag text-orange-600 mr-2"></i>Rol de Usuario
                        </label>
                        <select name="rol_usuario" id="rol_usuario" 
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800 transition-all duration-300">
                            <option value="miembro">👤 Miembro</option>
                            <option value="admin">👑 Administrador</option>
                        </select>
                    </div>
                    
                    <button type="submit" 
                            class="w-full btn-modern text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 hover-scale"
                            id="btn-crear-usuario">
                        <i class="fas fa-user-plus"></i>
                        <span>Crear Usuario</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Lista de Usuarios -->
        <div class="lg:col-span-2">
            <div class="card-modern rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-list text-orange-600"></i>
                    Lista de Usuarios
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-gray-200">
							<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-user mr-2"></i>Usuario
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-envelope mr-2"></i>Email
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-user-tag mr-2"></i>Rol
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <i class="fas fa-circle mr-2"></i>Estado
                                </th>
							<th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
								<i class="fas fa-cogs mr-2"></i>Acciones
							</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php
							$res_usuarios = $conn->query('SELECT id, nombre, email, rol, activo FROM usuarios ORDER BY nombre ASC');
                            while ($u = $res_usuarios->fetch_assoc()): ?>
							<tr class="hover:bg-gray-50 transition-colors duration-200" 
								data-id="<?php echo $u['id']; ?>" 
								data-nombre="<?php echo htmlspecialchars($u['nombre']); ?>" 
								data-email="<?php echo htmlspecialchars($u['email']); ?>" 
								data-rol="<?php echo htmlspecialchars($u['rol']); ?>" 
								data-activo="<?php echo (int)$u['activo']; ?>">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white font-semibold text-sm mr-3">
                                            <?php echo strtoupper(substr($u['nombre'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($u['nombre']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600"><?php echo htmlspecialchars($u['email']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($u['rol'] === 'admin'): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            <i class="fas fa-crown mr-1"></i>Admin
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-user mr-1"></i>Miembro
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($u['activo']): ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-circle text-green-500 mr-1 text-xs"></i>Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-circle text-red-500 mr-1 text-xs"></i>Inactivo
                                        </span>
                                    <?php endif; ?>
								</td>
								<td class="px-6 py-4 whitespace-nowrap">
									<div class="flex items-center gap-3">
										<button class="text-blue-600 hover:text-blue-800 font-semibold text-sm" onclick="abrirModalEditarUsuario(this)">
											<i class="fas fa-edit mr-1"></i>Editar
										</button>
										<button class="text-red-600 hover:text-red-800 font-semibold text-sm" onclick="abrirModalEliminarUsuario(this)">
											<i class="fas fa-trash-alt mr-1"></i>Eliminar
										</button>
									</div>
								</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Prevenir doble envío rápido
const form = document.getElementById('form-crear-usuario');
const btn = document.getElementById('btn-crear-usuario');

if (form && btn) {
    form.addEventListener('submit', function() {
        // Mostrar loading en el botón
        const originalText = btn.innerHTML;
        btn.innerHTML = '<div class="spinner w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Creando...';
        btn.disabled = true;
        
        // Restaurar después de 3 segundos
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 3000);
    });
}

// Animación de entrada para las filas de la tabla
document.addEventListener('DOMContentLoaded', function() {
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach((row, index) => {
        row.style.opacity = '0';
        row.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            row.style.transition = 'all 0.3s ease-out';
            row.style.opacity = '1';
            row.style.transform = 'translateY(0)';
        }, index * 100);
    });
});

// Modal Editar Usuario
function abrirModalEditarUsuario(btn) {
    const row = btn.closest('tr');
    const id = row.getAttribute('data-id');
    const nombre = row.getAttribute('data-nombre');
    const email = row.getAttribute('data-email');
    const rol = row.getAttribute('data-rol');
    const activo = row.getAttribute('data-activo');

    // Inyectar modal si no existe
    if (!document.getElementById('modal-editar-usuario')) {
        const modalHtml = `
        <div id="modal-editar-usuario" class="fixed inset-0 z-[9999] flex items-center justify-center hidden modal-bg">
            <div class="modal-content bg-white rounded-2xl shadow-2xl p-8 relative overflow-y-auto max-w-lg w-full mx-4">
                <button id="close-modal-editar-usuario" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl hover-scale z-10">&times;</button>
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                        <i class="fas fa-user-edit text-blue-600"></i>
                        Editar Usuario
                    </h3>
                    <p class="text-gray-600">Actualiza nombre, correo, contraseña y estado</p>
                </div>
                <form id="form-editar-usuario" class="space-y-4" method="post" action="procesar_usuario.php">
                    <input type="hidden" name="editar_usuario" value="1" />
                    <input type="hidden" name="usuario_id" id="edit_usuario_id" />
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Nombre</label>
                        <input type="text" name="nombre_usuario" id="edit_nombre" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-800" />
                    </div>
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Correo</label>
                        <input type="email" name="email_usuario" id="edit_email" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-800" />
                    </div>
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Nueva contraseña (opcional)</label>
                        <input type="password" name="password_usuario" id="edit_password" minlength="6" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-800" placeholder="Dejar en blanco para no cambiar" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Rol</label>
                            <select name="rol_usuario" id="edit_rol" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-800">
                                <option value="miembro">Miembro</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Estado</label>
                            <select name="activo" id="edit_activo" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white text-gray-800">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" id="cancelar-editar-usuario" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">Cancelar</button>
                        <button type="submit" class="flex-1 btn-modern text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">Guardar</button>
                    </div>
                </form>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        document.getElementById('close-modal-editar-usuario').addEventListener('click', () => document.getElementById('modal-editar-usuario').classList.add('hidden'));
        document.getElementById('cancelar-editar-usuario').addEventListener('click', () => document.getElementById('modal-editar-usuario').classList.add('hidden'));
    }

    document.getElementById('edit_usuario_id').value = id;
    document.getElementById('edit_nombre').value = nombre;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_rol').value = rol;
    document.getElementById('edit_activo').value = activo;
    document.getElementById('edit_password').value = '';
    document.getElementById('modal-editar-usuario').classList.remove('hidden');
}

// Modal Eliminar Usuario (Confirmación)
function abrirModalEliminarUsuario(btn) {
    const row = btn.closest('tr');
    const id = row.getAttribute('data-id');
    const nombre = row.getAttribute('data-nombre');
    
    if (!document.getElementById('modal-confirmar-eliminar-usuario')) {
        const modalHtml = `
        <div id="modal-confirmar-eliminar-usuario" class="fixed inset-0 z-[9999] flex items-center justify-center hidden modal-bg">
            <div class="modal-content bg-white rounded-2xl shadow-2xl p-8 relative overflow-y-auto max-w-md w-full mx-4">
                <button id="close-modal-confirmar-usuario" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl hover-scale z-10">&times;</button>
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                        <i class="fas fa-user-times text-red-600"></i>
                        Confirmar eliminación
                    </h3>
                    <p class="text-gray-600">¿Deseas inactivar al usuario <strong id="del_user_name"></strong>?</p>
                </div>
                <div class="flex gap-3 pt-2">
                    <button id="btn-cancelar-eliminar-usuario" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">Cancelar</button>
                    <button id="btn-confirmar-eliminar-usuario" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">Inactivar</button>
                </div>
            </div>
        </div>`;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        document.getElementById('close-modal-confirmar-usuario').addEventListener('click', () => document.getElementById('modal-confirmar-eliminar-usuario').classList.add('hidden'));
        document.getElementById('btn-cancelar-eliminar-usuario').addEventListener('click', () => document.getElementById('modal-confirmar-eliminar-usuario').classList.add('hidden'));
    }

    document.getElementById('del_user_name').textContent = nombre;
    document.getElementById('modal-confirmar-eliminar-usuario').classList.remove('hidden');
    document.getElementById('btn-confirmar-eliminar-usuario').onclick = function() {
        // Inactivar via POST
        const formData = new URLSearchParams();
        formData.append('inactivar_usuario', '1');
        formData.append('usuario_id', id);
        fetch('procesar_usuario.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: formData.toString() })
            .then(r => r.text())
            .then(() => { window.location.href = 'dashboard.php?view=usuarios-view'; });
    };
}
</script>

<style>
/* Estilos específicos para la vista de usuarios */
.hover-scale:hover {
    transform: scale(1.02);
}

.spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Animación de entrada para las cards */
.stat-card {
    animation: fadeInUp 0.6s ease-out;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }

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
</style> 