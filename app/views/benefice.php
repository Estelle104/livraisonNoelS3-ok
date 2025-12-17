<?php
// session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: /login');
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bénéfices - Livraison Noël</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logo h1 {
            font-size: 20px;
            font-weight: 600;
        }
        
        .nav-buttons {
            display: flex;
            gap: 15px;
        }
        
        .nav-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .nav-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .main-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            padding: 30px;
            max-width: 1600px;
            margin: 0 auto;
        }
        
        @media (max-width: 1200px) {
            .main-container {
                grid-template-columns: 1fr;
            }
        }
        
        .section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            padding: 30px;
        }
        
        .section-title {
            color: #333;
            font-size: 24px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        
        .filter-group label {
            font-weight: 500;
            color: #555;
        }
        
        .filter-row {
            display: flex;
            gap: 10px;
        }
        
        .filter-row select,
        .filter-row input {
            flex: 1;
        }
        
        select, input {
            padding: 10px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        
        select:focus, input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .stats-value {
            font-size: 48px;
            font-weight: 700;
            margin: 20px 0;
        }
        
        .stats-label {
            font-size: 18px;
            opacity: 0.9;
        }
        
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f8f9fa;
            color: #333;
            font-weight: 600;
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #dee2e6;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .positive {
            color: #28a745;
            font-weight: 600;
        }
        
        .negative {
            color: #dc3545;
            font-weight: 600;
        }
        
        .details-btn {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s;
        }
        
        .details-btn:hover {
            background: #138496;
        }
        
        .chart-container {
            height: 300px;
            margin-top: 30px;
        }
        
        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        .details-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .details-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        
        .detail-label {
            font-weight: 500;
            color: #555;
        }
        
        .detail-value {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>💰 Analyse des Bénéfices</h1>
        </div>
        
        <div class="nav-buttons">
            <a href="/app/accueil" class="nav-btn">🏠 Accueil</a>
            <a href="/app/livraison" class="nav-btn">🚚 Livraisons</a>
            <a href="/logout" class="nav-btn">🔒 Déconnexion</a>
        </div>
    </header>
    
    <main class="main-container">
        <!-- Section gauche : Filtres et montant total (Estelle) -->
        <section class="section">
            <h2 class="section-title">📊 Filtres et Total</h2>
            
            <form id="filterForm">
                <div class="filter-container">
                    <div class="filter-group">
                        <label>Jour</label>
                        <div class="filter-row">
                            <select id="jourOperator">
                                <option value="=">=</option>
                                <option value=">=">>=</option>
                                <option value="<="><=</option>
                            </select>
                            <input type="number" id="jour" min="1" max="31" 
                                   placeholder="1-31" style="flex: 2;">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label>Mois</label>
                        <div class="filter-row">
                            <select id="moisOperator">
                                <option value="=">=</option>
                                <option value=">=">>=</option>
                                <option value="<="><=</option>
                            </select>
                            <select id="mois" style="flex: 2;">
                                <option value="">Tous les mois</option>
                                <option value="1">Janvier</option>
                                <option value="2">Février</option>
                                <option value="3">Mars</option>
                                <option value="4">Avril</option>
                                <option value="5">Mai</option>
                                <option value="6">Juin</option>
                                <option value="7">Juillet</option>
                                <option value="8">Août</option>
                                <option value="9">Septembre</option>
                                <option value="10">Octobre</option>
                                <option value="11">Novembre</option>
                                <option value="12">Décembre</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label>Année</label>
                        <div class="filter-row">
                            <select id="anneeOperator">
                                <option value="=">=</option>
                                <option value=">=">>=</option>
                                <option value="<="><=</option>
                            </select>
                            <input type="number" id="annee" 
                                   placeholder="2024" 
                                   min="2020" max="2030"
                                   value="<?php echo date('Y'); ?>"
                                   style="flex: 2;">
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">
                    🔍 Appliquer les filtres
                </button>
                <button type="button" onclick="resetFilters()" class="btn-secondary">
                    🔄 Réinitialiser
                </button>
            </form>
            
            <div class="stats-card">
                <div class="stats-label">BÉNÉFICE TOTAL</div>
                <div class="stats-value" id="totalBenefice">
                    <?php echo number_format($totalBenefice, 2, ',', ' '); ?> Ar
                </div>
                <div>pour la période sélectionnée</div>
            </div>
            
            <div class="chart-container">
                <canvas id="beneficeChart"></canvas>
            </div>
        </section>
        
        <!-- Section droite : Liste détaillée (Andry) -->
        <section class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 class="section-title">📈 Détails des Bénéfices</h2>
                <button onclick="loadDetails()" class="btn-secondary">
                    📥 Charger les détails
                </button>
            </div>
            
            <div class="table-container">
                <table id="beneficeTable">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Chiffre d'Affaire</th>
                            <th>Coût Revient</th>
                            <th>Bénéfice</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="beneficeBody">
                        <?php if (empty($benefices)): ?>
                            <tr>
                                <td colspan="5" class="no-data">
                                    Aucune donnée de bénéfice disponible
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($benefices as $benefice): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($benefice['jour'])); ?></td>
                                    <td><?php echo number_format($benefice['chiffreAffaire'], 2, ',', ' '); ?> Ar</td>
                                    <td><?php echo number_format($benefice['coutRevient'], 2, ',', ' '); ?> Ar</td>
                                    <td class="<?php echo $benefice['benefice'] >= 0 ? 'positive' : 'negative'; ?>">
                                        <?php echo number_format($benefice['benefice'], 2, ',', ' '); ?> Ar
                                    </td>
                                    <td>
                                        <button onclick="showDayDetails('<?php echo $benefice['jour']; ?>')" 
                                                class="details-btn">
                                            📊 Détails
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                <h3 style="margin-bottom: 15px; color: #333;">📋 Légende</h3>
                <div style="display: flex; gap: 20px;">
                    <div>
                        <span style="display: inline-block; width: 20px; height: 20px; background: #28a745; border-radius: 4px;"></span>
                        <span style="margin-left: 10px;">Bénéfice positif</span>
                    </div>
                    <div>
                                        <span style="display: inline-block; width: 20px; height: 20px; background: #dc3545; border-radius: 4px;"></span>
                        <span style="margin-left: 10px;">Bénéfice négatif</span>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Modal pour les détails -->
    <div id="detailsModal" class="details-modal">
        <div class="details-content">
            <div class="details-header">
                <h2>📊 Détails du jour</h2>
                <button onclick="closeDetailsModal()" class="close-btn">×</button>
            </div>
            <div id="detailsContent">
                <!-- Les détails seront chargés ici -->
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let beneficeChart = null;
        
        // Initialiser le graphique
        function initChart() {
            const ctx = document.getElementById('beneficeChart').getContext('2d');
            const dates = <?php echo json_encode(array_column($benefices, 'jour')); ?>;
            const benefices = <?php echo json_encode(array_column($benefices, 'benefice')); ?>;
            
            beneficeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates.map(date => new Date(date).toLocaleDateString('fr-FR')),
                    datasets: [{
                        label: 'Bénéfice (Ar)',
                        data: benefices,
                        backgroundColor: benefices.map(b => b >= 0 ? 'rgba(40, 167, 69, 0.7)' : 'rgba(220, 53, 69, 0.7)'),
                        borderColor: benefices.map(b => b >= 0 ? '#28a745' : '#dc3545'),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR').format(value) + ' Ar';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Bénéfice: ' + new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' Ar';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Appliquer les filtres
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const filters = {};
            const jour = document.getElementById('jour').value;
            const mois = document.getElementById('mois').value;
            const annee = document.getElementById('annee').value;
            const jourOp = document.getElementById('jourOperator').value;
            const moisOp = document.getElementById('moisOperator').value;
            const anneeOp = document.getElementById('anneeOperator').value;
            
            // Construire l'URL avec les filtres
            let url = '/app/benefice?';
            const params = new URLSearchParams();
            
            if (jour) params.append('jour', jour);
            if (mois) params.append('mois', mois);
            if (annee) params.append('annee', annee);
            if (jourOp !== '=') params.append('jour_op', jourOp);
            if (moisOp !== '=') params.append('mois_op', moisOp);
            if (anneeOp !== '=') params.append('annee_op', anneeOp);
            
            window.location.href = url + params.toString();
        });
        
        // Réinitialiser les filtres
        function resetFilters() {
            document.getElementById('filterForm').reset();
            window.location.href = '/app/benefice';
        }
        
        // Charger les détails
        function loadDetails() {
            const tbody = document.getElementById('beneficeBody');
            tbody.innerHTML = '<tr><td colspan="5" class="loading">Chargement...</td></tr>';
            
            const params = new URLSearchParams(window.location.search);
            
            fetch('/app/benefice/details?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="no-data">Aucune donnée disponible</td></tr>';
                        return;
                    }
                    
                    let html = '';
                    data.forEach(item => {
                        const date = new Date(item.jour).toLocaleDateString('fr-FR');
                        const classe = item.benefice >= 0 ? 'positive' : 'negative';
                        
                        html += `
                            <tr>
                                <td>${date}</td>
                                <td>${formatCurrency(item.chiffreAffaire)}</td>
                                <td>${formatCurrency(item.coutRevient)}</td>
                                <td class="${classe}">${formatCurrency(item.benefice)}</td>
                                <td>
                                    <button onclick="showDayDetails('${item.jour}')" class="details-btn">
                                        📊 Détails
                                    </button>
                                </td>
                            </tr>
                        `;
                    });
                    
                    tbody.innerHTML = html;
                    
                    // Mettre à jour le total
                    const total = data.reduce((sum, item) => sum + parseFloat(item.benefice), 0);
                    document.getElementById('totalBenefice').textContent = formatCurrency(total);
                    
                    // Mettre à jour le graphique
                    updateChart(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    tbody.innerHTML = '<tr><td colspan="5" class="no-data">Erreur de chargement</td></tr>';
                });
        }
        
        // Afficher les détails d'une journée
        function showDayDetails(date) {
            const params = new URLSearchParams();
            params.append('jour', date.split(' ')[0]); // Juste la date sans l'heure
            
            fetch('/app/benefice/details?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const item = data[0];
                        const detailsHtml = `
                            <div class="detail-item">
                                <span class="detail-label">Date:</span>
                                <span class="detail-value">${new Date(item.jour).toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Chiffre d'Affaire:</span>
                                <span class="detail-value" style="color: #28a745;">${formatCurrency(item.chiffreAffaire)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Coût de Revient:</span>
                                <span class="detail-value" style="color: #dc3545;">${formatCurrency(item.coutRevient)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Bénéfice Net:</span>
                                <span class="detail-value" style="color: ${item.benefice >= 0 ? '#28a745' : '#dc3545'}; font-size: 20px;">
                                    ${formatCurrency(item.benefice)}
                                </span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Marge:</span>
                                <span class="detail-value">
                                    ${((item.benefice / item.chiffreAffaire) * 100).toFixed(2)}%
                                </span>
                            </div>
                        `;
                        
                        document.getElementById('detailsContent').innerHTML = detailsHtml;
                        document.getElementById('detailsModal').style.display = 'flex';
                    }
                });
        }
        
        // Fermer le modal
        function closeDetailsModal() {
            document.getElementById('detailsModal').style.display = 'none';
        }
        
        // Mettre à jour le graphique
        function updateChart(data) {
            if (beneficeChart) {
                beneficeChart.destroy();
            }
            
            const ctx = document.getElementById('beneficeChart').getContext('2d');
            const dates = data.map(item => item.jour);
            const benefices = data.map(item => item.benefice);
            
            beneficeChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates.map(date => new Date(date).toLocaleDateString('fr-FR')),
                    datasets: [{
                        label: 'Bénéfice (Ar)',
                        data: benefices,
                        backgroundColor: benefices.map(b => b >= 0 ? 'rgba(40, 167, 69, 0.7)' : 'rgba(220, 53, 69, 0.7)'),
                        borderColor: benefices.map(b => b >= 0 ? '#28a745' : '#dc3545'),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return new Intl.NumberFormat('fr-FR').format(value) + ' Ar';
                                }
                            }
                        }
                    }
                }
            });
        }
        
        // Formater une valeur monétaire
        function formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value) + ' Ar';
        }
        
        // Initialiser le graphique au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (!empty($benefices)): ?>
                initChart();
            <?php endif; ?>
            
            // Fermer le modal en cliquant à l'extérieur
            window.onclick = function(event) {
                const modal = document.getElementById('detailsModal');
                if (event.target == modal) {
                    closeDetailsModal();
                }
            }
        });
    </script>
</body>
</html>