<!DOCTYPE html>
<html lang="en">

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

                // Vérifier si l'utilisateur est connecté via les cookies
                if (!isset($_COOKIE["user_email"]) || !isset($_COOKIE["user_motdepasse"]) || !isset($_COOKIE["user_pseudonyme"])) {
                    // Rediriger l'utilisateur vers la page de connexion
                    header("Location: connexion.php");
                exit(); // Arrêt de l'éxecution du script après la redireciton
                }

            

                echo "<p> Nous vous remercions sincèrement pour votre commande sur notre site.<br> Votre confiance est précieuse pour nous et nous sommes honorés de pouvoir vous servir.

                
                
                <br>N'hésitez pas à nous contacter si vous avez des questions ou des préoccupations concernant votre commande. 
                
                Encore une fois, merci pour votre confiance. Nous avons hâte de vous servir à nouveau dans un avenir proche.</p>" ;
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