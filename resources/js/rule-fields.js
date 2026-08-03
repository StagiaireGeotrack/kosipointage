(function () {
    'use strict';

    let fields = window.fields || [];
    let editingId = null;

    /* ---------- Init ---------- */
    document.addEventListener('DOMContentLoaded', () => {
        initSortable();

        document.getElementById('field_type').addEventListener('change', function () {
            document.getElementById('optionsBlock').style.display = this.value === 'select' ? 'block' : 'none';
        });
    });

    /* ---------- Sortable (réordonner) ---------- */
    function initSortable() {
        const list = document.getElementById('fieldsList');
        if (!list || fields.length === 0) return;

        let dragged = null;

        list.querySelectorAll('.list-group-item[data-id]').forEach(item => {
            item.draggable = true;

            item.addEventListener('dragstart', function (e) {
                dragged = this;
                this.style.opacity = '0.5';
            });

            item.addEventListener('dragend', function () {
                this.style.opacity = '1';
                dragged = null;
                saveOrder();
            });

            item.addEventListener('dragover', function (e) {
                e.preventDefault();
                if (this === dragged) return;
                const rect = this.getBoundingClientRect();
                const mid = rect.top + rect.height / 2;
                if (e.clientY < mid) {
                    this.parentNode.insertBefore(dragged, this);
                } else {
                    this.parentNode.insertBefore(dragged, this.nextSibling);
                }
            });
        });
    }

    async function saveOrder() {
        const items = document.querySelectorAll('#fieldsList .list-group-item[data-id]');
        const orders = Array.from(items).map((item, idx) => ({
            id: parseInt(item.dataset.id),
            sort_order: idx + 1
        }));

        try {
            await fetch(`${window.apiUrl}/reorder`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ orders })
            });
        } catch (e) {
            console.error('Erreur réordonnancement:', e);
        }
    }

    /* ---------- Ajouter / Modifier ---------- */
    async function saveField() {
        const payload = {
            field_key:     document.getElementById('field_key').value.trim(),
            field_type:    document.getElementById('field_type').value,
            label:         document.getElementById('label').value.trim(),
            default_value: document.getElementById('default_value').value.trim() || null,
            options:       null,
            validation:    null,
        };

        // Options pour select
        if (payload.field_type === 'select') {
            try {
                payload.options = JSON.parse(document.getElementById('options').value || '[]');
            } catch {
                showError('Options JSON invalides.');
                return;
            }
        }

        // Validation
        const val = {};
        const min = document.getElementById('val_min').value;
        const max = document.getElementById('val_max').value;
        const step = document.getElementById('val_step').value;
        if (min !== '') val.min = parseFloat(min);
        if (max !== '') val.max = parseFloat(max);
        if (step !== '' && step !== 'any') val.step = parseFloat(step);
        if (document.getElementById('val_required').checked) val.required = true;
        if (Object.keys(val).length > 0) payload.validation = val;

        // Validation front
        if (!payload.field_key || !payload.label) {
            showError('Clé technique et libellé sont obligatoires.');
            return;
        }
        if (!/^[a-z0-9_]+$/.test(payload.field_key)) {
            showError('La clé technique doit être en snake_case.');
            return;
        }

        const errBox = document.getElementById('formErrors');
        errBox.style.display = 'none';
        errBox.innerHTML = '';

        const url = editingId
            ? `/admin/rule-fields/${editingId}`
            : window.apiUrl;
        const method = editingId ? 'PUT' : 'POST';

        try {
            const res = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (!res.ok) {
                if (data.errors) {
                    const msgs = Object.values(data.errors).flat();
                    showError(msgs.join('<br>'));
                } else {
                    showError(data.message || 'Erreur serveur');
                }
                return;
            }

            // Recharge la page pour simplicité (ou met à jour le DOM)
            window.location.reload();

        } catch (e) {
            showError('Erreur réseau : ' + e.message);
        }
    }

    /* ---------- Éditer ---------- */
    function editField(id) {
        const field = fields.find(f => f.id === id);
        if (!field) return;

        editingId = id;
        document.getElementById('fieldId').value = id;
        document.getElementById('field_key').value = field.field_key;
        document.getElementById('field_key').disabled = true;
        document.getElementById('field_type').value = field.field_type;
        document.getElementById('label').value = field.label;
        document.getElementById('default_value').value = field.default_value || '';

        if (field.options) {
            document.getElementById('options').value = JSON.stringify(field.options, null, 2);
            document.getElementById('optionsBlock').style.display = 'block';
        } else {
            document.getElementById('options').value = '[]';
            document.getElementById('optionsBlock').style.display = 'none';
        }

        if (field.validation) {
            document.getElementById('val_min').value = field.validation.min ?? '';
            document.getElementById('val_max').value = field.validation.max ?? '';
            document.getElementById('val_step').value = field.validation.step ?? '';
            document.getElementById('val_required').checked = field.validation.required ?? false;
        }

        document.getElementById('formTitle').textContent = 'Modifier le champ';
        document.getElementById('btnText').textContent = 'Enregistrer';
        document.getElementById('btnCancel').style.display = 'inline-block';
    }

    function resetForm() {
        editingId = null;
        document.getElementById('fieldForm').reset();
        document.getElementById('field_key').disabled = false;
        document.getElementById('optionsBlock').style.display = 'none';
        document.getElementById('formTitle').textContent = 'Ajouter un champ';
        document.getElementById('btnText').textContent = 'Ajouter';
        document.getElementById('btnCancel').style.display = 'none';
        document.getElementById('formErrors').style.display = 'none';
    }

    /* ---------- Supprimer ---------- */
    async function deleteField(id) {
        if (!confirm('Supprimer ce champ ? Les valeurs déjà saisies dans les policies seront perdues.')) return;

        try {
            const res = await fetch(`/admin/rule-fields/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (res.ok) {
                window.location.reload();
            } else {
                alert('Erreur lors de la suppression.');
            }
        } catch (e) {
            alert('Erreur réseau.');
        }
    }

    /* ---------- Seed defaults ---------- */
    async function seedDefaults() {
        if (!confirm('Créer les 15 champs standards pour ce type ?')) return;

        try {
            const res = await fetch(`${window.apiUrl}/seed-defaults`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (res.ok) {
                window.location.reload();
            } else {
                const data = await res.json();
                alert(data.message || 'Erreur');
            }
        } catch (e) {
            alert('Erreur réseau.');
        }
    }

    function showError(msg) {
        const box = document.getElementById('formErrors');
        box.innerHTML = msg;
        box.style.display = 'block';
    }

    // Exposer
    window.saveField = saveField;
    window.editField = editField;
    window.deleteField = deleteField;
    window.resetForm = resetForm;
    window.seedDefaults = seedDefaults;
})();