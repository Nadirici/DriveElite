<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Nadir" content="NEHILI">
    <link rel="stylesheet" type="text/css" href="../css/remerciement.css">
    <link rel="icon" href="../images/icon.jpg" type="image/x-icon">
    <title>Remerciement</title>
</head>


<body>
    
    <header>    
        <a href="../index.php"><img class="logo" src="../images/drive_elite-modified.png" alt="logo"></a>
        <nav class="navbar">
            <ul class="nav_links">
                <li><a href="citadines.php">Citadines</a></li>
                <li><a href="SUV.php">SUV</a></li>
                <li><a href="sportives.php">Sportives</a></li>
            </ul>
        </nav>
        <div class="cta">
                <a  href="profil.php"><button>Profil</button></a>
                <a  href="panier.php"><button>Panier</button></a>
                <a  href="contact.php"><button>Contactez-nous</button></a>
        </div>
    </header>



    <div class="container"  >
        <div class="message" >

            <?php



                    
                
                session_start();

                // Vérifie si l'utilisateur est connecté via les cookies
                if (!isset($_COOKIE["user_email"]) || !isset($_COOKIE["user_motdepasse"]) || !isset($_COOKIE["user_pseudonyme"])) {
                    // Rediriger l'utilisateur vers la page de connexion
                    header("Location: connexion.php");
                exit(); // Arrêter l'exécution du script après la redirection    
                }

            

                echo "<p> Merci de nous avoir contacté ".$_SESSION['prenom']."<br>Nous tenons à exprimer notre sincère gratitude pour avoir choisi DrivElite pour répondre à vos besoins.<br> C'est un réel plaisir de vous compter parmi nos clients, et nous sommes ravis d'avoir l'opportunité de vous offrir nos services.</p>" ;
                ?>
                <a  href="deconnexion.php"><button>Déconnexion</button></a>

        </div>

    </div>

    <footer>
    <p>Copyright Société DriveElite<br>
            Webmaster CY Tech
        </p>

    </footer>

    
</body>
</html>