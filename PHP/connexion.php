<?php


require_once("databasecreation.php");


// Vérifie si l'utilisateur est déjà connecté via les cookies
if(isset($_COOKIE["user_email"]) && isset($_COOKIE["user_motdepasse"])) {
    // Rediriger vers la page de profil
    header("Location: profil.php");
    exit;
}

function loginUser($email, $motdepasse) {
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "DriveElite";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM Clients WHERE email = :email AND mot_de_passe = :motdepasse");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':motdepasse', $motdepasse);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Stocker les informations d'authentification dans les cookies
            setcookie("user_email", $user['email'], time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie("user_motdepasse", $user['mot_de_passe'], time() + (86400 * 30), "/"); // 86400 = 1 day
            setcookie("user_pseudonyme", $user['nom'], time() + (86400 * 30), "/"); // 86400 = 1 day
            
            return true;
        } else {
            return false; // Identifiants incorrects
        }
    } catch(PDOException $e) {
        // echo "Erreur : " . $e->getMessage();
        return false;
    }

}

// Vérifie si le formulaire de connexion a été soumis
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifie si les champs email et mot de passe sont définis
    if(isset($_POST["email"]) && isset($_POST["motdepasse"])) {
        $email = trim($_POST['email']);

        $motdepasse = trim($_POST['motdepasse']);

        // Vérifier les informations de connexion
        if (loginUser($email, $motdepasse)) { 

            createDatabase();
            $_SESSION["motdepasse"]=$motdepasse;
            
            // Rediriger vers la page de profil après connexion réussie
            header("Location: SUV.php");
            exit;
        } else {
            $errorMessage = "Identifiants incorrects!";
        }
    } else {
        $errorMessage = "Veuillez remplir tous les champs.";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/icon.jpg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/connexion.css">
    <title>Connexion</title>
</head>



<body>
    <header>
        <div class="logo">
            <a href="../index.php"><img class="logo" src="../images/drive_elite-modified.png" alt="logo"></a>
        </div>
    </header>

    <div class="container">
        <div class="connexionbox">
            <h1 >Connexion</h1>
            <?php if (isset($errorMessage)) { ?>
                <div class="error-message"><?php echo $errorMessage; ?></div>
            <?php } ?>
            <form action="" method="post">
                <label for="email">Adresse mail</label>
                <input type="email" placeholder="" name="email" id="email" autocomplete="off" required>
                <label for="motdepasse">Mot de passe</label>
                <input type="password" placeholder="" name="motdepasse" id="motdepasse" autocomplete="off" required>
                <input type="submit" class="btn" name="submit" value="Se connecter" required>
                <p class="para-2">Vous n'avez pas de compte ? <a href="inscrire.php">Inscrivez-vous</a></p>
            </form>
        </div>
    </div>

    <footer>
    <p>Copyright Société DriveElite<br>
            Webmaster CY Tech
        </p>
    </footer>
</body>

</html>
