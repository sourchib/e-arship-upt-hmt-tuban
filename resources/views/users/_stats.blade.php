<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Total Pengguna -->
    <div class="bg-gradient-to-br from-indigo-600 to-violet-700 p-6 rounded-2xl shadow-sm border border-indigo-500/20 text-white">
        <div class="text-sm font-medium opacity-80 mb-1">Total Pengguna</div>
        <div class="text-3xl font-bold">{{ $stats['total'] }}</div>
    </div>

    <!-- Pengguna Aktif -->
    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-6 rounded-2xl shadow-sm border border-emerald-400/20 text-white">
        <div class="text-sm font-medium opacity-80 mb-1">Pengguna Aktif</div>
        <div class="text-3xl font-bold">{{ $stats['aktif'] }}</div>
    </div>

    <!-- Menunggu Persetujuan -->
    <div class="bg-gradient-to-br from-amber-500 to-orange-600 p-6 rounded-2xl shadow-sm border border-amber-400/20 text-white">
        <div class="text-sm font-medium opacity-80 mb-1">Menunggu Persetujuan</div>
        <div class="text-3xl font-bold">{{ $stats['pending'] }}</div>
    </div>

    <!-- Admin -->
    <div class="bg-gradient-to-br from-rose-500 to-red-600 p-6 rounded-2xl shadow-sm border border-rose-400/20 text-white">
        <div class="text-sm font-medium opacity-80 mb-1">Admin</div>
        <div class="text-3xl font-bold">{{ $stats['admin'] }}</div>
    </div>
</div>
