<x-app-layout>
    <div style="background:#f3f4f6; min-height:100vh; padding:32px 24px;">
        <div style="max-width:800px; margin:0 auto;">

            <div style="margin-bottom:28px;">
                <h1 style="font-size:28px; font-weight:800; color:#111827; margin:0; letter-spacing:-0.5px;">Nouvelle règle de calcul</h1>
                <p style="color:#6b7280; margin:6px 0 0 0; font-size:14px;">Définissez comment les congés sont décomptés</p>
            </div>

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

                <form action="{{ route('admin.leave-policies.store') }}" method="POST" style="padding:28px 24px;">
                    @csrf

                    @if(auth()->user()->IsSuperAdmin)
                    <div style="margin-bottom:24px; padding:20px; background:#f9fafb; border-radius:12px; border:1px solid #e5e7eb;">
                        <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:8px; text-transform:uppercase; letter-spacing:0.3px;">
                            Siège
                        </label>
                        <select name="site_id"
                                style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                            <option value="">Global (tous les sièges)</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->ID }}" {{ old('site_id') == $site->ID ? 'selected' : '' }}>
                                    {{ $site->Nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Personnalisable (uniquement visible si Global) --}}
                    <div id="customizableBox" style="margin-top:16px; padding:16px; background:#fffbeb; border:1px solid #fcd34d; border-radius:10px; display:none;">
                        <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                            <input type="checkbox" name="is_customizable" value="1" id="isCustomizable" {{ old('is_customizable') ? 'checked' : '' }}
                                   style="width:18px; height:18px; accent-color:#d97706; cursor:pointer;">
                            <span style="font-size:14px; font-weight:600; color:#92400e;">Personnalisable par les admins de siège</span>
                        </label>
                        <p style="color:#b45309; font-size:12px; margin:6px 0 0 28px;">
                            Si coché, chaque siège pourra adapter cette règle (méthode, week-end, arrondis...) sans impacter les autres.
                        </p>
                    </div>
                    @else
                        <input type="hidden" name="site_id" value="{{ auth()->user()->SiegeID }}">
                    @endif

                    <div style="margin-bottom:20px;">
                        <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                            Nom de la règle <span style="color:#ef4444;">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required maxlength="100"
                               placeholder="Ex: Standard France, Maroc Ouvrable..."
                               style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                Méthode de décompte <span style="color:#ef4444;">*</span>
                            </label>
                            <select name="calculation_method" required
                                    style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                                <option value="working_days" {{ old('calculation_method','working_days') == 'working_days' ? 'selected' : '' }}>Jours ouvrés (lun-ven, hors fériés)</option>
                                <option value="business_days" {{ old('calculation_method') == 'business_days' ? 'selected' : '' }}>Jours ouvrables (lun-sam, hors fériés)</option>
                                <option value="hours" {{ old('calculation_method') == 'hours' ? 'selected' : '' }}>Heures</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                Jours de week-end <span style="color:#ef4444;">*</span>
                            </label>
                            <select name="weekend_days" required
                                    style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                                <option value="saturday_sunday" {{ old('weekend_days','saturday_sunday') == 'saturday_sunday' ? 'selected' : '' }}>Samedi + Dimanche</option>
                                <option value="friday_saturday" {{ old('weekend_days') == 'friday_saturday' ? 'selected' : '' }}>Vendredi + Samedi</option>
                                <option value="sunday_only" {{ old('weekend_days') == 'sunday_only' ? 'selected' : '' }}>Dimanche seul</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                Gestion des jours fériés <span style="color:#ef4444;">*</span>
                            </label>
                            <select name="holiday_handling" required
                                    style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                                <option value="skip" {{ old('holiday_handling','skip') == 'skip' ? 'selected' : '' }}>Non décomptés (exclus)</option>
                                <option value="count" {{ old('holiday_handling') == 'count' ? 'selected' : '' }}>Décomptés</option>
                                <option value="split" {{ old('holiday_handling') == 'split' ? 'selected' : '' }}>Partagés (1/2 jour)</option>
                            </select>
                        </div>
                        <div>
                            <label style="display:block; font-size:13px; font-weight:700; color:#374151; margin-bottom:6px;">
                                Arrondis <span style="color:#ef4444;">*</span>
                            </label>
                            <select name="rounding_rule" required
                                    style="width:100%; padding:10px 14px; border:1px solid #d1d5db; border-radius:8px; font-size:14px; color:#111827; background:#ffffff; outline:none; box-sizing:border-box;">
                                <option value="none" {{ old('rounding_rule','none') == 'none' ? 'selected' : '' }}>Aucun</option>
                                <option value="half_day" {{ old('rounding_rule') == 'half_day' ? 'selected' : '' }}>Demi-journée</option>
                                <option value="full_day" {{ old('rounding_rule') == 'full_day' ? 'selected' : '' }}>Journée entière</option>
                                <option value="quarter_hour" {{ old('rounding_rule') == 'quarter_hour' ? 'selected' : '' }}>Quart d'heure</option>
                                <option value="half_hour" {{ old('rounding_rule') == 'half_hour' ? 'selected' : '' }}>Demi-heure</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:28px;">
                        <div style="padding:16px; background:#f9fafb; border-radius:10px; border:1px solid #e5e7eb;">
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                <input type="checkbox" name="exclude_holidays" value="1" {{ old('exclude_holidays', true) ? 'checked' : '' }}
                                       style="width:18px; height:18px; accent-color:#4f46e5; cursor:pointer;">
                                <span style="font-size:14px; color:#374151; font-weight:500;">Exclure les jours fériés</span>
                            </label>
                        </div>
                        <div style="padding:16px; background:#f9fafb; border-radius:10px; border:1px solid #e5e7eb;">
                            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                                <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                                       style="width:18px; height:18px; accent-color:#4f46e5; cursor:pointer;">
                                <span style="font-size:14px; color:#374151; font-weight:500;">Règle par défaut pour ce siège</span>
                            </label>
                        </div>
                    </div>

                    <div style="display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top:1px solid #e5e7eb;">
                        <a href="{{ route('admin.leave-policies.index') }}"
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
    document.addEventListener('DOMContentLoaded', function() {
        const siteSelect = document.querySelector('select[name="site_id"]');
        const box = document.getElementById('customizableBox');
        if (siteSelect && box) {
            siteSelect.addEventListener('change', function() {
                box.style.display = this.value === '' ? 'block' : 'none';
                if (this.value !== '') document.getElementById('isCustomizable').checked = false;
            });
            siteSelect.dispatchEvent(new Event('change'));
        }
    });
    </script>
</x-app-layout>