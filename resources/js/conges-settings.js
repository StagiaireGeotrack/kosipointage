/**
 * Gestion des policies de congés par siège — FORMULAIRE DYNAMIQUE
 */
import '../css/conges-settings.css';

(function () {
    'use strict';

    const API_URL = '/admin/leave-policies/api';
    let items = [];
    let currentMode = 'edit'; // 'edit' ou 'create'

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
                return `
                <tr data-idx="${idx}">
                    <td><span class="color-dot" style="background:${esc(t.color)}"></span> ${esc(t.name)}</td>
                    <td><code>${esc(t.code)}</code></td>
                    <td>${summary(item)}</td>
                    <td>
                        <button class="badge-status" style="background:${item.policy.is_active?'#d1fae5':'#fee2e2'};color:${item.policy.is_active?'#065f46':'#991b1b'};border:none;cursor:pointer;"
                            data-action="toggle" data-policy-id="${item.policy.id}">
                            ${item.policy.is_active ? 'Actif' : 'Inactif'}
                        </button>
                    </td>
                    <td class="actions">
                        <button class="btn btn-secondary btn-sm" data-action="edit" data-policy-id="${item.policy.id}">Modifier</button>
                    </td>
                </tr>`;
            } else {
                return `
                <tr data-idx="${idx}" style="background:#fffbeb;">
                    <td><span class="color-dot" style="background:${esc(t.color)};opacity:0.5;"></span> ${esc(t.name)}</td>
                    <td><code>${esc(t.code)}</code></td>
                    <td>${summary(item)}</td>
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

    function summary(item) {
        if (!item.configured) {
            return '<span style="background:#fef3c7;color:#92400e;padding:2px 8px;border-radius:4px;font-size:12px;">Non configuré</span>';
        }
        const values = item.policy_values || {};
        const fields = item.rule_fields || [];
        const parts = [];

        fields.slice(0, 4).forEach(f => {
            const v = values[f.field_key];
            if (v !== undefined && v !== null && v !== '') {
                let display = v;
                if (f.field_type === 'boolean') display = (v == '1' || v === true) ? 'Oui' : 'Non';
                parts.push(`<span style="white-space:nowrap;">${esc(f.label)}: <strong>${esc(display)}</strong></span>`);
            }
        });

        return parts.join(' · ') || '<span style="color:#64748b;font-size:12px;">Configuré</span>';
    }

    function esc(text) {
        if (!text) return '';
        const d = document.createElement('div');
        d.textContent = text;
        return d.innerHTML;
    }

    /* ---------- Délégation clic ---------- */
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

    /* ---------- Modals ---------- */
    function openCreateModal(leaveTypeId) {
        currentMode = 'create';
        const item = items.find(i => i.leave_type.id == leaveTypeId);
        if (!item) { console.error('Type non trouvé:', leaveTypeId); return; }

        document.getElementById('modalTitle').textContent = 'Configurer les règles';
        document.getElementById('policyId').value = '';
        document.getElementById('leaveTypeId').value = leaveTypeId;

        setTypeInfo(item.leave_type);
        buildDynamicForm(item.rule_fields, item.default_values);
        document.getElementById('is_active').value = '1';

        showModal();
    }

    function openEditModal(policyId) {
        currentMode = 'edit';
        const item = items.find(i => i.policy && i.policy.id == policyId);
        if (!item) { console.error('Policy non trouvée:', policyId); return; }

        document.getElementById('modalTitle').textContent = 'Modifier les règles';
        document.getElementById('policyId').value = policyId;
        document.getElementById('leaveTypeId').value = '';

        setTypeInfo(item.leave_type);
        buildDynamicForm(item.rule_fields, item.policy_values);
        document.getElementById('is_active').value = item.policy.is_active ? '1' : '0';

        showModal();
    }

    function setTypeInfo(type) {
        document.getElementById('typeName').value = type.name;
        document.getElementById('typeCode').value = type.code;
        document.getElementById('typeColor').value = type.color || '#3B82F6';
    }

    /* ---------- Construction formulaire dynamique ---------- */
    function buildDynamicForm(ruleFields, values) {
        const container = document.getElementById('dynamicFormContainer');
        container.innerHTML = '';

        if (!ruleFields || ruleFields.length === 0) {
            container.innerHTML = '<p style="grid-column: 1/-1; color: #94a3b8; text-align: center; padding: 2rem;">Ce type n\'a pas encore de champs configurés. Ajoutez-en dans le catalogue des types.</p>';
            return;
        }

        ruleFields.forEach(field => {
            const val = values[field.field_key] !== undefined ? values[field.field_key] : (field.default_value ?? '');
            const required = (field.validation?.required) ? ' *' : '';

            const wrapper = document.createElement('div');
            wrapper.className = 'form-group';
            wrapper.setAttribute('data-field-key', field.field_key);

            let inputHtml = '';

            switch (field.field_type) {
                case 'number': {
                    const step = field.validation?.step || 'any';
                    const min = field.validation?.min !== undefined ? `min="${field.validation.min}"` : '';
                    const max = field.validation?.max !== undefined ? `max="${field.validation.max}"` : '';
                    inputHtml = `<input type="number" id="rf_${field.field_key}" name="values[${field.field_key}]" class="form-control" value="${esc(val)}" step="${step}" ${min} ${max}>`;
                    break;
                }
                case 'boolean':
                    inputHtml = `<select id="rf_${field.field_key}" name="values[${field.field_key}]" class="form-control">
                        <option value="1" ${val == '1' || val === true ? 'selected' : ''}>Oui</option>
                        <option value="0" ${val == '0' || val === false ? 'selected' : ''}>Non</option>
                    </select>`;
                    break;
                case 'select': {
                    const options = (field.options || []).map(opt =>
                        `<option value="${esc(opt.value)}" ${val == opt.value ? 'selected' : ''}>${esc(opt.label)}</option>`
                    ).join('');
                    inputHtml = `<select id="rf_${field.field_key}" name="values[${field.field_key}]" class="form-control">${options}</select>`;
                    break;
                }
                case 'text':
                    inputHtml = `<input type="text" id="rf_${field.field_key}" name="values[${field.field_key}]" class="form-control" value="${esc(val)}">`;
                    break;
                case 'formula':
                    inputHtml = `<textarea id="rf_${field.field_key}" name="values[${field.field_key}]" class="form-control" rows="2" readonly>${esc(val)}</textarea><small style="color:#94a3b8; display:block; margin-top:4px;">Formule de calcul (lecture seule)</small>`;
                    break;
                default:
                    inputHtml = `<input type="text" id="rf_${field.field_key}" name="values[${field.field_key}]" class="form-control" value="${esc(val)}">`;
            }

            let hint = '';
            if (field.validation?.min !== undefined || field.validation?.max !== undefined) {
                hint = `<small style="color:#94a3b8; display:block; margin-top:4px;">`;
                if (field.validation.min !== undefined) hint += `Min: ${field.validation.min}`;
                if (field.validation.max !== undefined) hint += ` / Max: ${field.validation.max}`;
                hint += `</small>`;
            }

            wrapper.innerHTML = `
                <label for="rf_${field.field_key}">${esc(field.label)}${required}</label>
                ${inputHtml}
                ${hint}
            `;

            container.appendChild(wrapper);
        });
    }

    function showModal() {
        document.getElementById('formErrors').innerHTML = '';
        document.getElementById('modal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('modal').classList.remove('active');
        currentMode = 'edit';
    }

    /* ---------- Sauvegarde ---------- */
    async function savePolicy() {
        const policyId = document.getElementById('policyId').value;
        const leaveTypeId = document.getElementById('leaveTypeId').value;
        const errBox = document.getElementById('formErrors');
        errBox.innerHTML = '';

        // Récupère toutes les values du formulaire dynamique
        const values = {};
        const container = document.getElementById('dynamicFormContainer');
        const inputs = container.querySelectorAll('[name^="values["]');

        inputs.forEach(input => {
            const match = input.name.match(/values\[(.+)\]/);
            if (match) {
                let v = input.value;
                if (v === '') v = null;
                values[match[1]] = v;
            }
        });

        const payload = {
            is_active: document.getElementById('is_active').value === '1',
            values: values
        };

        let url, method;

        if (currentMode === 'edit' && policyId) {
            url = `${API_URL}/${policyId}`;
            method = 'PUT';
        } else {
            url = API_URL;
            method = 'POST';
            payload.leave_type_id = parseInt(leaveTypeId);
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
                    errBox.innerHTML = msgs.map(m => `<div style="margin-bottom:4px;">• ${esc(m)}</div>`).join('');
                } else {
                    errBox.innerHTML = `<div>${esc(data.message || 'Erreur serveur')}</div>`;
                }
                return;
            }

            closeModal();
            loadPolicies();

        } catch (e) {
            errBox.innerHTML = `<div>Erreur réseau : ${esc(e.message)}</div>`;
        }
    }

    /* ---------- Toggle ---------- */
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

        document.getElementById('modal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
    });

    // Exposer globalement
    window.closeModal = closeModal;
    window.savePolicy = savePolicy;
})();