<x-app-layout>

<style>
    select option { color: #111827 !important; background: #ffffff !important; }
</style>

    <div style="background:#f3f4f6; min-height:100vh; padding:32px 24px;">
        <div style="max-width:800px; margin:0 auto;">

            {{-- Header --}}
            <div style="margin-bottom:28px;">
                <h1 style="font-size:28px; font-weight:800; color:#111827; margin:0; letter-spacing:-0.5px;">Nouveau type de congé</h1>
                <p style="color:#6b7280; margin:6px 0 0 0; font-size:14px;">Créez une nouvelle catégorie de congés</p>
            </div>

            {{-- Card --}}
            <div style="background:white; border-radius:16px; box-shadow:0 1px 3px rgba(0,0,0,0.06); border:1px solid #e5e7eb; overflow:hidden;">

                @if($errors->any())
                    <div style="background:#fef2f2; border-left:4px solid #ef4444; color:#991b1b; padding:16px 20px; margin:24px 24px 0 24px; border-radius:8px;">
                        <div style="font-weight:700; font-size:13px; margin-bottom:6px;">⚠️ Veuillez corriger les erreurs suivantes :</div>
                        <ul style="margin:0; padding-left:18px; font-size:13px; line-height:1.8;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.leave-types.store') }}" method="POST" style="padding:28px 24px;">
                    @csrf

                    {{-- Siège --}}
                    @if(auth()->user()->isAdmin())
                    <div style="margin-bottom:24px; padding:20px; background:#f9fafb; border-radius:12px; border:1px solid #e5e7eb;">
                        <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.3px;">
                            Visibilité / Siège
                        </label>
                        <select name="site_id" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                            <option value="" style="color:#111827;">Global (tous les sièges)</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->ID }}" style="color:#111827;" {{ old('site_id') == $site->ID ? 'selected' : '' }}>
                                    {{ $site->Nom }}
                                </option>
                            @endforeach
                        </select>
                        <p style="color:#6b7280; font-size:12px; margin:6px 0 0 0;">Global = visible par tous. Spécifique = visible uniquement par ce siège.</p>
                    </div>
                    @endif

                    {{-- Nom + Code --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                Nom <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required maxlength="100"
                                   style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                        </div>
                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                Code <span style="color:#ef4444;">*</span>
                            </label>
                            <input type="text" name="code" value="{{ old('code') }}" required maxlength="20"
                                   style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box; text-transform:uppercase;">
                            <p style="color:#6b7280; font-size:12px; margin:6px 0 0 0;">Ex: CP, MAL, RTT, SS… (unique par siège)</p>
                        </div>
                    </div>

                    {{-- Unité + Couleur + Actif --}}
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; margin-bottom:24px;">
                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                Unité <span style="color:#ef4444;">*</span>
                            </label>
                            <select name="unit" style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                                <option value="days" style="color:#111827;" {{ old('unit','days')=='days'?'selected':'' }}>Jours</option>
                                <option value="half_days" style="color:#111827;" {{ old('unit')=='half_days'?'selected':'' }}>Demi-journées</option>
                                <option value="hours" style="color:#111827;" {{ old('unit')=='hours'?'selected':'' }}>Heures</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                Couleur <span style="color:#ef4444;">*</span>
                            </label>
                            @php
                                $defaultColor = old('color','#10B981');
                                if (!str_starts_with($defaultColor, '#')) $defaultColor = '#' . $defaultColor;
                            @endphp
                            <div style="display:flex; align-items:center; gap:10px;">
                                <input type="color" name="color" value="{{ $defaultColor }}" required
                                       style="width:50px; height:42px; border:1px solid #d1d5db; border-radius:8px; padding:2px; background:#ffffff; cursor:pointer;">
                                <span style="font-family:monospace; font-size:13px; color:#6b7280;">{{ strtoupper($defaultColor) }}</span>
                            </div>
                        </div>
                        <div style="display:flex; align-items:flex-end; padding-bottom:8px;">
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active',true) ? 'checked' : '' }}
                                       style="width:20px; height:20px; accent-color:#4f46e5; cursor:pointer;">
                                <span style="font-size:14px; font-weight:600; color:#374151;">Actif</span>
                            </label>
                        </div>
                    </div>

                    <hr style="border:none; border-top:1px solid #e5e7eb; margin:24px 0;">

                    {{-- Solde --}}
                    <div style="margin-bottom:20px;">
                        <h3 style="font-size:14px; font-weight:700; color:#374151; margin:0 0 14px 0; text-transform:uppercase; letter-spacing:0.3px;">Configuration du solde</h3>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                            <div style="padding:16px; background:#f9fafb; border-radius:10px; border:1px solid #e5e7eb;">
                                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                    <input type="checkbox" name="deducts_balance" value="1" {{ old('deducts_balance') ? 'checked' : '' }}
                                           style="width:18px; height:18px; accent-color:#4f46e5; cursor:pointer;">
                                    <span style="font-size:14px; color:#374151; font-weight:500;">Décompte du solde de congés</span>
                                </label>
                                <p style="color:#6b7280; font-size:12px; margin:6px 0 0 28px;">Le solde de l'employé sera décrémenté</p>
                            </div>
                            <div style="padding:16px; background:#f9fafb; border-radius:10px; border:1px solid #e5e7eb;">
                                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                    <input type="checkbox" name="allow_negative_balance" value="1" {{ old('allow_negative_balance') ? 'checked' : '' }}
                                           style="width:18px; height:18px; accent-color:#4f46e5; cursor:pointer;"
                                           onchange="document.getElementById('max-negative').style.display = this.checked ? 'block' : 'none'">
                                    <span style="font-size:14px; color:#374151; font-weight:500;">Solde négatif autorisé</span>
                                </label>
                                <div id="max-negative" style="margin-top:10px; {{ old('allow_negative_balance') ? '' : 'display:none;' }}">
                                    <input type="number" name="max_negative_limit" value="{{ old('max_negative_limit') }}"
                                           placeholder="Limite max (vide = illimité)"
                                           style="width:100%; padding:8px 12px; border:1px solid #d1d5db; border-radius:6px; font-size:13px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Justificatif --}}
                    <div style="margin-bottom:28px;">
                        <h3 style="font-size:14px; font-weight:700; color:#374151; margin:0 0 14px 0; text-transform:uppercase; letter-spacing:0.3px;">Justificatif requis</h3>
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                            <div>
                                <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                    Condition <span style="color:#ef4444;">*</span>
                                </label>
                                <select name="requires_attachment" id="requires_attachment"
                                        style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;"
                                        onchange="toggleAttachmentAfter()">
                                    <option value="never" style="color:#111827;" {{ old('requires_attachment','never')=='never'?'selected':'' }}>Jamais</option>
                                    <option value="always" style="color:#111827;" {{ old('requires_attachment')=='always'?'selected':'' }}>Toujours</option>
                                    <option value="after_duration" style="color:#111827;" {{ old('requires_attachment')=='after_duration'?'selected':'' }}>Après une durée</option>
                                </select>
                            </div>
                            <div id="attachment-after-wrapper" style="{{ old('requires_attachment')=='after_duration' ? '' : 'display:none;' }}">
                                <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                    À partir de (jours)
                                </label>
                                <input type="number" name="requires_attachment_after" id="requires_attachment_after"
                                       value="{{ old('requires_attachment_after') }}" min="1"
                                       style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                            </div>
                        </div>
                    </div>

                    {{-- Boutons --}}
                    <div style="display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top:1px solid #e5e7eb;">
                        <a href="{{ route('admin.leave-types.index') }}"
                           style="padding:10px 22px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; font-weight:600; color:#4b5563; text-decoration:none; background:#ffffff;">
                            Annuler
                        </a>
                        <button type="submit"
                                style="padding:10px 24px; background:linear-gradient(135deg,#4f46e5,#7c3aed); color:#ffffff; border:none; border-radius:8px; font-size:14px; font-weight:700; cursor:pointer; box-shadow:0 4px 14px rgba(79,70,229,0.35);">
                            ✓ Enregistrer
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>

    <script>
    function toggleAttachmentAfter() {
        const sel = document.getElementById('requires_attachment');
        const wrap = document.getElementById('attachment-after-wrapper');
        const input = document.getElementById('requires_attachment_after');
        if (sel.value === 'after_duration') {
            wrap.style.display = 'block';
            input.setAttribute('required','required');
        } else {
            wrap.style.display = 'none';
            input.removeAttribute('required');
            input.value = '';
        }
    }
    </script>
</x-app-layout>