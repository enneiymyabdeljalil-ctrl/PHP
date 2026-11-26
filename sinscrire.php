<?php
session_start(); 
try {
    $c = new PDO("mysql:host=localhost;port=3306;dbname=greenmarket", "root", "");
    $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$err = [];
$reg_success = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    extract($_POST);

    if (!isset($nom) || empty(trim($nom))) $err['nom'] = "Veuillez entrer votre nom";
    if (!isset($prenom) || empty(trim($prenom))) $err['prenom'] = "Veuillez entrer votre prénom";
    if (!isset($adresse) || empty(trim($adresse))) $err['adresse'] = "Veuillez entrer votre adresse";
    if (!isset($telephone) || empty(trim($telephone))) $err['telephone'] = "Veuillez entrer votre téléphone";
    
    if (!isset($email) || empty(trim($email))) {
        $err['email'] = "Veuillez entrer votre email";
    } else {
        try {
            $res = $c->prepare("SELECT * FROM utilisateur WHERE email = ?");
            $res->execute([$email]);
            if ($res->rowCount() != 0) $err['email'] = "Cet adresse email est déjà utilisée !";
        } catch (PDOException $e) {
            die("Erreur selection email : " . $e->getMessage());
        }
    }

    if (!isset($mot_de_passe) || empty($mot_de_passe)) $err['mot_de_passe'] = "Veuillez entrer un mot de passe";
    if (!isset($confirm_password) || empty($confirm_password)) {
        $err['confirm_password'] = "Veuillez confirmer le mot de passe";
    } elseif ($mot_de_passe !== $confirm_password) {
        $err['confirm_password'] = "Les mots de passe ne sont pas identiques !";
    }

    if (!isset($role) || empty($role)) $err['role'] = "Veuillez choisir un rôle";

    if (empty($err)) {
        $nom = htmlspecialchars(trim($nom));
        $prenom = htmlspecialchars(trim($prenom));
        $email = htmlspecialchars(trim($email));
        $adresse = htmlspecialchars(trim($adresse));
        $telephone = htmlspecialchars(trim($telephone));
        $date_inscription = date('Y-m-d');
        $statut_compte = ($role == 'producteur') ? 'en_attente' : 'actif';

        $hashed_password = password_hash($mot_de_passe, PASSWORD_DEFAULT);

        try {
            $ri = $c->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, adresse, telephone, role, date_inscription, statut_compte) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $r = $ri->execute([$nom, $prenom, $email, $hashed_password, $adresse, $telephone, $role, $date_inscription, $statut_compte]);
            
            if ($r == false) {
                echo "Echec d'insertion";
            } else {
                $reg_success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
                $nom = $prenom = $email = $adresse = $telephone = $role = "";
            }
        } catch (PDOException $e) {
            die("Erreur insertion utilisateur : " . $e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - GreenMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #FAF1E0;
            font-family: 'Times New Roman', Times, serif; 
            font-size: 16px;
        }
        .navbar {
            background-color: #FAF1E0 !important;
            padding: 15px 0;
            border-bottom: 1px solid rgba(105, 86, 31, 0.1);
            box-shadow: none;
        }

        .navbar-brand {
            font-family: 'Times New Roman', Times, serif;
            font-weight: bold;
            font-size: 26px;
            letter-spacing: 0.5px;
            color: #69561F !important;
        }

        .navbar-nav .nav-link {
            font-family: 'Times New Roman', Times, serif;
            color: #69561F !important;
            font-weight: bold;
            font-size: 16px; 
            text-transform: capitalize;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            padding: 8px 18px !important;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link i {
            font-size: 14px;
            color: #69561F !important;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .navbar-nav .nav-link:hover i {
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link:hover {
            opacity: 0.8;
            transform: translateY(-1px);
        }

        .register-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(105, 86, 31, 0.08);
            padding: 40px;
            background: white;
        }
        h2 {
            color: #69561F;
            font-weight: bold;
        }
        .btn-green {
            background-color: #69561F;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
            transition: all 0.2s;
        }
        .btn-green:hover {
            background-color: #544418;
            color: white;
        }
        .login-link a {
            text-decoration: none;
            color: #69561F;
            font-weight: bold;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .navbar {
            background-color: #69561F;
            padding: 15px 0;
        }
        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: 600;
        }
    </style>
</head>
<body>


    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="register-card">
                    <h2 class="text-center mb-4">Créer un compte</h2>

                    <?php if(!empty($reg_success)): ?>
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="fa-solid fa-circle-check me-2"></i>
                            <div><?php echo $reg_success; ?></div>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="sinscrire.php" class="needs-validation">
                        
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control <?php echo isset($err['nom']) ? 'is-invalid' : ''; ?>" placeholder="Entrer votre nom" value="<?php echo isset($nom) ? htmlspecialchars($nom) : ''; ?>">
                            <?php if(isset($err['nom'])): ?>
                                <div class="invalid-feedback"><?php echo $err['nom']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" name="prenom" class="form-control <?php echo isset($err['prenom']) ? 'is-invalid' : ''; ?>" placeholder="Entrer votre prénom" value="<?php echo isset($prenom) ? htmlspecialchars($prenom) : ''; ?>">
                            <?php if(isset($err['prenom'])): ?>
                                <div class="invalid-feedback"><?php echo $err['prenom']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control <?php echo isset($err['email']) ? 'is-invalid' : ''; ?>" placeholder="Entrer votre email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                            <?php if(isset($err['email'])): ?>
                                <div class="invalid-feedback"><?php echo $err['email']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control <?php echo isset($err['adresse']) ? 'is-invalid' : ''; ?>" placeholder="Entrer votre adresse" value="<?php echo isset($adresse) ? htmlspecialchars($adresse) : ''; ?>">
                            <?php if(isset($err['adresse'])): ?>
                                <div class="invalid-feedback"><?php echo $err['adresse']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control <?php echo isset($err['telephone']) ? 'is-invalid' : ''; ?>" placeholder="Entrer votre téléphone" value="<?php echo isset($telephone) ? htmlspecialchars($telephone) : ''; ?>">
                            <?php if(isset($err['telephone'])): ?>
                                <div class="invalid-feedback"><?php echo $err['telephone']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="mot_de_passe" class="form-control <?php echo isset($err['mot_de_passe']) ? 'is-invalid' : ''; ?>" placeholder="Créer un mot de passe">
                            <?php if(isset($err['mot_de_passe'])): ?>
                                <div class="invalid-feedback"><?php echo $err['mot_de_passe']; ?></div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Vérifier le mot de passe</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo isset($err['confirm_password']) ? 'is-invalid' : ''; ?>" placeholder="Confirmer le mot de passe">
                            <?php if(isset($err['confirm_password'])): ?>
                                <div class="invalid-feedback"><?php echo $err['confirm_password']; ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Vous êtes :</label>
                            
                            <div class="form-check">
                                <input class="form-check-input <?php echo isset($err['role']) ? 'is-invalid' : ''; ?>" type="radio" name="role" id="client" value="client" <?php if(!isset($role) || $role == 'client') echo 'checked'; ?>>
                                <label class="form-check-label" for="client">Client (Acheteur)</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input <?php echo isset($err['role']) ? 'is-invalid' : ''; ?>" type="radio" name="role" id="producteur" value="producteur" <?php if(isset($role) && $role == 'producteur') echo 'checked'; ?>>
                                <label class="form-check-label" for="producteur">Producteur (Vendeur)</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input <?php echo isset($err['role']) ? 'is-invalid' : ''; ?>" type="radio" name="role" id="administrateur" value="administrateur" <?php if(isset($role) && $role == 'administrateur') echo 'checked'; ?>>
                                <label class="form-check-label" for="administrateur">Administrateur</label>
                                <?php if(isset($err['role'])): ?>
                                    <div class="invalid-feedback d-block"><?php echo $err['role']; ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-green w-100"><i class="fa-solid fa-user-plus me-2"></i> S'inscrire</button>

                        <div class="text-center mt-4 login-link">
                            <p>Vous avez déjà un compte ? <a href="authentification.php">Se connecter</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>