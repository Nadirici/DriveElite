<?php
session_start();
require_once("getID.php"); 


// Récupere l'ID de l'utilisateur connecté
$user_id = obtenir_id_utilisateur_depuis_base_de_donnees($_COOKIE["user_email"], $_COOKIE["user_motdepasse"]);

// Suppression du cookie d'authentification
setcookie("user_email", "", time() - 3600, "/");
setcookie("user_motdepasse", "", time() - 3600, "/");
setcookie("user_pseudonyme", "", time() - 3600, "/");



if ($user_id !== null) {
    // Établir une connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "DriveElite";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupérer les informations sur les produits dans le panier de l'utilisateur
        $sqlPanier = "SELECT produit_id, quantite FROM PanierElements WHERE client = :client_id";
        $stmtPanier = $conn->prepare($sqlPanier);
        $stmtPanier->bindParam(':client_id', $user_id);
        $stmtPanier->execute();

        // Réinsérer la quantité de chaque produit dans la table Produits
        while ($row = $stmtPanier->fetch(PDO::FETCH_ASSOC)) {
            $produit_id = $row['produit_id'];
            $quantite = $row['quantite'];

            // Exécuter une requête de mise à jour pour ajouter la quantité de produit
            $sqlUpdate = "UPDATE Produits SET quantite = quantite + :quantite WHERE id = :produit_id";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':quantite', $quantite);
            $stmtUpdate->bindParam(':produit_id', $produit_id);
            $stmtUpdate->execute();
        }

        // Supprimer tous les éléments du panier associés à cet utilisateur
        $sqlDelete = "DELETE FROM PanierElements WHERE client = :client_id";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->bindParam(':client_id', $user_id);
        $stmtDelete->execute();

    } catch(PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

    $conn = null;
}

// Destruction de la session
session_unset();
session_destroy();

// Redirection vers une page de confirmation de déconnexion ou autre
header("Location: fin.php");
exit();

