<?php
session_start();

// Vérifier si l'utilisateur est connecté via les cookies
if (!isset($_COOKIE["user_email"]) || !isset($_COOKIE["user_motdepasse"]) || !isset($_COOKIE["user_pseudonyme"])) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: connexion.php");
    exit(); // Arrête l'exécution du script après la redirection
}

// Récupérer les informations d'authentification depuis les cookies
$email = $_COOKIE["user_email"];
$motdepasse = $_COOKIE["user_motdepasse"];
$pseudonyme = $_COOKIE["user_pseudonyme"];
$_SESSION["pseudonyme"]=$pseudonyme;

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/icon.jpg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/profil.css">
    <title>Profil</title>
</head>
<body>
<header>    
    <div class="logo">
        <a href="../index.php"><img class="logo" src="../images/drive_elite-modified.png" alt="logo"></a>
    </div>
    
    <nav class="navbar">
        <ul class="nav_links">
            <li><a href="Citadines.php">Citadines</a></li>
            <li><a href="SUV.php">SUV</a></li>
            <li><a href="Sportives.php">Sportives</a></li>
        </ul>
    </nav>
    <div class="cta">
            <a  href="profil.php"><button>Profil</button></a>
            <a  href="panier.php"><button>Panier</button></a>
            <a  href="contact.php"><button>Contactez-nous</button></a>
    </div>
    
</header>
    
    <div class="container">
        <!-- MENU DE GAUCHE -->
        <div class="LeftMenu">
            <div class="menu" >
                <ul>
                    <div class="links">
                        <li id="menu">Menu</li>
                    </div>
                    <div class="links">
                        <li id="citadine"><a href="Citadines.php">Citadines</a></li>
                    </div>
                    <div class="links">
                        <li id="SUV"><a href="SUV.php">SUV</a></li>
                    </div>
                    <div class="links">
                        <li id="sportive"><a href="Sportives.php">Sportive</a></li>
                    </div>
                    <div class="links">
                        <li id="contact"><a href="contact.php">Contactez-nous</a></li>
                    </div>
                </ul>
            </div>
        </div>
        

        <div class="body-form">
            <div class="formulaire-en-lui-meme">
                <div class="title">
                    Information du profil
                </div>
                <form  action="deconnexion.php" method="POST">
                    <div class="user-details">

                        <div class="input-box email-box">
                            <span class="details">Votre email : </span>
                            <input type="email" name="email" placeholder="email" value="<?php echo $email; ?>" readonly>
                            <div class="error-message" id="email-error"></div>
                        </div>

                        <div class="input-box nom-box">
                            <span class="details">Votre pseudonyme : </span>
                            <input type="text" name="nom" placeholder="Pseudonyme" value="<?php echo $pseudonyme; ?>" readonly>
                            <div class="error-message" id="nom-error"></div>
                        </div>
                        <div class="input-box nom-box">
                            <span class="details">Mot de passe : </span>
                            <input type="text" name="motdepasse" placeholder="Mot de passe" value="<?php echo $motdepasse; ?>" readonly>
                            <div class="error-message" id="motdepasse-error"></div>
                        </div>

                    </div>
                    <div class="button">
                        <input type="submit" value="Déconnexion" href="deconnexion.php">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <footer>
    <p>Copyright Société DriveElite<br>
            Webmaster CY Tech
        </p>
    </footer>

</body>
</html>
