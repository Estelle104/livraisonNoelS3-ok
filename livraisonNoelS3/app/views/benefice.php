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
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">

</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>💰 Analyse des Bénéfices</h1>
        </div>
        
        <div class="nav-buttons">
            <a href="<?= BASE_URL ?>/accueil" class="nav-btn">🏠 Accueil</a>
            <a href="<?= BASE_URL ?>/livraison" class="nav-btn">🚚 Livraisons</a>
            <a href="<?= BASE_URL ?>/logout" class="nav-btn">🔒 Déconnexion</a>
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
                                            <a href="<?= BASE_URL ?>/">
                                                <button type="submit" class="details-btn">
                                                    📊 Voir
                                                </button>
                                            </a>
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