<?php
// Vista Finanzas
?>
<div class="animate-fade-in-up">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-4xl font-bold text-gray-800 mb-2 flex items-center gap-3">
                <i class="fas fa-chart-pie text-gray-700"></i>
                Finanzas
            </h2>
            <p class="text-gray-600 text-lg">Registra ingresos, gastos y costos.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulario -->
        <div class="lg:col-span-1">
            <div class="card-modern rounded-2xl shadow-xl p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-green-600"></i>
                    Nuevo registro
                </h3>
                <form id="form-finanza" class="space-y-4">
                    <input type="hidden" name="action" value="add" />
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Tipo</label>
                        <select name="tipo" id="tipo" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800">
                            <option value="ingreso">Ingreso</option>
                            <option value="gasto">Gasto</option>
                            <option value="costo">Costo</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Categoría</label>
                        <input type="text" name="categoria" id="categoria" placeholder="Ej: Proyecto, Nómina, Licencias" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800" />
                    </div>
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Descripción</label>
                        <input type="text" name="descripcion" id="descripcion" placeholder="Detalle del movimiento" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800" />
                    </div>
                    <div>
                        <label class="block text-gray-800 font-semibold mb-2">Monto</label>
                        <input type="number" step="0.01" name="monto" id="monto" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800" />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Mes</label>
                            <select name="mes" id="mes" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>" <?php echo ($m == (int)date('m')) ? 'selected' : ''; ?>><?php echo $m; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Año</label>
                            <input type="number" name="anio" id="anio" value="<?php echo date('Y'); ?>" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800" />
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full btn-modern text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 hover-scale">
                        <i class="fas fa-save"></i>
                        <span>Guardar</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Tabla y totales -->
        <div class="lg:col-span-2">
            <div class="card-modern rounded-2xl shadow-xl p-6 mb-6">
                <div class="flex flex-col sm:flex-row gap-4 sm:items-end sm:justify-between">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Proyección</label>
                            <select id="filtro-proyeccion" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800">
                                <option value="empresa" selected>Empresa</option>
                                <option value="usuario">Usuario</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Mes</label>
                            <select id="filtro-mes" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800">
                                <option value="" selected>Todos</option>
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Año</label>
                            <input type="number" id="filtro-anio" value="" placeholder="Todos" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800" />
                        </div>
                        <div>
                            <label class="block text-gray-800 font-semibold mb-2">Tipo</label>
                            <select id="filtro-tipo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800">
                                <option value="">Todos</option>
                                <option value="ingreso">Ingreso</option>
                                <option value="gasto">Gasto</option>
                                <option value="costo">Costo</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        
                        <div class="flex items-end gap-2">
                            <button id="btn-aplicar" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                <i class="fas fa-filter"></i>
                                <span>Filtrar</span>
                            </button>
                            <button id="btn-limpiar" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                <i class="fas fa-times"></i>
                                <span>Limpiar</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-modern rounded-2xl shadow-xl p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full" id="tabla-finanzas">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tipo</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Categoría</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Monto</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100" id="tbody-finanzas"></tbody>
                    </table>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                    <div class="stat-card rounded-2xl p-4">
                        <div class="text-gray-600 text-sm">Ingresos</div>
                        <div id="total-ingresos" class="text-2xl font-bold text-gray-800">$0</div>
                    </div>
                    <div class="stat-card rounded-2xl p-4">
                        <div class="text-gray-600 text-sm">Gastos</div>
                        <div id="total-gastos" class="text-2xl font-bold text-gray-800">$0</div>
                    </div>
                    <div class="stat-card rounded-2xl p-4">
                        <div class="text-gray-600 text-sm">Costos</div>
                        <div id="total-costos" class="text-2xl font-bold text-gray-800">$0</div>
                    </div>
                    <div class="stat-card rounded-2xl p-4">
                        <div class="text-gray-600 text-sm">Utilidad</div>
                        <div id="total-utilidad" class="text-2xl font-bold text-gray-800">$0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    const tbody = document.getElementById('tbody-finanzas');
    const filtroMes = document.getElementById('filtro-mes');
    const filtroAnio = document.getElementById('filtro-anio');
    const filtroTipo = document.getElementById('filtro-tipo');
    const filtroProyeccion = document.getElementById('filtro-proyeccion');
    const filtroUsuario = null;
    const wrapUsuarioFiltro = null;
    const btnAplicar = document.getElementById('btn-aplicar');
    const btnLimpiar = document.getElementById('btn-limpiar');

    function fmt(num) {
        const n = Number(num || 0);
        return '$' + n.toLocaleString('es-CL', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function toggleUsuarioFiltro() {}

    function render(items) {
        tbody.innerHTML = '';
        let sumIngresos = 0, sumGastos = 0, sumCostos = 0;
        items.forEach(it => {
            if (it.tipo === 'ingreso') sumIngresos += Number(it.monto);
            if (it.tipo === 'gasto') sumGastos += Number(it.monto);
            if (it.tipo === 'costo') sumCostos += Number(it.monto);

            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 transition-colors duration-200';
            tr.innerHTML = `
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${String(it.mes).padStart(2,'0')}/${it.anio}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm font-medium">
                    ${it.tipo === 'ingreso' ? '<span class="text-green-700">Ingreso</span>' : it.tipo === 'gasto' ? '<span class="text-red-700">Gasto</span>' : it.tipo === 'costo' ? '<span class="text-yellow-700">Costo</span>' : '<span class="text-gray-700">Otro</span>'}
                </td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${(it.categoria||'')}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700">${(it.descripcion||'')}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-right text-gray-900 font-semibold">${fmt(it.monto)}</td>
                <td class="px-6 py-3 whitespace-nowrap text-sm text-right">
                    <button data-id="${it.id}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm btn-editar">
                        <i class="fas fa-edit mr-1"></i>Editar
                    </button>
                    <button data-id="${it.id}" class="text-red-600 hover:text-red-800 font-semibold text-sm btn-eliminar ml-3">
                        <i class="fas fa-trash-alt mr-1"></i>Eliminar
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
        document.getElementById('total-ingresos').textContent = fmt(sumIngresos);
        document.getElementById('total-gastos').textContent = fmt(sumGastos);
        document.getElementById('total-costos').textContent = fmt(sumCostos);
        document.getElementById('total-utilidad').textContent = fmt(sumIngresos - sumGastos - sumCostos);

        // Bind delete
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                if (!id) return;
                if (!confirm('¿Eliminar este registro?')) return;
                const form = new URLSearchParams();
                form.append('action', 'delete');
                form.append('id', id);
                fetch('procesar_finanzas.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: form.toString()
                }).then(r => r.text()).then(t => {
                    try {
                        const j = JSON.parse(t);
                        if (j.success) {
                            load();
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
                // Construir modal simple inline
                const modalId = 'modal-editar-finanza';
                let modal = document.getElementById(modalId);
                if (!modal) {
                    const html = `
                    <div id="${modalId}" class="fixed inset-0 z-[9999] flex items-center justify-center hidden modal-bg">
                        <div class="modal-content bg-white rounded-2xl shadow-2xl p-8 relative overflow-y-auto max-w-lg w-full mx-4">
                            <button id="close-${modalId}" class="absolute top-4 right-4 text-gray-400 hover:text-gray-700 text-2xl hover-scale z-10">&times;</button>
                            <h3 class="text-2xl font-bold text-gray-800 mb-4">Editar registro</h3>
                            <form id="form-${modalId}" class="space-y-4">
                                <input type="hidden" name="action" value="update" />
                                <input type="hidden" name="id" id="edit-id" />
                                <div>
                                    <label class="block text-gray-800 font-semibold mb-2">Tipo</label>
                                    <select name="tipo" id="edit-tipo" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800">
                                        <option value="ingreso">Ingreso</option>
                                        <option value="gasto">Gasto</option>
                                        <option value="costo">Costo</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-gray-800 font-semibold mb-2">Categoría</label>
                                    <input type="text" name="categoria" id="edit-categoria" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800" />
                                </div>
                                <div>
                                    <label class="block text-gray-800 font-semibold mb-2">Descripción</label>
                                    <input type="text" name="descripcion" id="edit-descripcion" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-800 font-semibold mb-2">Monto</label>
                                        <input type="number" step="0.01" name="monto" id="edit-monto" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800" />
                                    </div>
                                    <div>
                                        <label class="block text-gray-800 font-semibold mb-2">Mes</label>
                                        <select name="mes" id="edit-mes" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800">
                                            <?php for ($m = 1; $m <= 12; $m++): ?>
                                                <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-800 font-semibold mb-2">Año</label>
                                        <input type="number" name="anio" id="edit-anio" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 bg-white text-gray-800" />
                                    </div>
                                    
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
                        fetch('procesar_finanzas.php', { method: 'POST', body: fd })
                            .then(r => r.text())
                            .then(t => {
                                try {
                                    const j = JSON.parse(t);
                                    if (j.success) {
                                        modal.classList.add('hidden');
                                        load();
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
                // Prefill de valores desde la fila
                const cols = row.querySelectorAll('td');
                const fecha = cols[0]?.textContent?.trim() || '';
                const tipoText = cols[1]?.textContent?.trim().toLowerCase() || '';
                const categoria = cols[2]?.textContent?.trim() || '';
                const descripcion = cols[3]?.textContent?.trim() || '';
                const montoText = cols[4]?.textContent?.trim().replace(/[^0-9]/g,'') || '0';
                const [mesStr, anioStr] = fecha.split('/');

                document.getElementById('edit-id').value = id;
                document.getElementById('edit-tipo').value = tipoText.includes('ingreso') ? 'ingreso' : tipoText.includes('gasto') ? 'gasto' : tipoText.includes('costo') ? 'costo' : 'otro';
                document.getElementById('edit-categoria').value = categoria;
                document.getElementById('edit-descripcion').value = descripcion;
                document.getElementById('edit-monto').value = parseInt(montoText || '0', 10);
                document.getElementById('edit-mes').value = parseInt(mesStr || '1', 10);
                document.getElementById('edit-anio').value = parseInt(anioStr || String(new Date().getFullYear()), 10);

                modal.classList.remove('hidden');
            });
        });
    }

    function buildQuery() {
        const params = new URLSearchParams();
        if (filtroMes && filtroMes.value) params.set('mes', filtroMes.value);
        if (filtroAnio && filtroAnio.value) params.set('anio', filtroAnio.value);
        if (filtroTipo && filtroTipo.value) params.set('tipo', filtroTipo.value);
        if (filtroProyeccion && filtroProyeccion.value) params.set('proyeccion', filtroProyeccion.value);
        // filtro de usuario removido
        return params.toString();
    }

    function load() {
        const qs = buildQuery();
        fetch('procesar_finanzas.php' + (qs ? '?' + qs : ''))
            .then(r => r.text())
            .then(t => {
                try {
                    const j = JSON.parse(t);
                    if (j.success) {
                        render(j.items || []);
                    } else {
                        alert(j.message || 'Error al cargar');
                    }
                } catch (e) {
                    alert('Respuesta no válida del servidor (load):\n' + t);
                }
            })
            .catch((err) => { alert('Error de red al cargar'); });
    }

    if (btnAplicar) btnAplicar.addEventListener('click', load);
    if (btnLimpiar) btnLimpiar.addEventListener('click', function(){
        if (filtroMes) filtroMes.value = '';
        if (filtroAnio) filtroAnio.value = '';
        if (filtroTipo) filtroTipo.value = '';
        if (filtroProyeccion) filtroProyeccion.value = 'empresa';
        
        toggleUsuarioFiltro();
        load();
    });
    if (filtroProyeccion) filtroProyeccion.addEventListener('change', function(){ toggleUsuarioFiltro(); });

    const form = document.getElementById('form-finanza');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const fd = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn ? submitBtn.innerHTML : '';
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<div class="spinner w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div> Guardando...';
            }
            if (window.mostrarNotificacion) { window.mostrarNotificacion('Guardando...', 'success'); }
            fetch('procesar_finanzas.php', { method: 'POST', body: fd })
                .then(r => r.text())
                .then(t => {
                    try {
                        const j = JSON.parse(t);
                        if (j.success) {
                            form.reset();
                            document.getElementById('mes').value = String(new Date().getMonth()+1);
                            document.getElementById('anio').value = String(new Date().getFullYear());
                            if (window.mostrarNotificacion) { window.mostrarNotificacion('Guardado correctamente', 'success'); }
                            load();
                        } else {
                            if (window.mostrarNotificacion) { window.mostrarNotificacion(j.message || 'Error al guardar', 'error'); }
                            else { alert(j.message || 'Error al guardar'); }
                        }
                    } catch (e) {
                        if (window.mostrarNotificacion) { window.mostrarNotificacion('Respuesta no válida del servidor', 'error'); }
                        alert('Respuesta no válida del servidor (guardar):\n' + t);
                    }
                })
                .catch(() => { if (window.mostrarNotificacion) { window.mostrarNotificacion('Error de red al guardar', 'error'); } else { alert('Error de red al guardar'); } })
                .finally(() => { if (submitBtn) { submitBtn.disabled = false; submitBtn.innerHTML = originalText; } });
        });
    }

    // Carga inicial
    toggleUsuarioFiltro();
    load();
})();
</script>

