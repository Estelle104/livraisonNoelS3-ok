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
        <!-- Section gauche : Formulaire d'insertion (Estelle) -->
        <section class="section">
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
                    <label for="destination">Destination *</label>
                    <input type="text" id="destination" name="destination" 
                           placeholder="Ex: Antananarivo Centre" required>
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
                
                <div class="form-row">
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
                </div>
                
                <div class="form-group">
                    <label for="dateLivraison">Date de livraison prévue</label>
                    <input type="datetime-local" id="dateLivraison" name="dateLivraison">
                </div>
                
                <button type="submit" class="btn-primary">
                    💾 Enregistrer la livraison
                </button>
            </form>
        </section>
        
        <!-- Section droite : Liste des livraisons (Andry) -->
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
                                    <td><?php echo htmlspecialchars($livraison['destination']); ?></td>
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
                                        <?php if ($livraison['etatlivraison'] === 'EN_ATTENTE'): ?>

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

                                            <?php else: ?>
                                            <button class="btn-action btn-desactiver" disabled>✓ Livrer</button>
                                            <button class="btn-action btn-desactiver" disabled>✗ Annuler</button>
                                            <?php endif; ?>

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

    <script>
        // Gestion du formulaire de livraison
        // document.getElementById('livraisonForm').addEventListener('submit', function(e) {
        //     e.preventDefault();
            
        //     const formData = new FormData(this);
            
        //     fetch('<?= BASE_URL ?>/livraison', {
        //         method: 'POST',
        //         body: formData
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             showMessage('Livraison créée avec succès!', 'success');
        //             this.reset();
        //             refreshLivraisons();
        //         } else {
        //             showMessage('Erreur lors de la création', 'error');
        //         }
        //     })
        //     .catch(error => {
        //         console.error('Error:', error);
        //         showMessage('Erreur de connexion', 'error');
        //     });
        // });
        
        // Gestion du formulaire de colis
        // document.getElementById('colisForm').addEventListener('submit', function(e) {
        //     e.preventDefault();
            
        //     const colisData = {
        //         descriptionColi: document.getElementById('newDescription').value,
        //         poidsColis: document.getElementById('newPoids').value,
        //         prixUnitaire: document.getElementById('newPrix').value
        //     };
            
        //     fetch('<?= BASE_URL ?>/colis', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/x-www-form-urlencoded',
        //         },
        //         body: new URLSearchParams(colisData)
        //     })
        //     .then(response => response.json())
        //     .then(data => {
        //         if (data.success) {
        //             // Ajouter le nouveau colis à la liste déroulante
        //             const select = document.getElementById('idColis');
        //             const newOption = document.createElement('option');
        //             newOption.value = data.id;
        //             newOption.textContent = `${colisData.descriptionColi} - ${colisData.poidsColis}kg`;
        //             select.appendChild(newOption);
        //             select.value = data.id;
                    
        //             closeColisModal();
        //             showMessage('Colis créé avec succès!', 'success');
        //         } else {
        //             showMessage('Erreur lors de la création du colis', 'error');
        //         }
        //     });
        // });
        
        // Mettre à jour le statut d'une livraison
        function updateLivraisonStatus(livraisonId, newStatusId) {
            if (!confirm('Confirmer la modification du statut?')) return;
            
            const data = new URLSearchParams();
            data.append('idEtat', newStatusId);
            
            fetch(`<?= BASE_URL ?>/livraison/${livraisonId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: data
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage('Statut mis à jour avec succès!', 'success');
                    refreshLivraisons();
                } else {
                    showMessage('Erreur lors de la mise à jour', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Erreur de connexion', 'error');
            });
        }
        
        // Rafraîchir la liste des livraisons
        function refreshLivraisons() {
            fetch('<?= BASE_URL ?>/livraison')
            .then(response => response.text())
            .then(html => {
                // Cette fonction nécessiterait une API dédiée pour la liste seule
                // Pour l'instant, on recharge la page
                location.reload();
            });
        }
        
        // Gestion du modal colis
        function openColisModal() {
            document.getElementById('colisModal').style.display = 'flex';
        }
        
        function closeColisModal() {
            document.getElementById('colisModal').style.display = 'none';
            document.getElementById('colisForm').reset();
        }
        
        // Afficher les messages
        function showMessage(text, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = text;
            messageDiv.className = `message ${type}`;
            
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }
        
        // Fermer le modal en cliquant à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('colisModal');
            if (event.target == modal) {
                closeColisModal();
            }
        }
    </script>
</body>
</html>