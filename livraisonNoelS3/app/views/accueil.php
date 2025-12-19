<?php
// session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: ' . BASE_URL . '/login');
    exit();
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Livraison Noël</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>🎄 Livraison Noël S3</h1>
        </div>
        
        <div class="user-info">
            <span class="user-name">Bienvenue, <?php echo htmlspecialchars($user['nomUser']); ?>!</span>
            <a href="<?= BASE_URL ?>/logout" class="btn-logout">Déconnexion</a>
        </div>
    </header>
    
    <main class="main-content">
        <section class="welcome-section">
            <h2 class="welcome-title">Tableau de Bord</h2>
            <p class="welcome-message">
                Gérez efficacement vos livraisons, suivez les bénéfices et supervisez toutes les opérations 
                de votre entreprise de livraison depuis cette interface centralisée.
            </p>
            
            <div class="dashboard-grid">
                <a href="<?= BASE_URL ?>/livraison" class="dashboard-card livraison-card">
                    <div class="card-icon">🚚</div>
                    <h3 class="card-title">Gestion des Livraisons</h3>
                    <p class="card-description">
                        Créez de nouvelles livraisons, suivez leur statut et gérez les commandes en cours.
                    </p>
                </a>
                
                <a href="<?= BASE_URL ?>/benefice" class="dashboard-card benefice-card">
                    <div class="card-icon">💰</div>
                    <h3 class="card-title">Bénéfices & Rapports</h3>
                    <p class="card-description">
                        Analysez vos bénéfices, consultez les rapports financiers et filtrez par période.
                    </p>
                </a>
                
                <div class="dashboard-card stats-card">
                    <div class="card-icon">📊</div>
                    <h3 class="card-title">Statistiques</h3>
                    <p class="card-description">
                        Visualisez les statistiques de livraison, performances et indicateurs clés.
                    </p>
                </div>
            </div>
        </section>
    </main>
    
    <footer class="footer">
        <p>© 2024 Système de Livraison Noël - S3 | Version 1.0</p>
        <p>Connecté en tant que: <?php echo htmlspecialchars($user['loginUser']); ?></p>
    </footer>
</body>
</html>