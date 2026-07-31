/**
 * Gestion des policies de congés par siège
 */
import '../css/conges-settings.css';

(function () {
    'use strict';

    const API_URL = '/admin/leave-policies/api';
    let policies = [];

    /* ---------- Chargement ---------- */
    async function loadPolicies() {
        const tbody = document.getElementById('tableBody');
        const badge = document.getElementById('countBadge');

        try {
            const res = await fetch(API_URL);
            if (!res.ok) throw new Error('HTTP ' + res.status);
            policies = await res.json();
            renderTable();
        } catch (e) {
            console.error(e);
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="empty" style="color:#dc2626;">
                            Erreur de chargement. Vérifiez les routes.
                        </td>
                    </tr>`;
            }
            if (badge) badge.textContent = 'Erreur';
        }
    }

    /* ---------- Rendu tableau ---------- */
    function renderTable() {
        const tbody = document.getElementById('tableBody');
        const badge = document.getElementById('countBadge');
        if (!tbody) return;

        const count = policies.length;
        if (badge) {
            badge.textContent = count + ' type' + (count > 1 ? 's' : '') + ' configuré' + (count > 1 ? 's' : '');
        }

        if (count === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="empty">
                        Aucun type de congé configuré pour ce siège.
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = policies.map(p => {
            const t = p.leave_type || {};
            const r = p.rules || {};
            return `
            <tr>
                <td>
                    <span class="color-dot" style="background:${escapeHtml(t.color)}"></span>
                    ${escapeHtml(t.name)}
                </td>
                <td><code>${escapeHtml(t.code)}</code></td>
                <td>${rulesSummary(r)}</td>
                <td>
                    <button class="badge-status" 
                            style="background:${p.is_active ? '#d1fae5' : '#fee2e2'};color:${p.is_active ? '#065f46' : '#991b1b'};border:none;cursor:pointer;"
                            onclick="window.togglePolicy(${p.id}, ${p.is_active ? 0 : 1})">
                        ${p.is_active ? 'Actif' : 'Inactif'}
                    </button>
                </td>
                <td class="actions">
                    <button class="btn btn-secondary btn-sm" onclick="window.editPolicy(${p.id})">⚙️ Règles</button>
                </td>
            </tr>
        `}).join('');
    }

    function rulesSummary(r) {
        const parts = [];
        if (r.max_per_year) parts.push(`${r.max_per_year}j/an`);
        if (r.min_notice_days) parts.push(`${r.min_notice_days}j préavis`);
        if (r.requires_approval_from) {
            const map = { manager: 'Manager', rh: 'RH', direction: 'Direction', manager_then_rh: 'Mgr→RH' };
            parts.push(map[r.requires_approval_from] || r.requires_approval_from);
        }
        if (r.allow_half_day) parts.push('½j autorisée');
        if (r.exclude_weekends) parts.push('hors WE');
        if (r.deducts_balance) parts.push('décompte solde');
        return parts.join(' · ') || '—';
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /* ---------- Modal ---------- */
    function openModal(id) {
        const modal = document.getElementById('modal');
        const formErrors = document.getElementById('formErrors');
        const modalTitle = document.getElementById('modalTitle');

        if (formErrors) formErrors.innerHTML = '';
        document.getElementById('policyId').value = id || '';

        if (modalTitle) {
            modalTitle.textContent = 'Modifier les règles';
        }

        const p = policies.find(x => x.id === id);
        if (!p) return;

        const t = p.leave_type || {};
        const r = p.rules || {};

        // Infos type global (lecture seule)
        setVal('typeName', t.name);
        setVal('typeCode', t.code);
        setVal('typeColor', t.color);

        // Règles modifiables
        setVal('min_notice_days', r.min_notice_days ?? 15);
        setVal('max_per_year', r.max_per_year ?? 25);
        setVal('max_consecutive_days', r.max_consecutive_days ?? 24);
        setVal('max_carryover_days', r.max_carryover_days ?? 5);
        setVal('min_duration_days', r.min_duration_days ?? 0.5);
        setVal('requires_approval_from', r.requires_approval_from ?? 'manager_then_rh');
        setVal('allow_half_day', r.allow_half_day ? '1' : '0');
        setVal('exclude_weekends', r.exclude_weekends ? '1' : '0');
        setVal('exclude_holidays', r.exclude_holidays ? '1' : '0');
        setVal('deducts_balance', r.deducts_balance ? '1' : '0');
        setVal('approval_required', r.approval_required ? '1' : '0');
        setVal('requires_attachment', r.requires_attachment ?? 'never');
        setVal('attachment_threshold', r.attachment_threshold ?? 0);
        setVal('allow_negative_balance', r.allow_negative_balance ? '1' : '0');
        setVal('negative_limit', r.negative_limit ?? 0);
        setVal('is_active', p.is_active ? '1' : '0');

        if (modal) modal.classList.add('active');
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        if (modal) modal.classList.remove('active');
    }

    function setVal(id, value) {
        const el = document.getElementById(id);
        if (el) el.value = value;
    }

    /* ---------- Sauvegarde ---------- */
    async function savePolicy() {
        const id = document.getElementById('policyId').value;
        if (!id) return;

        const payload = {
            rules: {
                min_notice_days: parseInt(document.getElementById('min_notice_days').value) || 0,
                max_per_year: parseInt(document.getElementById('max_per_year').value) || null,
                max_consecutive_days: parseInt(document.getElementById('max_consecutive_days').value) || null,
                max_carryover_days: parseInt(document.getElementById('max_carryover_days').value) || 0,
                min_duration_days: parseFloat(document.getElementById('min_duration_days').value) || 0.5,
                requires_approval_from: document.getElementById('requires_approval_from').value,
                allow_half_day: document.getElementById('allow_half_day').value === '1',
                exclude_weekends: document.getElementById('exclude_weekends').value === '1',
                exclude_holidays: document.getElementById('exclude_holidays').value === '1',
                deducts_balance: document.getElementById('deducts_balance').value === '1',
                approval_required: document.getElementById('approval_required').value === '1',
                requires_attachment: document.getElementById('requires_attachment').value,
                attachment_threshold: parseFloat(document.getElementById('attachment_threshold').value) || 0,
                allow_negative_balance: document.getElementById('allow_negative_balance').value === '1',
                negative_limit: parseFloat(document.getElementById('negative_limit').value) || 0,
            },
            is_active: document.getElementById('is_active').value === '1',
        };

        try {
            const res = await fetch(`${API_URL}/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken || '',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) {
                const data = await res.json().catch(() => ({}));
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    document.getElementById('formErrors').innerHTML = errors
                        .map(e => `<div class="form-error">• ${escapeHtml(e)}</div>`)
                        .join('');
                    return;
                }
                throw new Error('Erreur serveur');
            }

            closeModal();
            loadPolicies();
        } catch (e) {
            const errBox = document.getElementById('formErrors');
            if (errBox) {
                errBox.innerHTML = `<div class="form-error">Erreur réseau ou serveur.</div>`;
            }
        }
    }

    /* ---------- Activer / Désactiver ---------- */
    async function togglePolicy(id, newState) {
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

    /* ---------- Écouteurs ---------- */
    document.addEventListener('DOMContentLoaded', () => {
        loadPolicies();

        const modal = document.getElementById('modal');
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === this) closeModal();
            });
        }
    });

    // Exposer globalement
    window.editPolicy = openModal;
    window.closeModal = closeModal;
    window.savePolicy = savePolicy;
    window.togglePolicy = togglePolicy;
})();