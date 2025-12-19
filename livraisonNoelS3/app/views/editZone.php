<?php
if (!isset($_SESSION['logged_in'])) {
    header('Location: ' . BASE_URL . '/login');
    exit;
}

if (!isset($zone)) {
    header('Location: ' . BASE_URL . '/zones');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Zone - Livraison Noël</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>

<header class="header">
    <div class="logo">
        <h1><span style="color: #3498db;">✏️</span> Modifier une zone</h1>
    </div>

    <div class="nav-buttons">
        <a href="<?= BASE_URL ?>/zones" class="nav-btn"><span style="margin-right: 5px;">⬅️</span> Retour</a>
        <a href="<?= BASE_URL ?>/accueil" class="nav-btn"><span style="margin-right: 5px;">🏠</span> Accueil</a>
        <a href="<?= BASE_URL ?>/logout" class="nav-btn"><span style="margin-right: 5px;">🔒</span> Déconnexion</a>
    </div>
</header>

<main class="main-container">
    <div class="edit-zone-container">
        <section class="edit-zone-form">
            <h2 class="section-title"><span style="color: #3498db;">🗺️</span> Informations de la zone</h2>
            
            <?php if (isset($message)): ?>
                <div class="zones-message <?= $message['type'] ?>">
                    <?= $message['text'] ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="<?= BASE_URL ?>/zones/update/<?= $zone['id'] ?>" class="zones-form">
                <div class="form-group">
                    <label>Zone de livraison</label>
                    <input type="text" name="zoneLivraison" 
                           value="<?= htmlspecialchars($zone['zoneLivraison']) ?>" 
                           required placeholder="Nom de la zone">
                </div>

                <div class="form-group">
                    <label>Pourcentage (%)</label>
                    <input type="number" name="pourcentageZone" 
                           step="0.01" min="0"
                           value="<?= htmlspecialchars($zone['pourcentageZone']) ?>"
                           required placeholder="Pourcentage">
                </div>
                
                <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <div style="font-weight: 500; color: #4a5568; margin-bottom: 10px;">📊 Zone ID: <?= $zone['id'] ?></div>
                    <div style="font-size: 14px; color: #718096;">Date de création: <?= date('d/m/Y', strtotime($zone['created_at'] ?? 'now')) ?></div>
                </div>

                <div style="display: flex; gap: 15px; margin-top: 30px;">
                    <button type="submit" class="btn-primary" style="flex: 2;">
                        <span style="margin-right: 8px;">💾</span> Enregistrer les modifications
                    </button>
                    <a href="<?= BASE_URL ?>/zones" class="btn-secondary" style="flex: 1;">
                        <span style="margin-right: 5px;">❌</span> Annuler
                    </a>
                </div>
            </form>
        </section>
    </div>
</main>

</body>
</html>