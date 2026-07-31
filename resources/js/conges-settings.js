/**
 * Gestion des policies de congés par siège
 */
import '../css/conges-settings.css';

(function () {
    'use strict';

    const API_URL = '/admin/leave-policies/api';
    let items = []; // {leave_type, policy, configured}
    let currentMode = 'edit'; // 'edit' ou 'create'
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

        const configuredCount = items.filter(i => i.configured).length;
        const totalCount = items.length;

        if (badge) {
            badge.textContent = `${configuredCount} / ${totalCount} type(s) configuré(s)`;
        }

        if (totalCount === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="empty">
                        Aucun type de congé global disponible.
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = items.map(item => {
            const t = item.leave_type || {};

            if (item.configured) {
                const p = item.policy;
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
                        <button class="btn btn-secondary btn-sm" onclick="window.editPolicy(${p.id})">⚙️ Modifier</button>
                    </td>
                </tr>`;
            } else {
                return `
                <tr style="background:#fffbeb;">
                    <td>
                        <span class="color-dot" style="background:${escapeHtml(t.color)};opacity:0.5;"></span>
                        ${escapeHtml(t.name)}
                    </td>
                    <td><code>${escapeHtml(t.code)}</code></td>
                    <td>
                        <span class="badge" style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:4px;font-size:12px;">
                            Non configuré
                        </span>
                    </td>
                    <td>-</td>
                    <td class="actions">
                        <button class="btn btn-success btn-sm" onclick="window.configureType(${t.id})">
                            ➕ Configurer
                        </button>
                    </td>
                </tr>`;
            }
        }).join('');
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

    /* ---------- Modal : Édition ---------- */
    function openEditModal(policyId) {
        currentMode = 'edit';
        currentLeaveTypeId = null;

        const modal = document.getElementById('modal');
        const formErrors = document.getElementById('formErrors');
        const modalTitle = document.getElementById('modalTitle');

        if (formErrors) formErrors.innerHTML = '';
        document.getElementById('policyId').value = policyId;

        if (modalTitle) modalTitle.textContent = 'Modifier les règles';

        const item = items.find(i => i.policy && i.policy.id === policyId);
        if (!item) return;

        const t = item.leave_type || {};
        const p = item.policy;
        const r = p.rules || {};

        fillModal(t, r, p.is_active);
        if (modal) modal.classList.add('active');
    }

    /* ---------- Modal : Création ---------- */
    function openCreateModal(leaveTypeId) {
        currentMode = 'create';
        currentLeaveTypeId = leaveTypeId;

        const modal = document.getElementById('modal');
        const formErrors = document.getElementById('formErrors');
        const modalTitle = document.getElementById('modalTitle');

        if (formErrors) formErrors.innerHTML = '';
        document.getElementById('policyId').value = '';

        if (modalTitle) modalTitle.textContent = 'Configurer les règles';

        const item = items.find(i => i.leave_type.id === leaveTypeId);
        if (!item) return;

        // Valeurs par défaut
        fillModal(item.leave_type, {}, true);
        if (modal) modal.classList.add('active');
    }

    function fillModal(type, rules, isActive) {
        setVal('typeName', type.name);
        setVal('typeCode', type.code);
        setVal('typeColor', type.color || '#3B82F6');

        setVal('min_notice_days', rules.min_notice_days ?? 15);
        setVal('max_per_year', rules.max_per_year ?? 25);
        setVal('max_consecutive_days', rules.max_consecutive_days ?? 24);
        setVal('max_carryover_days', rules.max_carryover_days ?? 5);
        setVal('min_duration_days', rules.min_duration_days ?? 0.5);
        setVal('requires_approval_from', rules.requires_approval_from ?? 'manager_then_rh');
        setVal('allow_half_day', (rules.allow_half_day ?? true) ? '1' : '0');
        setVal('exclude_weekends', (rules.exclude_weekends ?? true) ? '1' : '0');
        setVal('exclude_holidays', (rules.exclude_holidays ?? true) ? '1' : '0');
        setVal('deducts_balance', (rules.deducts_balance ?? true) ? '1' : '0');
        setVal('approval_required', (rules.approval_required ?? true) ? '1' : '0');
        setVal('requires_attachment', rules.requires_attachment ?? 'never');
        setVal('attachment_threshold', rules.attachment_threshold ?? 0);
        setVal('allow_negative_balance', (rules.allow_negative_balance ?? false) ? '1' : '0');
        setVal('negative_limit', rules.negative_limit ?? 0);
        setVal('is_active', isActive ? '1' : '0');
    }

    function closeModal() {
        const modal = document.getElementById('modal');
        if (modal) modal.classList.remove('active');
        currentMode = 'edit';
        currentLeaveTypeId = null;
    }

    function setVal(id, value) {
        const el = document.getElementById(id);
        if (el) el.value = value;
    }

    /* ---------- Sauvegarde ---------- */
    async function savePolicy() {
        const policyId = document.getElementById('policyId').value;
        const formErrors = document.getElementById('formErrors');

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

        let url, method;

        if (currentMode === 'edit' && policyId) {
            url = `${API_URL}/${policyId}`;
            method = 'PUT';
        } else if (currentMode === 'create' && currentLeaveTypeId) {
            url = API_URL;
            method = 'POST';
            payload.leave_type_id = currentLeaveTypeId;
        } else {
            if (formErrors) formErrors.innerHTML = '<div class="form-error">Erreur interne.</div>';
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

            if (!res.ok) {
                const data = await res.json().catch(() => ({}));
                if (data.errors) {
                    const errors = Object.values(data.errors).flat();
                    if (formErrors) {
                        formErrors.innerHTML = errors
                            .map(e => `<div class="form-error">• ${escapeHtml(e)}</div>`)
                            .join('');
                    }
                    return;
                }
                throw new Error(data.message || 'Erreur serveur');
            }

            closeModal();
            loadPolicies();
        } catch (e) {
            if (formErrors) {
                formErrors.innerHTML = `<div class="form-error">${escapeHtml(e.message)}</div>`;
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
    window.editPolicy = openEditModal;
    window.configureType = openCreateModal;
    window.closeModal = closeModal;
    window.savePolicy = savePolicy;
    window.togglePolicy = togglePolicy;
})();