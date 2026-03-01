<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 h-100 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500">
                <i data-lucide="file-text" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-slate-800">{{ $totalDokumen }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0">Total Dokumen</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 h-100 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-500">
                <i data-lucide="file-type-2" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-slate-800">{{ $pdfCount }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0">File PDF</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 h-100 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-500">
                <i data-lucide="file-spreadsheet" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-slate-800">{{ $excelCount }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0">File Excel</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="p-3 bg-white rounded-2xl shadow-sm border border-slate-100 h-100 flex items-center gap-3">
            <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center text-orange-500">
                <i data-lucide="layers" class="w-6 h-6"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-slate-800">{{ $categoryCount }}</h4>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-0">Kategori</p>
            </div>
        </div>
    </div>
</div>
