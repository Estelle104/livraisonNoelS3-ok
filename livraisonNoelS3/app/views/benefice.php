<?php
if (!isset($_SESSION['logged_in'])) {
    header('Location: /login');
    exit();
}

$user = $_SESSION['user'];

// Récupérer les filtres depuis l'URL
$jour = $_GET['jour'] ?? '';
$mois = $_GET['mois'] ?? '';
$annee = $_GET['annee'] ?? date('Y');
$jour_op = $_GET['jour_op'] ?? '=';
$mois_op = $_GET['mois_op'] ?? '=';
$annee_op = $_GET['annee_op'] ?? '=';

// Les données sont déjà passées par le contrôleur
// $benefices et $totalBenefice sont définies dans BeneficeController::index()
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
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>💰 Analyse des Bénéfices</h1>
        </div>
        
        <div class="nav-buttons">
            <a href="<?= BASE_URL ?>/accueil" class="nav-btn">🏠 Accueil</a>
            <a href="<?= BASE_URL ?>/livraison" class="nav-btn">🚚 Livraisons</a>
            <a href="/logout" class="nav-btn">🔒 Déconnexion</a>
        </div>
    </header>
    
    <main class="main-container">
        <!-- Section gauche : Filtres et montant total -->
        <section class="section">
            <h2 class="section-title">📊 Filtres et Total</h2>
            
            <form method="GET" action="<?= BASE_URL ?>/benefice">
                <div class="filter-container">
                    <div class="filter-group">
                        <label>Jour</label>
                        <div class="filter-row">
                            <select name="jour_op">
                                <option value="=" <?php echo $jour_op == '=' ? 'selected' : ''; ?>>=</option>
                                <option value=">=" <?php echo $jour_op == '>=' ? 'selected' : ''; ?>>>=</option>
                                <option value="<=" <?php echo $jour_op == '<=' ? 'selected' : ''; ?>><=</option>
                            </select>
                            <input type="number" name="jour" min="1" max="31" 
                                   placeholder="1-31" value="<?php echo htmlspecialchars($jour); ?>" style="flex: 2;">
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label>Mois</label>
                        <div class="filter-row">
                            <select name="mois_op">
                                <option value="=" <?php echo $mois_op == '=' ? 'selected' : ''; ?>>=</option>
                                <option value=">=" <?php echo $mois_op == '>=' ? 'selected' : ''; ?>>>=</option>
                                <option value="<=" <?php echo $mois_op == '<=' ? 'selected' : ''; ?>><=</option>
                            </select>
                            <select name="mois" style="flex: 2;">
                                <option value="">Tous les mois</option>
                                <option value="1" <?php echo $mois == '1' ? 'selected' : ''; ?>>Janvier</option>
                                <option value="2" <?php echo $mois == '2' ? 'selected' : ''; ?>>Février</option>
                                <option value="3" <?php echo $mois == '3' ? 'selected' : ''; ?>>Mars</option>
                                <option value="4" <?php echo $mois == '4' ? 'selected' : ''; ?>>Avril</option>
                                <option value="5" <?php echo $mois == '5' ? 'selected' : ''; ?>>Mai</option>
                                <option value="6" <?php echo $mois == '6' ? 'selected' : ''; ?>>Juin</option>
                                <option value="7" <?php echo $mois == '7' ? 'selected' : ''; ?>>Juillet</option>
                                <option value="8" <?php echo $mois == '8' ? 'selected' : ''; ?>>Août</option>
                                <option value="9" <?php echo $mois == '9' ? 'selected' : ''; ?>>Septembre</option>
                                <option value="10" <?php echo $mois == '10' ? 'selected' : ''; ?>>Octobre</option>
                                <option value="11" <?php echo $mois == '11' ? 'selected' : ''; ?>>Novembre</option>
                                <option value="12" <?php echo $mois == '12' ? 'selected' : ''; ?>>Décembre</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filter-group">
                        <label>Année</label>
                        <div class="filter-row">
                            <select name="annee_op">
                                <option value="=" <?php echo $annee_op == '=' ? 'selected' : ''; ?>>=</option>
                                <option value=">=" <?php echo $annee_op == '>=' ? 'selected' : ''; ?>>>=</option>
                                <option value="<=" <?php echo $annee_op == '<=' ? 'selected' : ''; ?>><=</option>
                            </select>
                            <input type="number" name="annee" 
                                   placeholder="2024" 
                                   min="2020" max="2030"
                                   value="<?php echo htmlspecialchars($annee); ?>"
                                   style="flex: 2;">
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary">
                    🔍 Appliquer les filtres
                </button>
                <a href="<?= BASE_URL ?>/benefice" class="btn-secondary">
                    🔄 Réinitialiser
                </a>
            </form>
            
            <div class="stats-card">
                <div class="stats-label">BÉNÉFICE TOTAL</div>
                <div class="stats-value" id="totalBenefice">
                    <?php echo number_format($totalBenefice ?? 0, 2, ',', ' '); ?> Ar
                </div>
                <div>pour la période sélectionnée</div>
            </div>
            
            <!-- Remarque : Sans JavaScript, le graphique ne peut pas être affiché -->
            <div class="chart-container">
                <p style="text-align: center; padding: 50px; color: #666;">
                    📊 Le graphique nécessite JavaScript pour fonctionner
                </p>
            </div>
        </section>
        
        <!-- Section droite : Liste détaillée -->
        <section class="section">
            <h2 class="section-title">📈 Détails des Bénéfices</h2>
            
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
                                        <form method="GET" action="/app/benefice" style="display: inline;">
                                            <input type="hidden" name="jour" value="<?php echo date('Y-m-d', strtotime($benefice['jour'])); ?>">
                                            <input type="hidden" name="mois" value="<?php echo $mois; ?>">
                                            <input type="hidden" name="annee" value="<?php echo $annee; ?>">
                                            <input type="hidden" name="jour_op" value="<?php echo $jour_op; ?>">
                                            <input type="hidden" name="mois_op" value="<?php echo $mois_op; ?>">
                                            <input type="hidden" name="annee_op" value="<?php echo $annee_op; ?>">
                                            <button type="submit" class="details-btn">
                                                📊 Voir
                                            </button>
                                        </form>
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
</body>
</html>