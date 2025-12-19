<?php
if (!isset($_SESSION['logged_in'])) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Bénéfices - <?php echo $dateFormatted; ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>📊 Détails des Bénéfices - <?php echo date('d/m/Y', strtotime($dateFormatted)); ?></h1>
        </div>
        
        <div class="nav-buttons">
            <a href="<?= BASE_URL ?>/benefice" class="nav-btn">← Retour aux bénéfices</a>
            <a href="<?= BASE_URL ?>/accueil" class="nav-btn">🏠 Accueil</a>
            <a href="<?= BASE_URL ?>/livraison" class="nav-btn">🚚 Livraisons</a>
        </div>
    </header>
    
    
        
        <!-- Liste des livraisons du jour -->
        <section class="section">
            <h2 class="section-title">📋 Livraisons du Jour</h2>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Colis</th>
                            <th>Destination</th>
                            <th>Véhicule</th>
                            <th>Chauffeur</th>
                            <th>Coût Véhicule</th>
                            <th>Salaire</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($livraisonsDuJour)): ?>
                            <tr>
                                <td colspan="8" class="no-data">Aucune livraison pour ce jour</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($livraisonsDuJour as $livraison): ?>
                                <tr>
                                    <td><?php echo $livraison['id']; ?></td>
                                    <td><?php echo htmlspecialchars($livraison['descriptionColi']); ?></td>
                                    <td><?php echo htmlspecialchars($livraison['nomDestination'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($livraison['nomVehicule']); ?></td>
                                    <td><?php echo htmlspecialchars($livraison['nomChauffeur']); ?></td>
                                    <td><?php echo number_format($livraison['coutVoiture'], 2, ',', ' '); ?> Ar</td>
                                    <td><?php echo number_format($livraison['salaireJournalier'], 2, ',', ' '); ?> Ar</td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower(str_replace('_', '', $livraison['etatlivraison'])); ?>">
                                            <?php echo str_replace('_', ' ', $livraison['etatlivraison']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>
</html>