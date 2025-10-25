<?php
// Vista Participantes
?>
<div class="animate-fade-in-up">
    <div class="flex items-center justify-between mb-8">
        <div>
        <h2 class="text-4xl font-bold text-gray-900 mb-2 flex items-center gap-3">
            <i class="fas fa-user-plus text-gray-700"></i>
                Participantes
            </h2>
            <p class="text-gray-700 text-lg font-medium">Registra y gestiona los participantes.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulario -->
        <div class="lg:col-span-1">
            <div class="card-modern rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-gray-700"></i>
                    Nuevo participante
                </h3>
                <form id="form-participante" class="space-y-4">
                    <input type="hidden" name="action" value="add" />
                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">Nombre completo *</label>
                        <input type="text" name="nombre" id="nombre" required placeholder="Ej: Juan Pérez" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">Correo electrónico *</label>
                        <input type="email" name="correo" id="correo" required placeholder="Ej: juan@ejemplo.com" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">Teléfono (opcional)</label>
                        <input type="tel" name="telefono" id="telefono" placeholder="Ej: +56912345678" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">Organización (opcional)</label>
                        <input type="text" name="organizacion" id="organizacion" placeholder="Ej: Empresa ABC" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900" />
                    </div>
                    <div>
                        <label class="block text-gray-900 font-semibold mb-2">Cargo/Rol (opcional)</label>
                        <input type="text" name="cargo" id="cargo" placeholder="Ej: Desarrollador, Gerente" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-900" />
                    </div>
                    
                    <button type="submit" class="w-full btn-modern text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 hover-scale">
                        <i class="fas fa-save"></i>
                        <span>Registrar participante</span>
                    </button>
                </form>
            </div>
            <!-- Nuevo bloque Foto de Grupo -->
            <div class="card-modern rounded-2xl shadow-xl p-6 mt-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <i class="fas fa-image text-gray-700"></i>
                    Foto de Grupo
                </h3>
                <p class="text-gray-700 text-sm mb-4">Sube una nueva imagen JPG. Se reemplazará la foto actual y se eliminará la anterior.</p>
                <div class="flex items-center gap-3">
                    <button type="button" class="btn-modern text-white font-bold py-2 px-4 rounded-xl transition-all duration-300" onclick="cambiarFotoGrupo()">
                        <i class="fas fa-upload mr-2"></i>
                        Cambiar Foto de Grupo
                    </button>
                    <input type="file" id="input-foto-grupo" class="hidden" accept="image/jpeg" />
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="lg:col-span-2">
            <div class="card-modern rounded-2xl shadow-xl p-6 mb-6">
                <div class="flex flex-col sm:flex-row gap-4 sm:items-end sm:justify-between">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Toggle de vista -->
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Vista</label>
                            <select id="vista-toggle" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800">
                                <option value="completa">Información Completa</option>
                                <option value="puntuaciones">Tabla de Puntuaciones</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Buscar</label>
                            <input type="text" id="filtro-buscar" placeholder="Buscar por nombre o correo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800" />
                        </div>
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Organización</label>
                            <select id="filtro-organizacion" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800">
                                <option value="">Todas</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end gap-2">
                <button id="btn-aplicar-filtro" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                <i class="fas fa-filter"></i>
                                <span>Filtrar</span>
                            </button>
                            <button id="btn-limpiar-filtro" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                <span>Limpiar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-modern rounded-2xl shadow-xl p-6">
                <div class="overflow-x-auto">
                    <!-- Tabla completa -->
                    <table class="min-w-full" id="tabla-participantes-completa">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-3 text-center text-xs font-semibold text-orange-600 uppercase tracking-wider">Puntos</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Correo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teléfono</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Organización</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Cargo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha registro</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="tbody-participantes-completa"></tbody>
                    </table>
                    
                    <!-- Tabla de puntuaciones -->
                    <table class="min-w-full hidden" id="tabla-participantes-puntuaciones">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Correo</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-orange-600 uppercase tracking-wider">Puntos</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Teléfono</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="tbody-participantes-puntuaciones"></tbody>
                    </table>
                </div>
                
                <!-- Paginador -->
                <div class="mt-6 flex items-center justify-between">
                    <div class="stat-card rounded-2xl p-4">
                        <div class="text-gray-600 text-sm">Total de participantes registrados</div>
                        <div id="total-participantes" class="text-2xl font-bold text-gray-800">0</div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-sm text-gray-600">
                            Mostrando <span id="mostrando-desde">1</span> - <span id="mostrando-hasta">10</span> de <span id="total-registros">0</span> participantes
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="btn-prev" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <div id="paginador-numeros" class="flex items-center gap-1">
                                <!-- Los números de página se generarán dinámicamente -->
                            </div>
                            <button id="btn-next" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
