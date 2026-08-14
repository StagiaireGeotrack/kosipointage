<x-app-layout>

<style>
    select option { color: #111827 !important; background: #fff !important; }
</style>

    <div style="background:#f3f4f6; min-height:100vh; padding:32px 24px;">
        <div style="max-width:1280px; margin:0 auto;">

            {{-- Header --}}
            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px;">
                <div>
                    <h3 style="color:#6b7280; margin:6px 0 0 0; font-size:14px;">Gérez les catégories de congés de votre organisation</h3>
                </div>
                @can('create', \App\Models\LeaveType::class)
                    <a href="{{ route('admin.leave-types.create') }}"
                       style="background:linear-gradient(135deg,#4f46e5,#7c3aed); color:white; text-decoration:none; padding:12px 24px; border-radius:10px; font-weight:600; font-size:14px; box-shadow:0 4px 14px rgba(79,70,229,0.35); display:inline-flex; align-items:center; gap:6px;">
                        + Nouveau type
                    </a>
                @endcan
            </div>

            @if(session('success'))
                <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:14px 20px; border-radius:10px; margin-bottom:20px; font-size:14px; font-weight:500;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            {{-- Table Card --}}
            <div style="background:white; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e5e7eb;">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; font-size:14px;">
                        <thead>
                            <tr style="background:#f9fafb;">
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap;">Couleur</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap;">Nom</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap;">Code</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap;">Siège</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap;">Unité</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap;">Solde</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap;">Justificatif</th>
                                <th style="padding:18px 20px; text-align:center; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap;">Actif</th>
                                <th style="padding:18px 20px; text-align:right; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaveTypes as $lt)
                            @php
                                // Gère stdClass (admin site) ET Eloquent (super admin)
                                $isGlobal = $lt->is_global ?? (method_exists($lt, 'isGlobal') ? $lt->isGlobal() : false);
                                $siteName = $lt->site_name ?? ($lt->site->Nom ?? '—');
                                $isCustomizable = $lt->is_customizable ?? false;
                                $isOverridden = $lt->is_overridden ?? false;
                                $color = $lt->color ?? '#9ca3af';
                                if (!str_starts_with($color, '#')) $color = '#' . $color;
                            @endphp
                            <tr style="border-top:1px solid #f3f4f6; transition:background 0.15s;"
                                onmouseover="this.style.background='#fafafa'"
                                onmouseout="this.style.background='white'">
                                
                                {{-- Couleur --}}
                                <td style="padding:16px 20px;">
                                    <div style="width:32px; height:32px; border-radius:50%; background-color:{{ $color }}; box-shadow:0 0 0 4px {{ $color }}26; border:2px solid white;"></div>
                                </td>

                                {{-- Nom --}}
                                <td style="padding:16px 20px; font-weight:700; color:#111827; font-size:14px;">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        {{ $lt->name }}
                                        @if($isGlobal && $isCustomizable)
                                            <span style="background:#fef3c7; color:#d97706; padding:2px 8px; border-radius:6px; font-size:10px; font-weight:700;">PERSO.</span>
                                        @endif
                                        @if($isOverridden)
                                            <span style="background:#dbeafe; color:#2563eb; padding:2px 8px; border-radius:6px; font-size:10px; font-weight:700;">MODIFIÉ</span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Code --}}
                                <td style="padding:16px 20px;">
                                    <span style="background:#eef2ff; color:#4f46e5; padding:5px 12px; border-radius:8px; font-family:'SF Mono', monospace; font-size:12px; font-weight:700;">{{ $lt->code }}</span>
                                </td>

                                {{-- Siège --}}
                                <td style="padding:16px 20px;">
                                    @if($isGlobal)
                                        <span style="background:#f3e8ff; color:#7c3aed; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:600;">Global</span>
                                    @else
                                        <span style="color:#374151; font-size:13px; font-weight:500;">{{ $siteName }}</span>
                                    @endif
                                </td>

                                {{-- Unité --}}
                                <td style="padding:16px 20px; color:#4b5563; font-size:13px;">
                                    @switch($lt->unit)
                                        @case('days') Jours @break
                                        @case('half_days') Demi-journées @break
                                        @case('hours') Heures @break
                                        @default {{ $lt->unit }}
                                    @endswitch
                                </td>

                                {{-- Solde --}}
                                <td style="padding:16px 20px; font-size:13px;">
                                    @if($lt->deducts_balance)
                                        <span style="color:#dc2626; font-weight:700;">Décompte</span>
                                    @else
                                        <span style="color:#6b7280;">Sans décompte</span>
                                    @endif
                                    @if($lt->allow_negative_balance)
                                        <div style="color:#f59e0b; font-size:11px; margin-top:3px; font-weight:500;">
                                            (négatif autorisé {{ $lt->max_negative_limit ? 'max '.$lt->max_negative_limit : 'illimité' }})
                                        </div>
                                    @endif
                                </td>

                                {{-- Justificatif --}}
                                <td style="padding:16px 20px; color:#4b5563; font-size:13px;">
                                    @switch($lt->requires_attachment)
                                        @case('never') <span style="color:#9ca3af;">Jamais</span> @break
                                        @case('always') <span style="color:#dc2626; font-weight:600;">Toujours</span> @break
                                        @case('after_duration') Après <strong style="color:#111827;">{{ $lt->requires_attachment_after }}</strong> j @break
                                    @endswitch
                                </td>

                                {{-- Actif --}}
                                <td style="padding:16px 20px; text-align:center;">
                                    @if($lt->is_active)
                                        <span style="display:inline-block; width:10px; height:10px; background:#22c55e; border-radius:50%; box-shadow:0 0 0 4px rgba(34,197,94,0.25);" title="Actif"></span>
                                    @else
                                        <span style="display:inline-block; width:10px; height:10px; background:#d1d5db; border-radius:50%; box-shadow:0 0 0 4px rgba(209,213,219,0.25);" title="Inactif"></span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                                                {{-- Actions --}}
                                <td style="padding:16px 20px; text-align:right; white-space:nowrap;">
                                    @php
                                        $isGlobal = $lt->is_global ?? (method_exists($lt, 'isGlobal') ? $lt->isGlobal() : false);
                                        $isCustomizable = $lt->is_customizable ?? false;
                                        $canUpdate = auth()->user()->IsSuperAdmin || ! $isGlobal || ($isGlobal && $isCustomizable);
                                        $canDelete = auth()->user()->IsSuperAdmin || ! $isGlobal;
                                    @endphp
                                    @if($canUpdate)
                                        <a href="{{ route('admin.leave-types.edit', $lt->id) }}"
                                           style="color:#4f46e5; text-decoration:none; font-weight:600; font-size:13px; margin-right:18px; padding:6px 0; display:inline-block;">
                                            Modifier
                                        </a>
                                    @endif
                                    @if($canDelete)
                                        <form action="{{ route('admin.leave-types.destroy', $lt->id) }}" method="POST" style="display:inline;"
                                              onsubmit="return confirm('Supprimer définitivement ce type de congé ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    style="background:#fef2f2; color:#dc2626; border:none; padding:7px 16px; border-radius:8px; font-size:12px; font-weight:700; cursor:pointer; transition:all 0.15s;"
                                                    onmouseover="this.style.background='#fee2e2'"
                                                    onmouseout="this.style.background='#fef2f2'">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="padding:60px 20px; text-align:center; color:#9ca3af;">
                                    <div style="font-size:48px; margin-bottom:12px;">📋</div>
                                    <div style="font-size:16px; font-weight:600; color:#6b7280;">Aucun type de congé trouvé</div>
                                    <div style="font-size:13px; margin-top:4px;">Créez votre premier type de congé pour commencer</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>