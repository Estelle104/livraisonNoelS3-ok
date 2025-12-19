<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Livraison Noël</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/styleLogin.css">

</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h2> Livraison Noël S3</h2>
        </div>
        
        <h1>Connexion Responsable</h1>
        
        <div class="error-message" id="errorMessage">
            <?php 
                // session_start();
                if (isset($_SESSION['error'])) {
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                }
            ?>
        </div>
        
        <form action="<?= BASE_URL ?>/login" method="POST" id="loginForm">
            <div class="form-group">
                <label for="loginUser">Nom d'utilisateur</label>
                <input type="text" id="loginUser" name="loginUser" required 
                       placeholder="Entrez votre login">
            </div>
            
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" required 
                       placeholder="Entrez votre mot de passe">
            </div>
            
            <button type="submit" class="btn-login">Se connecter</button>
        </form>
        
        <div class="footer">
            <p>© 2024 Système de Livraison Noël - S3</p>
            <p>Utilisateurs de test: estelle/1234, andry/1234</p>
        </div>
    </div>

    <script>
        // Afficher l'erreur si elle existe
        document.addEventListener('DOMContentLoaded', function() {
            const errorMessage = document.getElementById('errorMessage');
            if (errorMessage.textContent.trim() !== '') {
                errorMessage.classList.add('show');
            }
            
            // Validation du formulaire
            document.getElementById('loginForm').addEventListener('submit', function(e) {
                const loginUser = document.getElementById('loginUser').value;
                const mdp = document.getElementById('mdp').value;
                
                if (!loginUser || !mdp) {
                    e.preventDefault();
                    errorMessage.textContent = 'Veuillez remplir tous les champs';
                    errorMessage.classList.add('show');
                }
            });
        });
    </script>
</body>
</html>