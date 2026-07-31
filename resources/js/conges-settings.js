/**
 * Gestion des policies de congés par siège
 */
import '../css/conges-settings.css';

(function () {
    'use strict';

    const API_URL = '/admin/leave-policies/api';
    let items = [];
    let currentMode = 'edit';   // 'edit' ou 'create'
    let currentLeaveTypeId = null;

    /* ---------- Chargement ---------- */
    async function loadPolicies() {
        const tbody = document.getElementById('tableBody');
        const badge = document.getElementById('countBadge');

        try {
            const res = await fetch(API_URL);
            if (!res.ok) throw new Error('HTTP ' + res.status);
            items = await res.json();
            renderTable();
        } catch (e) {
            console.error('Erreur chargement:', e);
            if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="empty" style="color:#dc2626;">Erreur de chargement</td></tr>`;
            if (badge) badge.textContent = 'Erreur';
        }
    }

    /* ---------- Rendu tableau ---------- */
    function renderTable() {
        const tbody = document.getElementById('tableBody');
        const badge = document.getElementById('countBadge');
        if (!tbody) return;

        const configured = items.filter(i => i.configured).length;
        const total = items.length;

        if (badge) badge.textContent = `${configured} / ${total} type(s)`;

        if (total === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="empty">Aucun type global disponible.</td></tr>`;
            return;
        }

        tbody.innerHTML = items.map((item, idx) => {
            const t = item.leave_type || {};

            if (item.configured) {
                const p = item.policy;
                const r = p.rules || {};
                return `
                <tr data-idx="${idx}">
                    <td><span class="color-dot" style="background:${esc(t.color)}"></span> ${esc(t.name)}</td>
                    <td><code>${esc(t.code)}</code></td>
                    <td>${summary(r)}</td>
                    <td>
                        <button class="badge-status" style="background:${p.is_active?'#d1fae5':'#fee2e2'};color:${p.is_active?'#065f46':'#991b1b'};border:none;cursor:pointer;"
                            data-action="toggle" data-policy-id="${p.id}">
                            ${p.is_active ? 'Actif' : 'Inactif'}
                        </button>
                    </td>
                    <td class="actions">
                        <button class="btn btn-secondary btn-sm" data-action="edit" data-policy-id="${p.id}"> Modifier</button>
                    </td>
                </tr>`;
            } else {
                return `
                <tr data-idx="${idx}" style="background:#fffbeb;">
                    <td><span class="color-dot" style="background:${esc(t.color)};opacity:0.5;"></span> ${esc(t.name)}</td>
                    <td><code>${esc(t.code)}</code></td>
                    <td><span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:4px;font-size:12px;">Non configuré</span></td>
                    <td>-</td>
                    <td class="actions">
                       <button class="btn btn-sm" style="background-color: #dda4c4; color: white; border-color: #e3cae0;" data-action="configure" data-leave-type-id="${t.id}">
    ➕ Configurer
</button>
                    </td>
                </tr>`;
            }
        }).join('');
    }

    function summary(r) {
        const parts = [];
        if (r.max_per_year) parts.push(`${r.max_per_year}j/an`);
        if (r.min_notice_days) parts.push(`${r.min_notice_days}j préavis`);
        if (r.requires_approval_from) {
            const m = { manager: 'Manager', rh: 'RH', direction: 'Direction', manager_then_rh: 'Mgr→RH' };
            parts.push(m[r.requires_approval_from] || r.requires_approval_from);
        }
        if (r.allow_half_day) parts.push('½j autorisée');
        if (r.exclude_weekends) parts.push('hors WE');
        if (r.deducts_balance) parts.push('décompte solde');
        return parts.join(' · ') || '—';
    }

    function esc(text) {
        if (!text) return '';
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    /* ---------- Délégation clic sur le tableau ---------- */
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('button');
        if (!btn) return;

        const action = btn.dataset.action;
        if (!action) return;

        if (action === 'toggle') {
            const id = parseInt(btn.dataset.policyId);
            togglePolicy(id);
        } else if (action === 'edit') {
            const id = parseInt(btn.dataset.policyId);
            openEditModal(id);
        } else if (action === 'configure') {
            const leaveTypeId = parseInt(btn.dataset.leaveTypeId);
            openCreateModal(leaveTypeId);
        }
    });

    /* ---------- Modal Édition ---------- */
    function openEditModal(policyId) {
        currentMode = 'edit';
        currentLeaveTypeId = null;

        const item = items.find(i => i.policy && i.policy.id == policyId);
        if (!item) { console.error('Policy non trouvée:', policyId); return; }

        const t = item.leave_type;
        const p = item.policy;
        const r = p.rules || {};

        document.getElementById('modalTitle').textContent = 'Modifier les règles';
        document.getElementById('policyId').value = policyId;

        fillFields(t, r, p.is_active);
        showModal();
    }

    /* ---------- Modal Création ---------- */
    function openCreateModal(leaveTypeId) {
        currentMode = 'create';
        currentLeaveTypeId = leaveTypeId;

        const item = items.find(i => i.leave_type.id == leaveTypeId);
        if (!item) { console.error('Type non trouvé:', leaveTypeId); return; }

        const t = item.leave_type;

        document.getElementById('modalTitle').textContent = 'Configurer les règles';
        document.getElementById('policyId').value = ''; // ← VIDE = nouveau

        // Valeurs par défaut pour un nouveau type
        fillFields(t, {}, true);
        showModal();
    }

    function fillFields(type, rules, isActive) {
        set('typeName', type.name);
        set('typeCode', type.code);
        set('typeColor', type.color || '#3B82F6');

        set('min_notice_days',        rules.min_notice_days        ?? 15);
        set('max_per_year',           rules.max_per_year           ?? 25);
        set('max_consecutive_days',   rules.max_consecutive_days   ?? 24);
        set('max_carryover_days',     rules.max_carryover_days     ?? 5);
        set('min_duration_days',      rules.min_duration_days      ?? 0.5);
        set('requires_approval_from', rules.requires_approval_from ?? 'manager_then_rh');
        set('allow_half_day',         (rules.allow_half_day         ?? true) ? '1' : '0');
        set('exclude_weekends',       (rules.exclude_weekends       ?? true) ? '1' : '0');
        set('exclude_holidays',       (rules.exclude_holidays       ?? true) ? '1' : '0');
        set('deducts_balance',        (rules.deducts_balance        ?? true) ? '1' : '0');
        set('approval_required',      (rules.approval_required      ?? true) ? '1' : '0');
        set('requires_attachment',    rules.requires_attachment    ?? 'never');
        set('attachment_threshold',   rules.attachment_threshold   ?? 0);
        set('allow_negative_balance', (rules.allow_negative_balance ?? false) ? '1' : '0');
        set('negative_limit',         rules.negative_limit         ?? 0);
        set('is_active',              isActive ? '1' : '0');
    }

    function set(id, val) {
        const el = document.getElementById(id);
        if (el) el.value = val;
    }

    function showModal() {
        document.getElementById('formErrors').innerHTML = '';
        document.getElementById('modal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('modal').classList.remove('active');
        currentMode = 'edit';
        currentLeaveTypeId = null;
    }

    /* ---------- Sauvegarde ---------- */
    async function savePolicy() {
        const policyId = document.getElementById('policyId').value;
        const errBox = document.getElementById('formErrors');
        errBox.innerHTML = '';

        const payload = {
            rules: {
                min_notice_days:        parseInt(get('min_notice_days')) || 0,
                max_per_year:           parseInt(get('max_per_year')) || null,
                max_consecutive_days:   parseInt(get('max_consecutive_days')) || null,
                max_carryover_days:     parseInt(get('max_carryover_days')) || 0,
                min_duration_days:      parseFloat(get('min_duration_days')) || 0.5,
                requires_approval_from: get('requires_approval_from'),
                allow_half_day:         get('allow_half_day') === '1',
                exclude_weekends:       get('exclude_weekends') === '1',
                exclude_holidays:       get('exclude_holidays') === '1',
                deducts_balance:        get('deducts_balance') === '1',
                approval_required:      get('approval_required') === '1',
                requires_attachment:    get('requires_attachment'),
                attachment_threshold:   parseFloat(get('attachment_threshold')) || 0,
                allow_negative_balance: get('allow_negative_balance') === '1',
                negative_limit:         parseFloat(get('negative_limit')) || 0,
            },
            is_active: get('is_active') === '1',
        };

        let url, method;

        if (currentMode === 'edit' && policyId) {
            url = `${API_URL}/${policyId}`;
            method = 'PUT';
            console.log('Mode ÉDITION, PUT vers', url);
        } else if (currentMode === 'create' && currentLeaveTypeId) {
            url = API_URL;
            method = 'POST';
            payload.leave_type_id = currentLeaveTypeId;
            console.log('Mode CRÉATION, POST vers', url, 'leave_type_id:', currentLeaveTypeId);
        } else {
            errBox.innerHTML = '<div class="form-error">Erreur interne : mode inconnu</div>';
            console.error('Mode invalide:', currentMode, 'policyId:', policyId, 'leaveTypeId:', currentLeaveTypeId);
            return;
        }

        try {
            const res = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json().catch(() => ({}));

            if (!res.ok) {
                if (data.errors) {
                    const msgs = Object.values(data.errors).flat();
                    errBox.innerHTML = msgs.map(m => `<div class="form-error">• ${esc(m)}</div>`).join('');
                } else {
                    errBox.innerHTML = `<div class="form-error">${esc(data.message || 'Erreur serveur')}</div>`;
                }
                return;
            }

            closeModal();
            loadPolicies();

        } catch (e) {
            errBox.innerHTML = `<div class="form-error">Erreur réseau : ${esc(e.message)}</div>`;
        }
    }

    function get(id) {
        const el = document.getElementById(id);
        return el ? el.value : '';
    }

    /* ---------- Toggle actif/inactif ---------- */
    async function togglePolicy(id) {
        try {
            const res = await fetch(`${API_URL}/${id}/toggle`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken || '',
                    'Accept': 'application/json'
                }
            });
            if (!res.ok) throw new Error('HTTP ' + res.status);
            loadPolicies();
        } catch (e) {
            alert('Erreur lors du changement de statut.');
        }
    }

    /* ---------- Init ---------- */
    document.addEventListener('DOMContentLoaded', () => {
        loadPolicies();

        // Fermer modal en cliquant sur l'overlay
        document.getElementById('modal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    });

    // Exposer globalement
    window.closeModal = closeModal;
    window.savePolicy = savePolicy;
})();