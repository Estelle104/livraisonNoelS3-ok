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
    <title>Gestion Livraisons - Livraison Noël</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>🚚 Gestion des Livraisons</h1>
        </div>
        
        <div class="nav-buttons">
            <a href="<?= BASE_URL ?>/accueil" class="nav-btn">🏠 Accueil</a>
            <a href="<?= BASE_URL ?>/benefice" class="nav-btn">💰 Bénéfices</a>
            <a href="<?= BASE_URL ?>/logout" class="nav-btn">🔒 Déconnexion</a>
        </div>
    </header>
    
    <main class="main-container">
        
        <!--  gauche : Formulaire d'insertion (Estelle) -->
        <section class="section">
            <form id="livraisonSupprimer" action="<?= BASE_URL ?>/livraison/supprimerTout" method="POST">
                <input type="number" name="code" placeholder="Entrez 9999 pour confirmer et supprimer tout" required>
                <button type="submit" class="btn-primary" onclick="return confirm('Êtes-vous sûr de vouloir supprimer toutes les livraisons ?')">🗑️ Supprimer toutes les livraisons</button>
            </form>
            <h2 class="section-title">➕ Nouvelle Livraison</h2>

            
            
            <div id="message" class="message"></div>
            
            <form id="livraisonForm" action="<?= BASE_URL ?>/livraison" method="POST">
                <div class="form-group">
                    <label for="idColis">Colis *</label>
                    <div style="display: flex; gap: 10px;">
                        <select id="idColis" name="idColis" required>
                            <option value="">Sélectionnez un colis</option>
                            <?php foreach ($colis as $col): ?>
                                <option value="<?php echo $col['id']; ?>">
                                    <?php echo htmlspecialchars($col['descriptionColi'] . ' - ' . $col['poidsColis'] . 'kg'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <a href="<?= BASE_URL ?>/livraison?colis=1" style="background:#17a2b8; color:white; padding:20px 20px; border-radius:6px; text-decoration:none;">
                            +
                        </a>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="idEntrepot">Entrepôt de départ *</label>
                    <select id="idEntrepot" name="idEntrepot" required>
                        <option value="">Sélectionnez un entrepôt</option>
                        <?php foreach ($entrepots as $entrepot): ?>
                            <option value="<?php echo $entrepot['id']; ?>">
                                <?php echo htmlspecialchars($entrepot['nomEntrepot']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="idDestination">Destination *</label>
                    <select id="idDestination" name="idDestination" required>
                        <option value="">Sélectionnez une destination</option>
                        <?php foreach ($destinations as $destination): ?>
                            <option value="<?php echo $destination['id']; ?>">
                                <!-- <?php echo htmlspecialchars($destination['nomDestination'] . 
                                    (isset($destination['zoneLivraison']) ? 
                                    ' (' . $destination['zoneLivraison'] . ')' : '')); ?> -->
                                <?php echo htmlspecialchars($destination['zoneLivraison']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="idVehicule">Véhicule *</label>
                        <select id="idVehicule" name="idVehicule" required>
                            <option value="">Sélectionnez un véhicule</option>
                            <?php foreach ($vehicules as $vehicule): ?>
                                <option value="<?php echo $vehicule['id']; ?>">
                                    <?php echo htmlspecialchars($vehicule['nomVehicule'] . ' (' . $vehicule['nomSociete'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="idChauffeur">Chauffeur *</label>
                        <select id="idChauffeur" name="idChauffeur" required>
                            <option value="">Sélectionnez un chauffeur</option>
                            <?php foreach ($chauffeurs as $chauffeur): ?>
                                <option value="<?php echo $chauffeur['id']; ?>">
                                    <?php echo htmlspecialchars($chauffeur['nomChauffeur'] . ' (' . $chauffeur['nomSociete'] . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- <div class="form-row">
                    <div class="form-group">
                        <label for="coutVoiture">Coût véhicule (Ar)</label>
                        <input type="number" id="coutVoiture" name="coutVoiture" 
                               step="0.01" min="0" placeholder="0.00">
                    </div>
                    
                    <div class="form-group">
                        <label for="salaireChauffeur">Salaire chauffeur (Ar)</label>
                        <input type="number" id="salaireChauffeur" name="salaireChauffeur" 
                               step="0.01" min="0" placeholder="0.00">
                    </div>
                </div> -->
                
                <div class="form-group">
                    <label for="dateLivraison">Date de livraison prévue</label>
                    <input type="datetime-local" id="dateLivraison" name="dateLivraison">
                </div>
                
                <button type="submit" class="btn-primary">
                    💾 Enregistrer la livraison
                </button>
            </form>
        </section>
        
        <!--  droite : Liste des livraisons (Andry) -->
        <section class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <h2 class="section-title">📋 Liste des Livraisons</h2>
                <button onclick="refreshLivraisons()" class="refresh-btn">
                    🔄 Actualiser
                </button>
            </div>
            
            <div class="table-container">
                <table id="livraisonsTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Colis</th>
                            <th>Véhicule</th>
                            <th>Chauffeur</th>
                            <th>Salaire de base</th>
                            <th>Salaire + zone</th>
                            <th>Destination</th>
                            <th>Date</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($livraisons)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 40px;">
                                    Aucune livraison trouvée
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($livraisons as $livraison): ?>
                                <tr id="row-<?php echo $livraison['id']; ?>">
                                    <td><?php echo $livraison['id']; ?></td>
                                    <td><?php echo htmlspecialchars($livraison['descriptionColi']); ?></td>
                                    <td><?php echo htmlspecialchars($livraison['nomVehicule']); ?></td>
                                    <td><?php echo htmlspecialchars($livraison['nomChauffeur']); ?></td>
                                    <td><?php echo number_format($livraison['salaire'] , 2, ',', ' '); ?> Ar</td>
                                    <td><?php echo number_format($livraison['salaire'] + (($livraison['pourcentageZone'] / 100) * $livraison['salaire']), 2, ',', ' '); ?> Ar</td>
                                    <td><?php echo htmlspecialchars($livraison['nomDestination']); ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($livraison['dateLivraison'])); ?></td>
                                    <td>
                                        <?php 
                                            $statusClass = 'status-' . strtolower(str_replace('_', '', $livraison['etatlivraison']));
                                            $statusText = str_replace('_', ' ', $livraison['etatlivraison']);
                                        ?>
                                        <span class="status-badge <?php echo $statusClass; ?>">
                                            <?php echo $statusText; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                        <?php if ($livraison['etatlivraison'] === 'EN_ATTENTE'){ ?>

                                            <form method="POST" action="<?= BASE_URL ?>/livraison/<?= $livraison['id'] ?>" style="display:inline;">
                                                <input type="hidden" name="idEtat" value="2">
                                                <button type="submit" class="btn-action btn-livrer">
                                                    ✓ Livrer
                                                </button>
                                            </form>

                                            <form method="POST" action="<?= BASE_URL ?>/livraison/<?= $livraison['id'] ?>" style="display:inline;">
                                                <input type="hidden" name="idEtat" value="3">
                                                <button type="submit" class="btn-action btn-annuler">
                                                    ✗ Annuler
                                                </button>
                                            </form>

                                            <?php }else{ ?>
                                            <button class="btn-action btn-desactiver" disabled>✓ Livrer</button>
                                            <button class="btn-action btn-desactiver" disabled>✗ Annuler</button>
                                            <?php } ?>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    
    <!-- fORM ajout colis (Estelle)-->
    <?php if (isset($_GET['colis']) && $_GET['colis'] == 1): ?>
        <div class="modal" style="display:flex;">
        <div class="modal-content">
            <h2>➕ Nouveau Colis</h2>

            <form method="POST" action="<?= BASE_URL ?>/colis">
                <div class="form-group">
                    <label>Description</label>
                    <input type="text" name="description" required>
                </div>

                <div class="form-group">
                    <label>Poids (kg)</label>
                    <input type="number" name="poids" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Prix unitaire (Ar/kg)</label>
                    <input type="number" name="prix" step="0.01" required>
                </div>

                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn-primary">Créer</button>

                    <a href="<?= BASE_URL ?>/livraison" 
                    style="background:#6c757d; color:white; padding:14px 30px; border-radius:8px; text-decoration:none;">
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

</body>
</html>