<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Livraison Noël</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h2 {
            color: #667eea;
            font-size: 24px;
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
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .error-message {
            background-color: #fee;
            color: #c33;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
            display: none;
        }
        
        .error-message.show {
            display: block;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h2>🎄 Livraison Noël S3</h2>
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