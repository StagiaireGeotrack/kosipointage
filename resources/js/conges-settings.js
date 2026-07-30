/**
 * Gestion des types de congés — Paramétrage
 */
import '../css/conges-settings.css';
(function () {
    'use strict';

    const API_URL = '/admin/leave-types/api';
    let types = [];

    /* ---------- Chargement ---------- */
    async function loadTypes() {
        const tbody = document.getElementById('tableBody');
        const badge = document.getElementById('countBadge');

        try {
            const res = await fetch(API_URL);
            if (!res.ok) throw new Error('HTTP ' + res.status);
            types = await res.json();
            renderTable();
        } catch (e) {
            console.error(e);
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="empty" style="color:#dc2626;">
                            Erreur de chargement. Vérifie que les routes sont bien enregistrées.
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

        const count = types.length;
        if (badge) {
            badge.textContent = count + ' type' + (count > 1 ? 's' : '');
        }

        if (count === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="empty">
                        Aucun type de congé. Clique sur « Nouveau » pour commencer.
                    </td>
                </tr>`;
            return;
        }

        tbody.innerHTML = types.map(t => `
            <tr>
                <td>
                    <span class="color-dot" style="background:${escapeHtml(t.color)}"></span>
                    ${escapeHtml(t.name)}
                </td>
                <td><code>${escapeHtml(t.code)}</code></td>
                <td>${unitLabel(t.unit)}</td>
                <td>${t.deducts_balance ? 'Oui' : 'Non'}</td>
                <td>${attachmentLabel(t)}</td>
                <td>
                    <span class="badge-status" style="background:${t.is_active ? '#d1fae5' : '#fee2e2'};color:${t.is_active ? '#065f46' : '#991b1b'}">
                        ${t.is_active ? 'Actif' : 'Inactif'}
                    </span>
                </td>
                <td class="actions">
                    <button class="btn btn-secondary btn-sm" onclick="window.editType(${t.id})">Modifier</button>
                    <button class="btn btn-danger btn-sm" onclick="window.deleteType(${t.id})">Suppr.</button>
                </td>
            </tr>
        `).join('');
    }

    function unitLabel(unit) {
        if (unit === 'days') return 'Jours';
        if (unit === 'half_days') return 'Demi-journées';
        if (unit === 'hours') return 'Heures';
        return unit;
    }

    function attachmentLabel(t) {
        if (t.requires_attachment === 'never') return '—';
        if (t.requires_attachment === 'always') return 'Obligatoire';
        return 'À partir de ' + t.attachment_threshold + ' j';
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /* ---------- Modal ---------- */
    function openModal(id = null) {
        const modal = document.getElementById('modal');
        const formErrors = document.getElementById('formErrors');
        const modalTitle = document.getElementById('modalTitle');

        if (formErrors) formErrors.innerHTML = '';
        document.getElementById('typeId').value = id || '';

        if (modalTitle) {
            modalTitle.textContent = id ? 'Modifier le type' : 'Nouveau type de congé';
        }

        if (id) {
            const t = types.find(x => x.id === id);
            if (!t) return;
            setVal('name', t.name);
            setVal('code', t.code);
            setVal('color', t.color);
            setVal('unit', t.unit);
            setVal('deducts_balance', t.deducts_balance ? '1' : '0');
            setVal('requires_attachment', t.requires_attachment);
            setVal('attachment_threshold', t.attachment_threshold);
            setVal('approval_required', t.approval_required ? '1' : '0');
            setVal('allow_negative_balance', t.allow_negative_balance ? '1' : '0');
            setVal('negative_limit', t.negative_limit);
            setVal('visibility_level', t.visibility_level);
            setVal('is_active', t.is_active ? '1' : '0');
        } else {
            const form = document.getElementById('formType');
            if (form) form.reset();
            setVal('color', '#4CAF50');
            setVal('deducts_balance', '1');
            setVal('approval_required', '1');
            setVal('requires_attachment', 'never');
            setVal('attachment_threshold', '0');
            setVal('allow_negative_balance', '0');
            setVal('negative_limit', '0');
            setVal('visibility_level', 'all');
            setVal('is_active', '1');
        }

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
    async function saveType() {
        const id = document.getElementById('typeId').value;
        const payload = {
            name: document.getElementById('name').value.trim(),
            code: document.getElementById('code').value.trim(),
            color: document.getElementById('color').value,
            unit: document.getElementById('unit').value,
            deducts_balance: document.getElementById('deducts_balance').value === '1',
            requires_attachment: document.getElementById('requires_attachment').value,
            attachment_threshold: parseInt(document.getElementById('attachment_threshold').value) || 0,
            approval_required: document.getElementById('approval_required').value === '1',
            allow_negative_balance: document.getElementById('allow_negative_balance').value === '1',
            negative_limit: parseFloat(document.getElementById('negative_limit').value) || 0,
            visibility_level: document.getElementById('visibility_level').value,
            is_active: document.getElementById('is_active').value === '1',
        };

        const url = id ? `${API_URL}/${id}` : API_URL;
        const method = id ? 'PUT' : 'POST';

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
                    document.getElementById('formErrors').innerHTML = errors
                        .map(e => `<div class="form-error">• ${escapeHtml(e)}</div>`)
                        .join('');
                    return;
                }
                throw new Error('Erreur serveur');
            }

            closeModal();
            loadTypes();
        } catch (e) {
            const errBox = document.getElementById('formErrors');
            if (errBox) {
                errBox.innerHTML = `<div class="form-error">Erreur réseau ou serveur.</div>`;
            }
        }
    }

    /* ---------- Suppression ---------- */
    async function deleteType(id) {
        if (!confirm('Supprimer ce type de congé ?')) return;

        try {
            const res = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken || '',
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) {
                const data = await res.json().catch(() => ({}));
                alert(data.error || 'Impossible de supprimer.');
                return;
            }

            loadTypes();
        } catch (e) {
            alert('Erreur réseau.');
        }
    }

    /* ---------- Écouteurs ---------- */
    document.addEventListener('DOMContentLoaded', () => {
        loadTypes();

        const modal = document.getElementById('modal');
        if (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === this) closeModal();
            });
        }
    });

    // Exposer globalement pour les onclick inline
    window.openModal = openModal;
    window.closeModal = closeModal;
    window.saveType = saveType;
    window.editType = openModal;
    window.deleteType = deleteType;
})();