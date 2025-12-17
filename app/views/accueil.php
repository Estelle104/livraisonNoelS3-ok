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
    <title>Accueil - Livraison Noël</title>
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
            font-size: 24px;
            font-weight: 600;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-name {
            font-weight: 500;
        }
        
        .btn-logout {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 8px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .main-content {
            padding: 40px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .welcome-section {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            text-align: center;
            margin-bottom: 40px;
        }
        
        .welcome-title {
            color: #333;
            font-size: 32px;
            margin-bottom: 15px;
        }
        
        .welcome-message {
            color: #666;
            font-size: 18px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }
        
        .dashboard-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            text-decoration: none;
            color: inherit;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        
        .card-icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .card-description {
            color: #666;
            line-height: 1.5;
        }
        
        .livraison-card {
            border-top: 4px solid #4CAF50;
        }
        
        .benefice-card {
            border-top: 4px solid #2196F3;
        }
        
        .stats-card {
            border-top: 4px solid #FF9800;
        }
        
        .footer {
            text-align: center;
            padding: 20px;
            color: #777;
            margin-top: 40px;
            border-top: 1px solid #eee;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
            
            .main-content {
                padding: 20px;
            }
            
            .welcome-section {
                padding: 25px;
            }
            
            .welcome-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>🎄 Livraison Noël S3</h1>
        </div>
        
        <div class="user-info">
            <span class="user-name">Bienvenue, <?php echo htmlspecialchars($user['nomUser']); ?>!</span>
            <a href="/logout" class="btn-logout">Déconnexion</a>
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
                <a href="/app/livraison" class="dashboard-card livraison-card">
                    <div class="card-icon">🚚</div>
                    <h3 class="card-title">Gestion des Livraisons</h3>
                    <p class="card-description">
                        Créez de nouvelles livraisons, suivez leur statut et gérez les commandes en cours.
                    </p>
                </a>
                
                <a href="/app/benefice" class="dashboard-card benefice-card">
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