<?php
session_start();
require_once("getID.php");

// Vérifier si l'utilisateur est connecté via les cookies
if (!isset($_COOKIE["user_email"]) || !isset($_COOKIE["user_motdepasse"]) || !isset($_COOKIE["user_pseudonyme"])) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: connexion.php");
    exit(); //Arrête l'exécution du script après la redirection
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = obtenir_id_utilisateur_depuis_base_de_donnees($_COOKIE["user_email"], $_COOKIE["user_motdepasse"]);

// Fonction pour supprimer un élément du panier
if(isset($_GET['action']) && $_GET['action'] == 'supprimer' && isset($_GET['produit_id'])) {
    $produit_id = $_GET['produit_id'];

    // Supprime l'élément du panier de l'utilisateur
    try {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "DriveElite";

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Supprime un seul élément du panier
        $sqlDelete = "DELETE FROM PanierElements WHERE client = :client_id AND produit_id = :produit_id LIMIT 1";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':client_id', $user_id);
        $stmtDelete->bindParam(':produit_id', $produit_id);
        $stmtDelete->execute();

        // Réduire la quantité du produit dans la table Produits
        $sqlUpdateQuantity = "UPDATE Produits SET quantite = quantite + 1 WHERE id = :produit_id";
        $stmtUpdateQuantity = $conn->prepare($sqlUpdateQuantity);
        $stmtUpdateQuantity->bindParam(':produit_id', $produit_id);
        $stmtUpdateQuantity->execute();

        // Redirection vers la page panier
        header("Location: panier.php");
        exit();
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    $conn = null;
}

// Fonction pour vider le panier de l'utilisateur
if(isset($_GET['action']) && $_GET['action'] == 'vider_panier') {

    try {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "DriveElite";

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les éléments du panier de l'utilisateur
        $stmtPanier = $conn->prepare("SELECT produit_id, quantite FROM PanierElements WHERE client = :client_id");
        $stmtPanier->bindParam(':client_id', $user_id);
        $stmtPanier->execute();

        // Parcour les éléments du panier et mettre à jour la quantité dans la table Produits
        while ($rowPanier = $stmtPanier->fetch(PDO::FETCH_ASSOC)) {
            $produit_id = $rowPanier['produit_id'];
            $quantite_panier = $rowPanier['quantite'];

            // Mettre à jour la quantité du produit dans la table Produits
            $sqlUpdateProduit = "UPDATE Produits SET quantite = quantite + :quantite WHERE id = :produit_id";
            $stmtUpdateProduit = $conn->prepare($sqlUpdateProduit);
            $stmtUpdateProduit->bindParam(':quantite', $quantite_panier);
            $stmtUpdateProduit->bindParam(':produit_id', $produit_id);
            $stmtUpdateProduit->execute();
        }

        // Supprime tous les éléments du panier de l'utilisateur
        $sqlDeleteAll = "DELETE FROM PanierElements WHERE client = :client_id";
        $stmtDeleteAll = $conn->prepare($sqlDeleteAll);
        $stmtDeleteAll->bindParam(':client_id', $user_id);
        $stmtDeleteAll->execute();

        // Redirection vers la page panier
        header("Location: panier.php");
        exit();
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    $conn = null;
}

// Fonction pour commander
if(isset($_GET['action']) && $_GET['action'] == 'commander') {
    // Vide le panier de l'utilisateur
    try {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "DriveElite";

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Supprime tous les éléments du panier de l'utilisateur
        $sqlDeleteAll = "DELETE FROM PanierElements WHERE client = :client_id";
        $stmtDeleteAll = $conn->prepare($sqlDeleteAll);
        $stmtDeleteAll->bindParam(':client_id', $user_id);
        $stmtDeleteAll->execute();

        // Redirection vers la page de confirmation
        header("Location: confirmation.php");
        exit();
    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/icon.jpg" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../css/panier.css">
    <title>Panier</title>
</head>

<body>

<header>    
    <div class="logo">
        <a href="../index.php"> <img class="logo" src="../images/drive_elite-modified.png" alt="logo" ></a>
    </div>
    
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
    

<div class="container">

    <!-- MENU DE GAUCHE -->
    <div class="LeftMenu">
        <div class="menu" >
            <ul>
                <div class="links">
                    <li id="menu">Menu</li>
                </div>
                <div class="links">
                    <li id="SUV"><a href="SUV.php">Citadines</a></li>
                </div>
                <div class="links">
                    <li id="citadine"><a href="SUV.php">SUV</a></li>
                </div>
                <div class="links">
                    <li id="sportive"><a href="SUV.php">Sportive</a></li>
                </div>
                <div class="links">
                    <li id="contact"><a href=contact.php>Contactez-nous</a></li>
                </div>
            </ul>
        </div>
    </div>

    <!-- Affichage du contenu du panier -->
    <div class="body-form">
        <div class="photo-container">
            <h1>Votre Panier</h1>

            <?php
            // Affichage du contenu du panier
            if ($user_id !== null) {
                try {
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "DriveElite";

                    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Prépare la requête pour récupérer les éléments du panier de l'utilisateur
                    $stmt = $conn->prepare("SELECT produits.id AS produit_id, produits.nom AS nom, produits.prix AS prix FROM PanierElements INNER JOIN Produits ON PanierElements.produit_id = Produits.id WHERE PanierElements.client = :client_id");
                    $stmt->bindParam(':client_id', $user_id);
                    $stmt->execute();

                    // Affichage du contenu du panier
                    if ($stmt->rowCount() > 0) {
                        echo '<table border="1">';
                        echo '<thead>';
                        echo '<tr><th>Nom</th><th>Prix</th><th>Action</th></tr>';
                        echo '</thead>';
                        echo '<tbody>';
                        $total = 0; // Initialisation du total
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo '<td>' . $row['nom'] . '</td>';
                            echo '<td>' . $row['prix'] . ' euros</td>';
                            echo '<td><a href="panier.php?action=supprimer&produit_id=' . $row['produit_id'] . '">Supprimer</a></td>'; // Ajout du lien pour supprimer l'élément
                            echo '</tr>';
                            $total += $row['prix']; // Ajout du prix de la voiture au total
                        }
                        echo '<tr>';
                        echo '<td><strong>Total</strong></td>';
                        echo '<td colspan="2">' . $total . ' euros</td>'; // Affichage du total
                        echo '</tr>';
                        echo '</tbody>';
                        echo '</table>';

                        // Affichage du bouton "Commander" seulement si le panier n'est pas vide
                        echo '<div class="button">';
                        echo '<div class="catalogue">';
                        echo '<a href="SUV.php"><button>Retour au catalogue</button></a>';
                        echo '</div>';
                        echo '<a href="panier.php?action=vider_panier"><button>Vider le panier</button></a>';
                        echo '<a href="panier.php?action=commander"><button>Commander</button></a>';
                        echo '</div>';
                    } else {
                        echo '<p>Votre panier est vide.</p>';
                    }
                } catch(PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }

                $conn = null;
            } else {
                // Gére le cas où l'identifiant de l'utilisateur n'a pas pu être récupéré
                echo "Erreur : Impossible de récupérer l'identifiant de l'utilisateur.";
            }
            ?>
        </div>            
    </div>
</div>

<footer>
    <p>Information sur le site web </p>

</footer>

</body>
</html>
