<?php
if (!isset($_SESSION['logged_in'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Zones - Livraison Noël</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>

<header class="header">
    <div class="logo">
        <h1><span style="color: #3498db;">🗺️</span> Gestion des Zones</h1>
    </div>
    <div class="nav-buttons">
        <a href="<?= BASE_URL ?>/accueil" class="nav-btn"><span style="margin-right: 5px;">🏠</span> Accueil</a>
        <a href="<?= BASE_URL ?>/benefice" class="nav-btn"><span style="margin-right: 5px;">💰</span> Bénéfices</a>
        <a href="<?= BASE_URL ?>/logout" class="nav-btn"><span style="margin-right: 5px;">🔒</span> Déconnexion</a>
    </div>
</header>

<main class="main-container">
    <div class="zones-container">
        <!-- SECTION LISTE -->
        <section class="section zones-section">
            <h2 class="section-title"><span style="color: #3498db;">📋</span> Liste des zones</h2>
            
            <div class="zones-table-container">
                <table class="zones-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Zone</th>
                            <th>Pourcentage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($zones)): ?>
                            <tr>
                                <td colspan="4" class="empty-zones">
                                    <div class="empty-zones-icon">🗺️</div>
                                    <div class="empty-zones-title">Aucune zone disponible</div>
                                    <div class="empty-zones-message">Créez votre première zone dans le formulaire à côté</div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($zones as $zone): ?>
                                <tr>
                                    <td><strong><?= $zone['id'] ?></strong></td>
                                    <td><?= htmlspecialchars($zone['zoneLivraison']) ?></td>
                                    <td>
                                        <?php 
                                            $percent = (float)$zone['pourcentageZone'];
                                            $badgeClass = $percent > 20 ? 'high' : ($percent > 10 ? 'medium' : 'low');
                                        ?>
                                        <span class="percentage-badge <?= $badgeClass ?>">
                                            <?= htmlspecialchars($zone['pourcentageZone']) ?> %
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= BASE_URL ?>/zones/edit/<?= $zone['id'] ?>" 
                                               class="btn-action btn-livrer">
                                                ✏️ Modifier
                                            </a>
                                            <a href="<?= BASE_URL ?>/zones/delete/<?= $zone['id'] ?>"
                                               class="btn-action btn-annuler"
                                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette zone ?');">
                                                🗑️ Supprimer
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (!empty($zones)): ?>
            <div style="margin-top: 25px; padding: 15px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 8px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 14px; color: #718096;">
                        <strong><?= count($zones) ?></strong> zone(s) enregistrée(s)
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </section>
        <!-- ajout -->
        <section class="section zones-section">
            <h2 class="section-title"><span style="color: #3498db;">➕</span> Nouvelle zone</h2>
            
            <form method="POST" action="<?= BASE_URL ?>/zones/store" class="zones-form">
                <div class="form-group">
                    <label>Zone de livraison</label>
                    <input type="text" name="zoneLivraison" required 
                           placeholder="Ex: Antananarivo Centre">
                </div>
                
                <div class="form-group">
                    <label>Pourcentage (%)</label>
                    <input type="number" name="pourcentageZone" step="0.01" min="0" 
                           required placeholder="Ex: 15.50">
                </div>
                
                <button type="submit" class="btn-primary">
                    <span style="margin-right: 8px;">💾</span> Enregistrer la zone
                </button>
            </form>
        </section>
    </div>
</main>

</body>
</html>