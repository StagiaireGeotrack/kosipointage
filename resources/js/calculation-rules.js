(function () {
    'use strict';

    let rules = window.rules || [];
    let variables = window.variables || {};
    let editingId = null;

    function saveRule() {
        const payload = {
            name: document.getElementById('name').value.trim(),
            output_variable: document.getElementById('output_variable').value.trim(),
            formula: document.getElementById('formula').value.trim(),
            is_active: document.getElementById('is_active').checked,
        };

        if (!payload.name || !payload.output_variable || !payload.formula) {
            showError('Tous les champs sont obligatoires.');
            return;
        }
        if (!/^[a-z0-9_]+$/.test(payload.output_variable)) {
            showError('La variable de sortie doit être en snake_case.');
            return;
        }

        const url = editingId ? `/admin/calculation-rules/${editingId}` : window.apiUrl;
        const method = editingId ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (!ok) {
                showError(data.errors ? Object.values(data.errors).flat().join('<br>') : data.message);
                return;
            }
            window.location.reload();
        })
        .catch(e => showError('Erreur réseau: ' + e.message));
    }

    function editRule(id) {
        const rule = rules.find(r => r.id === id);
        if (!rule) return;

        editingId = id;
        document.getElementById('ruleId').value = id;
        document.getElementById('name').value = rule.name;
        document.getElementById('output_variable').value = rule.output_variable;
        document.getElementById('formula').value = rule.formula;
        document.getElementById('is_active').checked = rule.is_active;
        document.getElementById('output_variable').disabled = true;

        document.getElementById('formTitle').textContent = 'Modifier la formule';
        document.getElementById('btnText').textContent = 'Enregistrer';
        document.getElementById('btnCancel').style.display = 'inline-block';
    }

    function resetForm() {
        editingId = null;
        document.getElementById('ruleForm').reset();
        document.getElementById('output_variable').disabled = false;
        document.getElementById('formTitle').textContent = 'Ajouter une formule';
        document.getElementById('btnText').textContent = 'Ajouter';
        document.getElementById('btnCancel').style.display = 'none';
        document.getElementById('formErrors').style.display = 'none';
    }

    function deleteRule(id) {
        if (!confirm('Supprimer cette formule ?')) return;
        fetch(`/admin/calculation-rules/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
        }).then(() => window.location.reload());
    }

    function testFormula() {
        const formula = document.getElementById('formula').value.trim();
        if (!formula) { alert('Entrez une formule d\'abord.'); return; }

        document.getElementById('testFormulaDisplay').textContent = formula;

        const container = document.getElementById('testVariables');
        container.innerHTML = '';
        Object.entries(variables).forEach(([key, label]) => {
            const div = document.createElement('div');
            div.className = 'mb-2';
            div.innerHTML = `
                <label class="form-label small">${label} <code>(${key})</code></label>
                <input type="text" class="form-control form-control-sm test-var" data-key="${key}" placeholder="valeur">
            `;
            container.appendChild(div);
        });

        document.getElementById('testResult').style.display = 'none';
        document.getElementById('testModal').classList.add('show');
        document.getElementById('testModal').style.display = 'block';
    }

    function runTest() {
        const formula = document.getElementById('formula').value.trim();
        const vars = {};
        document.querySelectorAll('.test-var').forEach(input => {
            let v = input.value;
            if (v === '') v = '0';
            if (!isNaN(v) && v !== '') v = parseFloat(v);
            vars[input.dataset.key] = v;
        });

        fetch(window.testUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ formula, variables: vars })
        })
        .then(r => r.json())
        .then(data => {
            const box = document.getElementById('testResult');
            box.style.display = 'block';
            if (data.success) {
                box.className = 'alert alert-success mt-3';
                box.innerHTML = `<strong>Résultat :</strong> <code>${data.result}</code>`;
            } else {
                box.className = 'alert alert-danger mt-3';
                box.innerHTML = `<strong>Erreur :</strong> ${data.error}`;
            }
        });
    }

    function closeTestModal() {
        document.getElementById('testModal').classList.remove('show');
        document.getElementById('testModal').style.display = 'none';
    }

    function showError(msg) {
        const box = document.getElementById('formErrors');
        box.innerHTML = msg;
        box.style.display = 'block';
    }

    window.saveRule = saveRule;
    window.editRule = editRule;
    window.resetForm = resetForm;
    window.deleteRule = deleteRule;
    window.testFormula = testFormula;
    window.runTest = runTest;
    window.closeTestModal = closeTestModal;
})();