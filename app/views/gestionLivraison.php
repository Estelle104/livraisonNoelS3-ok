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
    <title>Gestion Livraisons - Livraison Noël</title>
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
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="number"],
        input[type="datetime-local"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            width: 100%;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
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
        
        .table-container {
            overflow-x: auto;
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
            vertical-align: top;
        }
        
        tr:hover {
            background: #f8f9fa;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-en_attente {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-livre {
            background: #d4edda;
            color: #155724;
        }
        
        .status-annule {
            background: #f8d7da;
            color: #721c24;
        }
        
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        
        .btn-action {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        
        .btn-action:hover {
            opacity: 0.8;
        }
        
        .btn-livrer {
            background: #28a745;
            color: white;
        }
        
        .btn-annuler {
            background: #dc3545;
            color: white;
        }
        
        .btn-desactiver {
            background: #6c757d;
            color: white;
            cursor: not-allowed;
            opacity: 0.5;
        }
        
        .modal {
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
        
        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        
        .message {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            display: block;
        }
        
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            display: block;
        }
        
        .colis-form {
            display: grid;
            gap: 15px;
        }
        
        .refresh-btn {
            background: #17a2b8;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <h1>🚚 Gestion des Livraisons</h1>
        </div>
        
        <div class="nav-buttons">
            <a href="/app/accueil" class="nav-btn">🏠 Accueil</a>
            <a href="/app/benefice" class="nav-btn">💰 Bénéfices</a>
            <a href="/logout" class="nav-btn">🔒 Déconnexion</a>
        </div>
    </header>
    
    <main class="main-container">
        <!-- Section gauche : Formulaire d'insertion (Estelle) -->
        <section class="section">
            <h2 class="section-title">➕ Nouvelle Livraison</h2>
            
            <div id="message" class="message"></div>
            
            <form id="livraisonForm" action="/app/livraison" method="POST">
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
                        <a href="/app/livraison?colis=1" style="background:#17a2b8; color:white; padding:20px 20px; border-radius:6px; text-decoration:none;">
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

                                            <form method="POST" action="/app/livraison/<?= $livraison['id'] ?>" style="display:inline;">
                                                <input type="hidden" name="idEtat" value="2">
                                                <button type="submit" class="btn-action btn-livrer">
                                                    ✓ Livrer
                                                </button>
                                            </form>

                                            <form method="POST" action="/app/livraison/<?= $livraison['id'] ?>" style="display:inline;">
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
    
    <!-- fORM ajout colis -->
    <?php if (isset($_GET['colis']) && $_GET['colis'] == 1): ?>
        <div class="modal" style="display:flex;">
        <div class="modal-content">
            <h2>➕ Nouveau Colis</h2>

            <form method="POST" action="/app/colis">
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

                    <a href="/app/livraison" 
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
            
        //     fetch('/app/livraison', {
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
            
        //     fetch('/app/colis', {
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
            
            fetch(`/app/livraison/${livraisonId}`, {
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
            fetch('/app/livraison')
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