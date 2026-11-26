<?php
require_once __DIR__ . '/app.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
    extract($_POST);
    $err =[];
    if(!isset($Email) || empty($Email)) $err['username']="Veuillez entrer votre email ";
    if(!isset($mdp) || empty($mdp)) $err['mdp']="Veuillez entrer un mot de passe ";
    
    if(empty($err)){
        $Email= trim($Email);
        try{
            $c = db();
            
            $reqs = $c->prepare("SELECT * FROM utilisateur WHERE email = ? ");
            $reqs->execute([$Email]);
            $tuser = $reqs->fetch(PDO::FETCH_ASSOC);
            
            if(empty($tuser)){
                $login_error = "Email ou mot de passe incorrects";
            }
            else {
                // 💡 تعديل ذكي: يقبل كلمة المرور المشفرة أو العادية (لتسهيل التجربة اليدوية)
                if (password_verify($mdp, $tuser['mot_de_passe']) || $mdp === $tuser['mot_de_passe']) {
                    if ($tuser['role'] == 'producteur' && $tuser['statut_compte'] == 'en_attente') {
                        $login_error = "Votre compte est en attente de validation par l'administrateur.";
                    } else {
                        $_SESSION['idu'] = $tuser['id'];
                        $_SESSION['nomu'] = $tuser['nom'];
                        $_SESSION['roleu'] = $tuser['role'];

                        redirect_dashboard($_SESSION['roleu']);
                    }
                }
                else {
                    $login_error = "Email ou mot de passe incorrects";
                }
            }
        }
        catch(PDOException $e) {
            die("Erreur authentification :".$e->getMessage());
        }
    }
}
?> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - GreenMarket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
        body {
            /* Forçage du background beige sur tout le corps de la page */
            background-color: #FAF1E0 !important;
            font-family: 'Times New Roman', Times, serif; 
            font-size: 16px;
        }
        h2 {
            color: #69561F;
            font-weight: bold;
        }
        .login-card {
            border: none;
            border-radius: 20px;
            /* Conservation du fond blanc pour la carte pour que le formulaire reste bien lisible */
            background-color: #ffffff !important; 
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.08) !important;
        }
        .toggle-password {
            cursor: pointer;
            border-color: rgba(105, 86, 31, 0.2);
            color: #69561F;
        }
        .form-control {
            border: 1px solid rgba(105, 86, 31, 0.2);
            border-radius: 10px;
            padding: 10px 15px;
            background-color: #ffffff;
        }
        .form-control:focus {
            border-color: #69561F;
            box-shadow: 0 0 0 0.25rem rgba(105, 86, 31, 0.15);
            background-color: #ffffff;
        }
        .extra-links a {
            text-decoration: none;
            color: #69561F;
            font-weight: 500;
        }
        .extra-links a:hover {
            text-decoration: underline;
        }
        #se {
            background-color: #69561F;
            border-radius: 10px;
            color: white;
            border: none;
            padding: 12px;
            font-weight: bold;
            transition: all 0.2s ease;
        }
        #se:hover {
            background-color: #544418;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

   <?php include("header.php")?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow login-card p-4 bg-white">
                <h2 class="text-center mb-4">Connexion</h2>

                <?php if(isset($login_error)): ?>
                    <div class="alert alert-danger" style="color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; padding: 12px; border-radius: 10px; margin-bottom: 20px; font-size: 14px; font-weight: 500;">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i> <?php echo $login_error; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="authentification.php">
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: #69561F;">Email</label>
                        <?php if(isset($err['username'])) echo "<div style='color:red; font-size:13px; margin-bottom:5px;'>".$err['username']."</div>"; ?>
                        <input type="email" name="Email" class="form-control" placeholder="Entrer votre email" value="<?php if(isset($Email)) echo htmlspecialchars($Email); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: #69561F;">Mot de passe</label>
                        <?php if(isset($err['mdp'])) echo "<div style='color:red; font-size:13px; margin-bottom:5px;'>".$err['mdp']."</div>"; ?>
                        <div class="input-group">
                            <input type="password" name="mdp" class="form-control" id="password" placeholder="Entrer votre mot de passe">
                            <button class="btn btn-outline-secondary toggle-password" type="button" onclick="togglePassword()">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3 text-end extra-links">
                        <a href="mtp.php" style="font-size: 14px;">Mot de passe oublié ?</a>
                    </div>
                    <button type="submit" id="se" class="w-100 shadow-sm mt-2">
                        Se connecter
                    </button>
                    <div class="text-center mt-4 extra-links">
                        <p class="mb-0" style="font-size: 15px; color: #333;">
                            Pas encore de compte ?
                            <a href="sinscrire.php" class="fw-bold ms-1">S'inscrire</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword(){
        const password = document.getElementById("password");
        const icon = document.querySelector(".toggle-password i");
        if(password.type === "password"){
            password.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }else{
            password.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    const form = document.querySelector("form");
    form.addEventListener("submit", function (e) {
        const email = document.querySelector('input[type="email"]').value;
        const password = document.getElementById("password").value;

        if(email === "" || password === ""){
            e.preventDefault();
            alert("Veuillez remplir tous les champs");
        }
    });
</script>
</body>
</html>
