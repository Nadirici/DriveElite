<?php
session_start();

// Vérifier si l'utilisateur est connecté via les cookies
if (!isset($_COOKIE["user_email"]) || !isset($_COOKIE["user_motdepasse"]) || !isset($_COOKIE["user_pseudonyme"])) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: connexion.php");
    exit(); // Assurez-vous d'arrêter l'exécution du script après la redirection
}

// Établir une connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "DriveElite";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier si la catégorie est spécifiée dans l'URL, sinon afficher les SUV par défaut
    $categorie = isset($_GET['cat']) ? $_GET['cat'] : 'SUV';

// Exécuter une requête SQL pour récupérer les données des voitures de la catégorie spécifiée avec une quantité disponible
$sql = "SELECT * FROM Produits WHERE categorie_id = (SELECT id FROM Categories WHERE nom = :categorie) AND quantite > 0";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':categorie', $categorie);
$stmt->execute();


} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

$categories = [
    'SUV' => 'SUV',
    'Citadines' => 'Citadines',
    'Sportives' => 'Sportives'
];


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../css/exposition.css">
    <link rel="icon" href="../images/icon.JPG" type="image/x-icon">
    <title>Voitures <?php echo $categorie; ?></title>
</head>

<body>

<header>    
    <div class="logo">
        <a href="../index.php"> <img class="logo" src="../images/drive_elite-modified.png" alt="logo" ></a>
    </div>
    
    <nav class="navbar">
        <ul class="nav_links">
            <?php
            // Afficher les liens de menu en utilisant le tableau de catégories
            foreach ($categories as $key => $value) {
                echo "<li><a href='produits.php?cat=$key'>$value</a></li>";
            }
            ?>
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
                <?php
                // Afficher les liens de menu en utilisant le tableau de catégories
                foreach ($categories  as $key => $value) {
                    echo "<div class='links'>";
                    echo "<li id='$key'><a href='produits.php?cat=$key'>$value</a></li>";
                    echo "</div>";
                }
                ?>
                <div class="links">
                    <li id="contact"><a href=contact.php>Contactez-nous</a></li>
                </div>
            </ul>
        </div>
    </div>

    <!-- Affichage des voitures de la catégorie spécifiée -->
    <div class="body-form">
        <div class="photo-container">

            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Référence</th>
                        <th>Prix</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    // Affichage des voitures dans le tableau
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo '<td><img class="car-image" src="../images/' . $row['Fichier'] . '" alt="' . $row['nom'] . '"></td>';
                        echo '<td>' . $row['nom'] . '</td>';
                        echo '<td>' . $row['reference'] . '</td>';
                        echo '<td>' . $row['prix'] . ' euros</td>';
                        echo '<td>';
                        echo '<form id="form-ajout-panier-' . $row['id'] . '" class="form-ajout-panier" action="ajouter_panier.php" method="post">';
                        echo '<input type="hidden" name="voiture_id" value="' . $row['id'] . '">';
                        echo '<input type="hidden" name="voiture_nom" value="' . $row['nom'] . '">';
                        echo '<input type="hidden" name="voiture_prix" value="' . $row['prix'] . '">';
                        echo '<input type="button" class="ajouter-panier" data-id="' . $row['id'] . '" value="Ajouter au panier">';
                        echo '<br>';
                        echo '<input type="button" id="btn-stock-' . $row['id'] . '" class="afficher-stock" data-id="' . $row['id'] . '" value="Stock">';
                        echo '<br>';
                        echo '<span id="stock-' . $row['id'] . '" class="stock hidden">' . $row['quantite'] . '</span>'; 
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>

            </table>
            
        </div>            
    </div>
    
</div>

<footer>
<p>Copyright Société DriveElite<br>
            Webmaster CY Tech
        </p>
</footer>

<script src="../scripts/produit.js"></script>
<script src="../scripts/stock.js"></script>

</body>
</html>

