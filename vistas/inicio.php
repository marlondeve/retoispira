<h2 class="text-3xl font-bold text-blue-900 mb-4">Inicio</h2>
<div class="bg-gradient-to-r from-blue-600 to-blue-400 rounded-xl shadow-lg p-8 text-white flex flex-col md:flex-row gap-8 items-center">
    <div>
        <h3 class="text-2xl font-bold mb-2">¡Bienvenido a tu Dashboard, <?php echo htmlspecialchars(
            isset(
                $nombre
            ) ? $nombre : ''
        ); ?>!</h3>
        <p class="text-lg">Gestiona Reto Inspira a tu gusto.</p>
    </div>
    <svg class="w-32 h-32 md:w-40 md:h-40" fill="none" stroke="currentColor" viewBox="0 0 48 48"><circle cx="24" cy="24" r="22" stroke-width="4" class="text-blue-200"/><path d="M16 24l6 6 10-10" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" class="text-white"/></svg>
</div> 