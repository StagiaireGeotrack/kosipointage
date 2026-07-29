<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramétrage — Types de Congés</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 1100px; margin: 0 auto; }
        h1 { color: #111827; margin-bottom: 8px; }
        .subtitle { color: #6b7280; margin-bottom: 24px; font-size: 14px; }
        .btn { padding: 10px 18px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; }
        .btn-primary { background: #2563eb; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-secondary { background: #e5e7eb; color: #374151; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        table { width: 100%; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; font-size: 14px; }
        th { background: #f9fafb; color: #374151; font-weight: 600; border-bottom: 1px solid #e5e7eb; }
        td { border-bottom: 1px solid #f3f4f6; color: #4b5563; }
        tr:hover td { background: #f9fafb; }
        .badge { padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .color-dot { display: inline-block; width: 14px; height: 14px; border-radius: 50%; vertical-align: middle; margin-right: 6px; border: 1px solid #e5e7eb; }
        .actions { display: flex; gap: 8px; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.4); z-index: 50; justify-content: center; align-items: center; }
        .modal { background: white; width: 90%; max-width: 560px; border-radius: 10px; padding: 24px; max-height: 90vh; overflow-y: auto; }
        .modal h2 { margin-top: 0; font-size: 18px; color: #111827; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 16px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full { grid-column: 1 / -1; }
        label { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        input, select { padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px; }
        input:focus, select:focus { outline: none; border-color: #2563eb; }
        .form-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e5e7eb; }
        .error { color: #dc2626; font-size: 13px; margin-top: 4px; }
        .empty { text-align: center; padding: 40px; color: #9ca3af; font-size: 14px; }
    </style>
</head>
<body>

<div class="container">
    <h1>🛠️ Paramétrage — Types de Congés</h1>
    <p class="subtitle">Gérez ici tous les types de congés de votre siège. Aucun code à modifier.</p>

    <div class="toolbar">
        <div>
            <span id="countBadge" style="background:#dbeafe;color:#1e40af;padding:4px 12px;border-radius:999px;font-size:13px;font-weight:600;">Chargement...</span>
        </div>
        <button class="btn btn-primary" onclick="openModal()">+ Nouveau type de congé</button>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Code</th>
                <th>Unité</th>
                <th>Décompte solde</th>
                <th>Pièce justificative</th>
                <th>Actif</th>
                <th style="width:140px;">Actions</th>
            </tr>
        </thead>
        <tbody id="tableBody">
            <tr><td colspan="7" class="empty">Chargement...</td></tr>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal-overlay" id="modal">
    <div class="modal">
        <h2 id="modalTitle">Nouveau type de congé</h2>
        
        <form id="formType" onsubmit="return false;">
            <input type="hidden" id="typeId">
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" id="name" placeholder="Ex: Congé Payé">
                </div>
                <div class="form-group">
                    <label>Code *</label>
                    <input type="text" id="code" placeholder="Ex: CP" maxlength="20">
                </div>
                <div class="form-group">
                    <label>Couleur *</label>
                    <input type="color" id="color" value="#4CAF50">
                </div>
                <div class="form-group">
                    <label>Unité *</label>
                    <select id="unit">
                        <option value="days">Jours</option>
                        <option value="half_days">Demi-journées</option>
                        <option value="hours">Heures</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Décompte solde</label>
                    <select id="deducts_balance">
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Approbation requise</label>
                    <select id="approval_required">
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pièce justificative</label>
                    <select id="requires_attachment">
                        <option value="never">Jamais</option>
                        <option value="always">Toujours</option>
                        <option value="from_duration">À partir d'une durée</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Seuil pièce (jours)</label>
                    <input type="number" id="attachment_threshold" value="0" min="0">
                </div>
                <div class="form-group">
                    <label>Solde négatif autorisé</label>
                    <select id="allow_negative_balance">
                        <option value="0">Non</option>
                        <option value="1">Oui</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Limite négative</label>
                    <input type="number" id="negative_limit" value="0" min="0" step="0.5">
                </div>
                <div class="form-group">
                    <label>Visibilité</label>
                    <select id="visibility_level">
                        <option value="all">Tous</option>
                        <option value="manager">Manager</option>
                        <option value="rh">RH</option>
                        <option value="admin">Admin uniquement</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Actif</label>
                    <select id="is_active">
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>
            </div>
            
            <div id="formErrors" style="margin-top:12px;"></div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="saveType()">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
const API_URL = '/admin/leave-types/api';
let types = [];

async function loadTypes() {
    try {
        const res = await fetch(API_URL);
        types = await res.json();
        renderTable();
    } catch (e) {
        document.getElementById('tableBody').innerHTML = `<tr><td colspan="7" class="empty" style="color:#dc2626;">Erreur de chargement. Vérifie que les routes sont bien enregistrées.</td></tr>`;
    }
}

function renderTable() {
    const tbody = document.getElementById('tableBody');
    const badge = document.getElementById('countBadge');
    
    badge.textContent = types.length + ' type' + (types.length > 1 ? 's' : '');
    
    if (types.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" class="empty">Aucun type de congé. Clique sur "Nouveau" pour commencer.</td></tr>`;
        return;
    }
    
    tbody.innerHTML = types.map(t => `
        <tr>
            <td><span class="color-dot" style="background:${t.color}"></span>${t.name}</td>
            <td><code>${t.code}</code></td>
            <td>${t.unit === 'days' ? 'Jours' : t.unit === 'half_days' ? 'Demi-journées' : 'Heures'}</td>
            <td>${t.deducts_balance ? 'Oui' : 'Non'}</td>
            <td>${t.requires_attachment === 'never' ? '—' : t.requires_attachment === 'always' ? 'Obligatoire' : 'À partir de ' + t.attachment_threshold + 'j'}</td>
            <td><span class="badge" style="background:${t.is_active ? '#d1fae5' : '#fee2e2'};color:${t.is_active ? '#065f46' : '#991b1b'}">${t.is_active ? 'Actif' : 'Inactif'}</span></td>
            <td class="actions">
                <button class="btn btn-secondary btn-sm" onclick="editType(${t.id})">Modifier</button>
                <button class="btn btn-danger btn-sm" onclick="deleteType(${t.id})">Suppr.</button>
            </td>
        </tr>
    `).join('');
}

function openModal(id = null) {
    document.getElementById('formErrors').innerHTML = '';
    document.getElementById('typeId').value = id || '';
    document.getElementById('modalTitle').textContent = id ? 'Modifier le type' : 'Nouveau type de congé';
    
    if (id) {
        const t = types.find(x => x.id === id);
        if (!t) return;
        document.getElementById('name').value = t.name;
        document.getElementById('code').value = t.code;
        document.getElementById('color').value = t.color;
        document.getElementById('unit').value = t.unit;
        document.getElementById('deducts_balance').value = t.deducts_balance ? '1' : '0';
        document.getElementById('requires_attachment').value = t.requires_attachment;
        document.getElementById('attachment_threshold').value = t.attachment_threshold;
        document.getElementById('approval_required').value = t.approval_required ? '1' : '0';
        document.getElementById('allow_negative_balance').value = t.allow_negative_balance ? '1' : '0';
        document.getElementById('negative_limit').value = t.negative_limit;
        document.getElementById('visibility_level').value = t.visibility_level;
        document.getElementById('is_active').value = t.is_active ? '1' : '0';
    } else {
        document.getElementById('formType').reset();
        document.getElementById('color').value = '#4CAF50';
        document.getElementById('deducts_balance').value = '1';
        document.getElementById('approval_required').value = '1';
        document.getElementById('requires_attachment').value = 'never';
        document.getElementById('attachment_threshold').value = '0';
        document.getElementById('allow_negative_balance').value = '0';
        document.getElementById('negative_limit').value = '0';
        document.getElementById('visibility_level').value = 'all';
        document.getElementById('is_active').value = '1';
    }
    
    document.getElementById('modal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('modal').style.display = 'none';
}

async function saveType() {
    const id = document.getElementById('typeId').value;
    const payload = {
        name: document.getElementById('name').value,
        code: document.getElementById('code').value,
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
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(payload)
        });

        if (!res.ok) {
            const data = await res.json();
            if (data.errors) {
                document.getElementById('formErrors').innerHTML = Object.values(data.errors).flat().map(e => `<div class="error">• ${e}</div>`).join('');
                return;
            }
            throw new Error('Erreur serveur');
        }

        closeModal();
        loadTypes();
    } catch (e) {
        document.getElementById('formErrors').innerHTML = `<div class="error">Erreur réseau ou serveur.</div>`;
    }
}

async function deleteType(id) {
    if (!confirm('Supprimer ce type ?')) return;
    
    try {
        const res = await fetch(`${API_URL}/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        });
        
        if (!res.ok) {
            const data = await res.json();
            alert(data.error || 'Impossible de supprimer.');
            return;
        }
        
        loadTypes();
    } catch (e) {
        alert('Erreur réseau.');
    }
}

function editType(id) { openModal(id); }

// Fermer le modal en cliquant à l'extérieur
document.getElementById('modal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

// Charger au démarrage
loadTypes();
</script>

</body>
</html>