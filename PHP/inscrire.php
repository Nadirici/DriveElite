<?php


require_once("databasecreation.php");



$user = 'root';
$pass = '';



if ($_SERVER["REQUEST_METHOD"] == "POST") {

   
    // Récupérer les données du formulaire
    $pseudonyme = $_POST['pseudonyme'];
    $email = $_POST['email'];
    $motdepasse = $_POST['motdepasse'];

    // création de la base si elle n'existe pas
    createDatabase();

    //Connexion à la base 
    try {
        // Connexion à la base de données
        $db = new PDO("mysql:host=localhost;dbname=DriveElite", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connexion à la base de données échouée : " . $e->getMessage());
    }

    // Vérifie si le pseudonyme ou l'adresse e-mail existe déjà dans la base de données
    try {
        $query_check = $db->prepare("SELECT COUNT(*) FROM clients WHERE nom = :nom OR email = :email");
        $query_check->bindParam(':nom', $pseudonyme);
        $query_check->bindParam(':email', $email);
        $query_check->execute();
        $result = $query_check->fetchColumn();

        if ($result > 0) {
            $errorMessage = "Le pseudonyme ou l'adresse e-mail est déjà utilisé. Veuillez en choisir un autre.";
        } else {
            // Ajout des données à la base de données
            try {
                $query = $db->prepare("INSERT INTO clients (nom, email, mot_de_passe) VALUES (:nom, :email, :mot_de_passe)");
                $query->bindParam(':nom', $pseudonyme);
                $query->bindParam(':email', $email);
                $query->bindParam(':mot_de_passe', $motdepasse);
                $query->execute();
                
                // Récupére l'ID du client nouvellement inséré
                $client_id = $db->lastInsertId();

                // Stocke l'ID du client dans une variable de session

                $_SESSION['client_id'] = $client_id;
                // Redirige vers la page de connexion
                header("Location: connexion.php");
                exit();
            } catch (PDOException $e) {
                // En cas d'erreur lors de l'ajout à la base de données
                die("Erreur lors de l'ajout à la base de données : " . $e->getMessage());
            }
        }
    } catch (PDOException $e) {
        die("Erreur lors de la vérification dans la base de données : " . $e->getMessage());
    }
}

// Ferme la connexion à la base de données
$db = null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Inscription</title>
    <link rel="icon" href="../images/icon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="../css/inscrire.css">
</head>
<header>
        <div class="logo">
            <a href="../index.php"><img class="logo" src="../images/drive_elite-modified.png" alt="logo"></a>
        </div>
    </header>
<body>
    <div class="inscrirebox">
        <h1>S'inscrire</h1>
        <h4>Pour bénéficier pleinement des avantages offerts par DrivElite.</h4>
        <form action="inscrire.php" method="post">
            <?php if (isset($errorMessage)) { ?>
                <div class="error-message"><?php echo $errorMessage ?></div>
            <?php } ?>
            <label for="pseudonyme">Pseudonyme</label>
            <input type="text" placeholder="" name="pseudonyme" id="pseudonyme" autocomplete="off" required>
            <label for="email">Adresse mail</label>
            <input type="email" placeholer="" name="email" id="email" autocomplete="off" required>
            <label for="motdepasse">Mot de passe</label>
            <input type="password" placeholder="" name="motdepasse" id="motdepasse" autocomplete="off" required>
            <input type="submit" class="btn" name="enregistrer" value="Enregistrer" required>
            <p class="para-2">Vous avez déjà un compte ? <a href="connexion.php">Connectez-vous</a></p>
        </form>
    </div>
    <footer>
        <p>Information sur le site web</p>

    </footer>
</body>
</html>