(function() {
    // Prevenir múltiples inicializaciones
    if (window.participantesInicializado) {
        console.log('Participantes ya inicializado, saltando...');
        return;
    }
    window.participantesInicializado = true;
    
    console.log('Inicializando participantes...');
    
    // Variables de paginación
    let paginaActual = 1;
    const elementosPorPagina = 10;
    let totalElementos = 0;
    let totalPaginas = 0;
    
    // Limpiar URL de parámetros si los hay
    if (window.location.search.includes('action=add')) {
        const cleanUrl = window.location.pathname + '?view=participantes-view';
        window.history.replaceState({}, '', cleanUrl);
    }
    
    const tbodyCompleta = document.getElementById('tbody-participantes-completa');
    const tbodyPuntuaciones = document.getElementById('tbody-participantes-puntuaciones');
    const tablaCompleta = document.getElementById('tabla-participantes-completa');
    const tablaPuntuaciones = document.getElementById('tabla-participantes-puntuaciones');
    const vistaToggle = document.getElementById('vista-toggle');
    const filtroBuscar = document.getElementById('filtro-buscar');
    const filtroOrganizacion = document.getElementById('filtro-organizacion');
    const btnAplicar = document.getElementById('btn-aplicar-filtro');
    const btnLimpiar = document.getElementById('btn-limpiar-filtro');
    
    let participantesData = []; // Almacenar datos globalmente para cambio de vista
    
    // Debug de elementos DOM encontrados
    console.log('Elementos DOM encontrados:');
    console.log('- tbodyCompleta:', tbodyCompleta);
    console.log('- tbodyPuntuaciones:', tbodyPuntuaciones);
    console.log('- tablaCompleta:', tablaCompleta);
    console.log('- tablaPuntuaciones:', tablaPuntuaciones);
    console.log('- vistaToggle:', vistaToggle);
    console.log('- filtroBuscar:', filtroBuscar);
    console.log('- filtroOrganizacion:', filtroOrganizacion);

    function render(items) {
        console.log('Renderizando items:', items);
        participantesData = items; // Guardar datos globalmente
        
        // Obtener organizaciones únicas para el filtro
        const organizaciones = [...new Set(items.map(item => item.organizacion).filter(org => org && org.trim()))];
        filtroOrganizacion.innerHTML = '<option value="">Todas</option>';
        organizaciones.forEach(org => {
            const option = document.createElement('option');
            option.value = org;
            option.textContent = org;
            filtroOrganizacion.appendChild(option);
        });
        
        // Calcular paginación
        totalElementos = items.length;
        totalPaginas = Math.ceil(totalElementos / elementosPorPagina);
        
        // Asegurar que la página actual sea válida
        if (paginaActual > totalPaginas && totalPaginas > 0) {
            paginaActual = totalPaginas;
        }
        if (paginaActual < 1) {
            paginaActual = 1;
        }
        
        // Obtener elementos para la página actual
        const inicio = (paginaActual - 1) * elementosPorPagina;
        const fin = inicio + elementosPorPagina;
        const itemsPagina = items.slice(inicio, fin);
        
        console.log(`Página ${paginaActual} de ${totalPaginas}, mostrando ${itemsPagina.length} elementos`);
        
        // Renderizar según la vista seleccionada
        const vistaActual = vistaToggle ? vistaToggle.value : 'completa';
        console.log('Vista actual:', vistaActual);
        if (vistaActual === 'puntuaciones') {
            renderTablaPuntuaciones(itemsPagina);
        } else {
            renderTablaCompleta(itemsPagina);
        }
        
        // Actualizar contador y paginador
        document.getElementById('total-participantes').textContent = totalElementos;
        actualizarPaginador();
    }
    
    function renderTablaCompleta(items) {
        console.log('=== RENDER TABLA COMPLETA ===');
        console.log('Items recibidos:', items.length, 'items');
        console.log('Items data:', items);
        console.log('tbodyCompleta element:', tbodyCompleta);
        console.log('tbodyCompleta exists:', !!tbodyCompleta);
        
        if (!tbodyCompleta) {
            console.error('ERROR: tbodyCompleta no existe!');
            return;
        }
        
        console.log('Limpiando tbody...');
        tbodyCompleta.innerHTML = '';
        console.log('Tbody limpiado, comenzando loop de items...');
        
        items.forEach((it, index) => {
            console.log(`Procesando item ${index}:`, it);
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 transition-colors duration-200';
            console.log(`Creando fila ${index}, elemento tr:`, tr);
            
            const fechaRegistro = new Date(it.creado_en).toLocaleDateString('es-CL');
            const puntos = parseInt(it.puntos) || 0;
            
            tr.innerHTML = `
                <td class="px-6 py-3 whitespace-nowrap text-sm text-center">
                    <button style="background: linear-gradient(135deg, #fe6901 0%, #ff8c42 100%);"  data-id="${it.id}" data-nombre="${(it.nombre || '').replace(/"/g, '&quot;')}"  class=" text-white btn-asignar-puntos bg-orange-500 hover:bg-orange-600  px-3 py-1 rounded-lg text-xs font-medium transition-colors flex items-center gap-1 mx-auto">
                        <i class="fas fa-bolt text-orange-200"></i>
                        ${puntos}
                    </button>
                </td>
                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${it.nombre || ''}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${it.correo || ''}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${it.telefono || '-'}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${it.organizacion || '-'}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${it.cargo || '-'}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${fechaRegistro}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-right">
                    <button data-id="${it.id}" class="text-gray-600 hover:text-gray-800 font-semibold text-sm btn-editar">
                        <i class="fas fa-edit mr-1"></i>Editar
                    </button>
                    <button data-id="${it.id}" class="text-red-600 hover:text-red-800 font-semibold text-sm btn-eliminar ml-3">
                        <i class="fas fa-trash-alt mr-1"></i>Eliminar
                    </button>
                </td>
            `;
            tbodyCompleta.appendChild(tr);
            console.log(`Fila ${index} agregada al DOM`);
        });
        
        console.log('Total filas en tbody:', tbodyCompleta.children.length);
        
        // Mostrar tabla completa, ocultar puntuaciones
        tablaCompleta.classList.remove('hidden');
        tablaPuntuaciones.classList.add('hidden');
        console.log('Tablas mostradas/ocultadas');
        
        // Bind event listeners después de renderizar
        bindEventListeners();
    }
    
    function renderTablaPuntuaciones(items) {
        tbodyPuntuaciones.innerHTML = '';
        
        // Ordenar por puntos descendente
        const itemsOrdenados = [...items].sort((a, b) => (b.puntos || 0) - (a.puntos || 0));
        
        itemsOrdenados.forEach(it => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 transition-colors duration-200';
            
            const puntos = parseInt(it.puntos) || 0;
            
            tr.innerHTML = `
                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium text-gray-900">${it.nombre || ''}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${it.correo || ''}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${puntos > 0 ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-600'}">
                        <i class="fas fa-bolt mr-1 ${puntos > 0 ? 'text-orange-600' : 'text-gray-500'}"></i>
                        ${puntos}
                    </span>
                </td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${it.telefono || '-'}</td>
            `;
            tbodyPuntuaciones.appendChild(tr);
        });
        
        // Mostrar tabla puntuaciones, ocultar completa
        tablaPuntuaciones.classList.remove('hidden');
        tablaCompleta.classList.add('hidden');
        
        // Bind event listeners después de renderizar
        bindEventListeners();
    }
    
    function bindEventListeners() {
        // Bind delete
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (!id) return;
                if (!confirm('¿Eliminar este participante?')) return;
                const form = new URLSearchParams();
                form.append('action', 'delete');
                form.append('id', id);
                fetch('procesar_participantes.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: form.toString()
                }).then(r => r.text()).then(t => {
                    try {
                        const j = JSON.parse(t);
                        if (j.success) {
                            load();
                            if (window.mostrarNotificacion) {
                                window.mostrarNotificacion('Participante eliminado correctamente', 'success');
                            }
                        } else {
                            alert(j.message || 'Error al eliminar');
                        }
                    } catch (e) {
                        alert('Respuesta no válida del servidor (eliminar):\n' + t);
                    }
                }).catch(() => alert('Error de red al eliminar'));
            });
        });

        // Bind edit
        document.querySelectorAll('.btn-editar').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const row = this.closest('tr');
                if (!id || !row) return;
                
                // Construir modal de edición
                const modalId = 'modal-editar-participante';
                let modal = document.getElementById(modalId);
                if (!modal) {
                    const html = `
                    <div id="${modalId}" class="fixed inset-0 z-[9999] flex items-center justify-center hidden modal-bg">
                        <div class="modal-content bg-white rounded-2xl shadow-2xl p-8 relative overflow-y-auto max-w-lg w-full mx-4">
                            <button id="close-${modalId}" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl hover-scale z-10">&times;</button>
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Editar participante</h3>
                            <form id="form-${modalId}" class="space-y-4">
                                <input type="hidden" name="action" value="update" />
                                <input type="hidden" name="id" id="edit-id" />
                                <div>
                                    <label class="block text-gray-800 font-semibold mb-2">Nombre completo *</label>
                                    <input type="text" name="nombre" id="edit-nombre" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800" />
                                </div>
                                <div>
                                    <label class="block text-gray-800 font-semibold mb-2">Correo electrónico *</label>
                                    <input type="email" name="correo" id="edit-correo" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800" />
                                </div>
                                <div>
                                    <label class="block text-gray-800 font-semibold mb-2">Teléfono</label>
                                    <input type="tel" name="telefono" id="edit-telefono" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800" />
                                </div>
                                <div>
                                    <label class="block text-gray-800 font-semibold mb-2">Organización</label>
                                    <input type="text" name="organizacion" id="edit-organizacion" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800" />
                                </div>
                                <div>
                                    <label class="block text-gray-800 font-semibold mb-2">Cargo/Rol</label>
                                    <input type="text" name="cargo" id="edit-cargo" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white text-gray-800" />
                                </div>
                                <div class="flex gap-3 pt-2">
                                    <button type="button" id="cancel-${modalId}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">Cancelar</button>
                                    <button type="submit" class="flex-1 btn-modern text-white font-bold py-3 px-6 rounded-xl transition-all duration-300">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>`;
                    document.body.insertAdjacentHTML('beforeend', html);
                    modal = document.getElementById(modalId);
                    document.getElementById(`close-${modalId}`).addEventListener('click', () => modal.classList.add('hidden'));
                    document.getElementById(`cancel-${modalId}`).addEventListener('click', () => modal.classList.add('hidden'));

                    const formEdit = document.getElementById(`form-${modalId}`);
                    formEdit.addEventListener('submit', function(e){
                        e.preventDefault();
                        const fd = new FormData(formEdit);
                        fetch('procesar_participantes.php', { method: 'POST', body: fd })
                            .then(r => r.text())
                            .then(t => {
                                try {
                                    const j = JSON.parse(t);
                                    if (j.success) {
                                        modal.classList.add('hidden');
                                        load();
                                        if (window.mostrarNotificacion) {
                                            window.mostrarNotificacion('Participante actualizado correctamente', 'success');
                                        }
                                    } else {
                                        alert(j.message || 'Error al actualizar');
                                    }
                                } catch (e) {
                                    alert('Respuesta no válida del servidor (actualizar):\n' + t);
                                }
                            })
                            .catch(() => alert('Error de red al actualizar'));
                    });
                }
                
                // Prefill valores desde la fila (columna 0 es botón de puntos, datos empiezan en 1)
                const cols = row.querySelectorAll('td');
                const nombre = cols[1]?.textContent?.trim() || '';
                const correo = cols[2]?.textContent?.trim() || '';
                const telefono = cols[3]?.textContent?.trim() || '';
                const organizacion = cols[4]?.textContent?.trim() || '';
                const cargo = cols[5]?.textContent?.trim() || '';

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-nombre').value = nombre;
                document.getElementById('edit-correo').value = correo;
                document.getElementById('edit-telefono').value = telefono === '-' ? '' : telefono;
                document.getElementById('edit-organizacion').value = organizacion === '-' ? '' : organizacion;
                document.getElementById('edit-cargo').value = cargo === '-' ? '' : cargo;

                modal.classList.remove('hidden');
            });
        });
        
        // Bind asignar puntos
        document.querySelectorAll('.btn-asignar-puntos').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-nombre');
                if (!id) return;
                
                // Abrir modal de asignar puntos
                document.getElementById('puntos-participante-id').value = id;
                document.getElementById('puntos').value = '';
                document.getElementById('motivo-puntos').value = '';
                
                // Resetear a asignar por defecto
                document.querySelector('input[name="operacion"][value="asignar"]').checked = true;
                updateModalUI();
                
                document.getElementById('modal-asignar-puntos').classList.remove('hidden');
            });
        });
    }

    function buildQuery() {
        console.log('=== BUILDING QUERY ===');
        const params = new URLSearchParams();
        if (filtroBuscar && filtroBuscar.value) {
            console.log('Agregando filtro buscar:', filtroBuscar.value);
            params.set('buscar', filtroBuscar.value);
        }
        if (filtroOrganizacion && filtroOrganizacion.value) {
            console.log('Agregando filtro organización:', filtroOrganizacion.value);
            params.set('organizacion', filtroOrganizacion.value);
        }
        const queryString = params.toString();
        console.log('Query string final:', queryString);
        return queryString;
    }

    function load() {
        const qs = buildQuery();
        const url = 'procesar_participantes.php' + (qs ? '?' + qs : '');
        console.log('Cargando participantes desde:', url);
        fetch(url)
            .then(r => r.text())
            .then(t => {
                console.log('Respuesta del servidor:', t);
                try {
                    const j = JSON.parse(t);
                    console.log('JSON parseado:', j);
                    if (j.success) {
                        console.log('Items encontrados:', j.items?.length || 0);
                        render(j.items || []);
                    } else {
                        console.error('Error del servidor:', j.message);
                        alert(j.message || 'Error al cargar');
                    }
                } catch (e) {
                    console.error('Error parseando JSON:', e);
                    console.error('Respuesta raw:', t);
                    alert('Respuesta no válida del servidor (load):\n' + t);
                }
            })
            .catch((err) => { 
                console.error('Error de red:', err);
                alert('Error de red al cargar'); 
            });
    }

    if (btnAplicar) btnAplicar.addEventListener('click', function() {
        paginaActual = 1; // Resetear a la primera página
        load();
    });
    if (btnLimpiar) btnLimpiar.addEventListener('click', function(){
        if (filtroBuscar) filtroBuscar.value = '';
        if (filtroOrganizacion) filtroOrganizacion.value = '';
        paginaActual = 1; // Resetear a la primera página
        load();
    });
    
    // Event listener para cambio de vista
    if (vistaToggle) {
        vistaToggle.addEventListener('change', function() {
            paginaActual = 1; // Resetear a la primera página
            render(participantesData);
        });
    }
    
    // Función para actualizar la UI del modal según la operación seleccionada
    function updateModalUI() {
        const operacion = document.querySelector('input[name="operacion"]:checked').value;
        const modalTitle = document.getElementById('modal-title');
        const submitBtnText = document.getElementById('submit-btn-text');
        const submitBtn = document.getElementById('submit-puntos-btn');
        const asignarLabel = document.getElementById('asignar-label');
        const restarLabel = document.getElementById('restar-label');
        
        if (operacion === 'asignar') {
            modalTitle.textContent = 'Asignar Puntos';
            submitBtnText.textContent = 'Asignar Puntos';
            submitBtn.innerHTML = '<i class="fas fa-plus-circle mr-2"></i><span id="submit-btn-text">Asignar Puntos</span>';
            asignarLabel.classList.add('border-orange-500', 'bg-orange-50');
            restarLabel.classList.remove('border-orange-500', 'bg-orange-50');
        } else {
            modalTitle.textContent = 'Restar Puntos';
            submitBtnText.textContent = 'Restar Puntos';
            submitBtn.innerHTML = '<i class="fas fa-minus-circle mr-2"></i><span id="submit-btn-text">Restar Puntos</span>';
            restarLabel.classList.add('border-orange-500', 'bg-orange-50');
            asignarLabel.classList.remove('border-orange-500', 'bg-orange-50');
        }
    }

    // Función para actualizar el paginador
    function actualizarPaginador() {
        const desde = totalElementos === 0 ? 0 : (paginaActual - 1) * elementosPorPagina + 1;
        const hasta = Math.min(paginaActual * elementosPorPagina, totalElementos);
        
        document.getElementById('mostrando-desde').textContent = desde;
        document.getElementById('mostrando-hasta').textContent = hasta;
        document.getElementById('total-registros').textContent = totalElementos;
        
        // Actualizar botones de navegación
        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');
        
        btnPrev.disabled = paginaActual <= 1;
        btnNext.disabled = paginaActual >= totalPaginas;
        
        // Generar números de página
        const paginadorNumeros = document.getElementById('paginador-numeros');
        paginadorNumeros.innerHTML = '';
        
        if (totalPaginas <= 7) {
            // Mostrar todas las páginas si son 7 o menos
            for (let i = 1; i <= totalPaginas; i++) {
                const btn = crearBotonPagina(i);
                paginadorNumeros.appendChild(btn);
            }
        } else {
            // Mostrar páginas con elipsis
            const btn1 = crearBotonPagina(1);
            paginadorNumeros.appendChild(btn1);
            
            if (paginaActual > 4) {
                const elipsis1 = document.createElement('span');
                elipsis1.textContent = '...';
                elipsis1.className = 'px-2 text-gray-500';
                paginadorNumeros.appendChild(elipsis1);
            }
            
            const inicio = Math.max(2, paginaActual - 1);
            const fin = Math.min(totalPaginas - 1, paginaActual + 1);
            
            for (let i = inicio; i <= fin; i++) {
                const btn = crearBotonPagina(i);
                paginadorNumeros.appendChild(btn);
            }
            
            if (paginaActual < totalPaginas - 3) {
                const elipsis2 = document.createElement('span');
                elipsis2.textContent = '...';
                elipsis2.className = 'px-2 text-gray-500';
                paginadorNumeros.appendChild(elipsis2);
            }
            
            if (totalPaginas > 1) {
                const btnUltima = crearBotonPagina(totalPaginas);
                paginadorNumeros.appendChild(btnUltima);
            }
        }
    }
    
    function crearBotonPagina(numero) {
        const btn = document.createElement('button');
        btn.textContent = numero;
        btn.className = `px-3 py-2 text-sm font-medium rounded-lg transition-colors ${
            numero === paginaActual 
                ? 'bg-orange-600 text-white' 
                : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-700'
        }`;
        btn.addEventListener('click', () => irAPagina(numero));
        return btn;
    }
    
    function irAPagina(numero) {
        if (numero >= 1 && numero <= totalPaginas) {
            paginaActual = numero;
            render(participantesData);
        }
    }
    
    function paginaAnterior() {
        if (paginaActual > 1) {
            paginaActual--;
            render(participantesData);
        }
    }
    
    function paginaSiguiente() {
        if (paginaActual < totalPaginas) {
            paginaActual++;
            render(participantesData);
        }
    }

    // Event listeners para radio buttons
    document.querySelectorAll('input[name="operacion"]').forEach(radio => {
        radio.addEventListener('change', updateModalUI);
    });
    
    // Event listeners para paginador
    document.getElementById('btn-prev').addEventListener('click', paginaAnterior);
    document.getElementById('btn-next').addEventListener('click', paginaSiguiente);

    // Cambiar Foto de Grupo (JPG)
    window.cambiarFotoGrupo = function() {
        const input = document.getElementById('input-foto-grupo');
        if (!input) return;
        input.onchange = async () => {
            if (input.files && input.files.length) {
                const file = input.files[0];
                if (!file || file.type !== 'image/jpeg') {
                    if (window.mostrarNotificacion) { window.mostrarNotificacion('Solo se permite JPG para la foto de grupo', 'error'); }
                    input.value = '';
                    return;
                }
                const fd = new FormData();
                fd.append('accion', 'cambiar_foto_grupo');
                fd.append('foto_grupo', file);
                try {
                    const res = await fetch('procesar_config.php', { method: 'POST', body: fd, credentials: 'include' });
                    const dataText = await res.text();
                    let data;
                    try { data = JSON.parse(dataText); } catch(e) { data = null; }
                    if (data && data.success) {
                        if (window.mostrarNotificacion) { window.mostrarNotificacion('Foto de grupo actualizada', 'success'); }
                    } else {
                        const msg = (data && data.message) ? data.message : 'No se pudo actualizar la foto de grupo';
                        if (window.mostrarNotificacion) { window.mostrarNotificacion(msg, 'error'); } else { alert(msg); }
                    }
                } catch (e) {
                    if (window.mostrarNotificacion) { window.mostrarNotificacion('Error de red al subir la foto', 'error'); } else { alert('Error de red al subir la foto'); }
                } finally {
                    input.value = '';
                }
            }
        };
        input.click();
    };

    // Los event listeners para la modal de puntos ahora están en dashboard.php

    const form = document.getElementById('form-participante');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Enviando formulario de participante...');
            const fd = new FormData(form);
            console.log('FormData:', Array.from(fd.entries()));
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<div class="spinner w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Registrando...';
            }
            if (window.mostrarNotificacion) { window.mostrarNotificacion('Registrando participante...', 'success'); }
            fetch('procesar_participantes.php', { method: 'POST', body: fd })
                .then(r => r.text())
                .then(t => {
                    console.log('Respuesta del registro:', t);
                    try {
                        const j = JSON.parse(t);
                        console.log('JSON del registro:', j);
                        if (j.success) {
                            form.reset();
                            if (window.mostrarNotificacion) { window.mostrarNotificacion('Participante registrado correctamente', 'success'); }
                            load();
                        } else {
                            console.error('Error en registro:', j.message);
                            if (window.mostrarNotificacion) { window.mostrarNotificacion(j.message || 'Error al registrar', 'error'); }
                            else { alert(j.message || 'Error al registrar'); }
                        }
                    } catch (e) {
                        console.error('Error parseando respuesta de registro:', e);
                        console.error('Respuesta raw:', t);
                        if (window.mostrarNotificacion) { window.mostrarNotificacion('Respuesta no válida del servidor', 'error'); }
                        alert('Respuesta no válida del servidor (guardar):\n' + t);
                    }
                })
                .catch(() => { if (window.mostrarNotificacion) { window.mostrarNotificacion('Error de red al registrar', 'error'); } else { alert('Error de red al registrar'); } })
                .finally(() => { if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = originalText; } });
        });
    }
    
    // Formulario de asignar/restar puntos
    const formPuntos = document.getElementById('form-asignar-puntos');
    if (formPuntos && !window.puntosFormListenerAttached) {
        window.puntosFormListenerAttached = true;
        formPuntos.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(formPuntos);
            const operacion = fd.get('operacion');
            const submitBtn = formPuntos.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            
            if (submitBtn) {
                submitBtn.disabled = true;
                const actionText = operacion === 'asignar' ? 'Asignando...' : 'Restando...';
                submitBtn.innerHTML = `<div class="spinner w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> ${actionText}`;
            }
            
            const notificationText = operacion === 'asignar' ? 'Asignando puntos...' : 'Restando puntos...';
            if (window.mostrarNotificacion) { window.mostrarNotificacion(notificationText, 'success'); }
            
            fetch('procesar_participantes.php', { method: 'POST', body: fd })
                .then(r => r.text())
                .then(t => {
                    try {
                        const j = JSON.parse(t);
                        if (j.success) {
                            document.getElementById('modal-asignar-puntos').classList.add('hidden');
                            const successText = operacion === 'asignar' ? 'Puntos asignados correctamente' : 'Puntos restados correctamente';
                            if (window.mostrarNotificacion) { window.mostrarNotificacion(successText, 'success'); }
                            load();
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

    // Función de test manual
    window.testParticipantes = function() {
        console.log('=== TEST MANUAL ===');
        console.log('Probando procesar_participantes.php directamente...');
        fetch('procesar_participantes.php')
            .then(r => r.text())
            .then(t => {
                console.log('Respuesta RAW del servidor:', t);
                try {
                    const j = JSON.parse(t);
                    console.log('JSON parseado:', j);
                    console.log('Success:', j.success);
                    console.log('Items:', j.items);
                    console.log('Items length:', j.items?.length);
                } catch (e) {
                    console.error('Error parseando JSON:', e);
                }
            })
            .catch(e => console.error('Error fetch:', e));
    };

    // Carga inicial - con delay para asegurar que el DOM esté listo
    setTimeout(() => {
        console.log('Ejecutando carga inicial...');
        load();
    }, 100);
})();
</script>
