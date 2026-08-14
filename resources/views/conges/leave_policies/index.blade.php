<x-app-layout>
    <div style="background:#f3f4f6; min-height:100vh; padding:32px 24px;">
        <div style="max-width:1280px; margin:0 auto;">

            <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:28px;">
                <div>
                    <h1 style="font-size:30px; font-weight:800; color:#111827; margin:0; letter-spacing:-0.8px;">Règles de calcul</h1>
                    <p style="color:#6b7280; margin:6px 0 0 0; font-size:14px;">Méthodes de décompte des congés</p>
                </div>
                <a href="{{ route('admin.leave-policies.create') }}"
                   style="background:linear-gradient(135deg,#4f46e5,#7c3aed); color:white; text-decoration:none; padding:12px 24px; border-radius:10px; font-weight:600; font-size:14px; box-shadow:0 4px 14px rgba(79,70,229,0.35); display:inline-flex; align-items:center; gap:6px;">
                    + Nouvelle règle
                </a>
            </div>

            @if(session('success'))
                <div style="background:#dcfce7; border:1px solid #86efac; color:#166534; padding:14px 20px; border-radius:10px; margin-bottom:20px; font-size:14px; font-weight:500;">
                    ✓ {{ session('success') }}
                </div>
            @endif

            <div style="background:white; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06); overflow:hidden; border:1px solid #e5e7eb;">
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; font-size:14px;">
                        <thead>
                            <tr style="background:#f9fafb;">
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Nom</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Siège</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Méthode</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Week-end</th>
                                <th style="padding:18px 20px; text-align:left; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Arrondi</th>
                                <th style="padding:18px 20px; text-align:center; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Défaut</th>
                                <th style="padding:18px 20px; text-align:right; color:#6b7280; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.6px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($policies as $policy)
                            @php
                                $policyId = $policy->id ?? null;
                                $isGlobal = $policy->is_global ?? (method_exists($policy, 'isGlobal') ? $policy->isGlobal() : ($policy->site_id === null));
                                $siteName = $policy->site_name ?? ($policy->site->Nom ?? 'Global');
                                $isCustomizable = $policy->is_customizable ?? false;
                                $isOverridden = $policy->is_overridden ?? false;
                            @endphp
                            <tr style="border-top:1px solid #f3f4f6; transition:background 0.15s;"
                                onmouseover="this.style.background='#fafafa'"
                                onmouseout="this.style.background='white'">
                                <td style="padding:16px 20px; font-weight:700; color:#111827; font-size:14px;">
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        {{ $policy->name ?? '—' }}
                                        @if($isGlobal && $isCustomizable)
                                            <span style="background:#fef3c7; color:#d97706; padding:2px 8px; border-radius:6px; font-size:10px; font-weight:700;">PERSO.</span>
                                        @endif
                                        @if($isOverridden)
                                            <span style="background:#dbeafe; color:#2563eb; padding:2px 8px; border-radius:6px; font-size:10px; font-weight:700;">MODIFIÉ</span>
                                        @endif
                                    </div>
                                </td>
                                <td style="padding:16px 20px; color:#374151; font-size:13px; font-weight:500;">
                                    @if($isGlobal)
                                        <span style="background:#f3e8ff; color:#7c3aed; padding:5px 14px; border-radius:20px; font-size:12px; font-weight:600;">Global</span>
                                    @else
                                        <span style="color:#374151; font-size:13px; font-weight:500;">{{ $siteName }}</span>
                                    @endif
                                </td>
                                <td style="padding:16px 20px; color:#4b5563; font-size:13px;">
                                    @switch($policy->calculation_method ?? '')
                                        @case('working_days') Jours ouvrés @break
                                        @case('business_days') Jours ouvrables @break
                                        @case('hours') Heures @break
                                        @default {{ $policy->calculation_method ?? '' }}
                                    @endswitch
                                </td>
                                <td style="padding:16px 20px; color:#4b5563; font-size:13px;">
                                    @switch($policy->weekend_days ?? '')
                                        @case('saturday_sunday') Sam+Dim @break
                                        @case('friday_saturday') Ven+Sam @break
                                        @case('sunday_only') Dimanche @break
                                        @default {{ $policy->weekend_days ?? '' }}
                                    @endswitch
                                </td>
                                <td style="padding:16px 20px; color:#4b5563; font-size:13px;">
                                    @switch($policy->rounding_rule ?? '')
                                        @case('none') Aucun @break
                                        @case('half_day') Demi-journée @break
                                        @case('full_day') Journée entière @break
                                        @case('quarter_hour') 1/4h @break
                                        @case('half_hour') 1/2h @break
                                        @default {{ $policy->rounding_rule ?? '' }}
                                    @endswitch
                                </td>
                                <td style="padding:16px 20px; text-align:center;">
                                    @if($policy->is_default ?? false)
                                        <span style="background:#dcfce7; color:#166534; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700;">DÉFAUT</span>
                                    @else
                                        <span style="color:#9ca3af; font-size:12px;">—</span>
                                    @endif
                                </td>
                                <td style="padding:16px 20px; text-align:right; white-space:nowrap;">
                                    @php
                                        $canUpdate = auth()->user()->IsSuperAdmin || ! $isGlobal || ($isGlobal && $isCustomizable);
                                        $canDelete = auth()->user()->IsSuperAdmin || ! $isGlobal;
                                    @endphp
                                    @if($canUpdate && $policyId)
                                        <a href="{{ route('admin.leave-policies.edit', $policyId) }}"
                                           style="color:#4f46e5; text-decoration:none; font-weight:600; font-size:13px; margin-right:18px; padding:6px 0; display:inline-block;">
                                            Modifier
                                        </a>
                                    @endif
                                    @if($canDelete && $policyId)
                                        <form action="{{ route('admin.leave-policies.destroy', $policyId) }}" method="POST" style="display:inline;"
                                              onsubmit="return confirm('Supprimer cette règle ?');">
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
                                <td colspan="7" style="padding:60px 20px; text-align:center; color:#9ca3af;">
                                    <div style="font-size:48px; margin-bottom:12px;">📐</div>
                                    <div style="font-size:16px; font-weight:600; color:#6b7280;">Aucune règle de calcul</div>
                                    <div style="font-size:13px; margin-top:4px;">Créez votre première règle pour commencer</div>
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