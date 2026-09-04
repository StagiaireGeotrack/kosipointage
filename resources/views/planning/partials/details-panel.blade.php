<aside class="w-80 bg-white border border-slate-200 rounded-xl p-4 flex flex-col shadow-sm flex-shrink-0 overflow-y-auto" id="detail-panel">
    <div class="space-y-5" id="detail-content">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <h3 class="font-bold text-slate-800 text-sm">Détails de l'événement</h3>
            <button class="text-slate-400 hover:text-slate-600" id="close-detail">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="text-center py-8 text-slate-400">
            <i data-lucide="mouse-pointer-click" class="w-10 h-10 mx-auto mb-3 text-slate-300"></i>
            <p class="text-sm font-medium">Cliquez sur un événement</p>
            <p class="text-xs">pour voir les détails ici</p>
        </div>

        <!-- Légende -->
        <div class="pt-4 border-t border-slate-100 space-y-2">
            <h5 class="font-bold text-xs text-slate-800">Légende</h5>
            <div class="space-y-1.5 text-[11px]">
                <div class="flex items-center gap-2 text-green-700">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5"></i> Travail
                </div>
                <div class="flex items-center gap-2 text-amber-700">
                    <i data-lucide="coffee" class="w-3.5 h-3.5"></i> Pause déjeuner
                </div>
                <div class="flex items-center gap-2 text-purple-700">
                    <i data-lucide="graduation-cap" class="w-3.5 h-3.5"></i> Formation
                </div>
                <div class="flex items-center gap-2 text-amber-600">
                    <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Congé payé
                </div>
                <div class="flex items-center gap-2 text-pink-700">
                    <i data-lucide="hourglass" class="w-3.5 h-3.5"></i> RTT
                </div>
                <div class="flex items-center gap-2 text-orange-700">
                    <i data-lucide="navigation" class="w-3.5 h-3.5"></i> Déplacement professionnel
                </div>
                <div class="flex items-center gap-2 text-rose-700">
                    <i data-lucide="heart-pulse" class="w-3.5 h-3.5"></i> Maladie
                </div>
                <div class="flex items-center gap-2 text-blue-700">
                    <i data-lucide="user-x" class="w-3.5 h-3.5"></i> Absence autorisée
                </div>
                <div class="flex items-center gap-2 text-slate-500">
                    <i data-lucide="moon" class="w-3.5 h-3.5"></i> Repos
                </div>
            </div>
        </div>
    </div>
</aside>