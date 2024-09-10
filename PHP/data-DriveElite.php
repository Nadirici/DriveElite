<?php

function insertData() {
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "DriveElite";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Insertion des catégories s'ils n'existent pas déjà
        $categories = ['SUV', 'Citadines', 'Sportives'];

        foreach ($categories as $categorie) {
            $sql_check = "SELECT COUNT(*) FROM Categories WHERE nom = '$categorie'";
            $stmt_check = $conn->query($sql_check);
            $count = $stmt_check->fetchColumn();

            if ($count == 0) {
                $sql_insert_cat = "INSERT INTO Categories (nom) VALUES ('$categorie')";
                $conn->exec($sql_insert_cat);
            }
        }


        $categories = [
            'SUV' => 'SUV',
            'Citadines' => 'Citadines',
            'Sportives' => 'Sportives',

        ];

        $_SESSION["categories"]=$categories;

        // Données des voitures
        $voitures = [
            ['photo' => 'Jeep.jpg', 'nom' => 'Jeep', 'reference' => 'REF001', 'prix' => 35000,'quantite'=> 5, 'categorie' => 'SUV',],
            ['photo' => 'Cypbertruck.jpg', 'nom' => 'Cybertruck 2.3', 'reference' => 'REF002', 'prix' => 75000,'quantite'=> 5, 'categorie' => 'SUV'],
            ['photo' => 'RangeRover.jpg', 'nom' => 'Range Rover', 'reference' => 'REF003', 'prix' => 90000,'quantite'=> 5, 'categorie' => 'SUV'],
            ['photo' => 'LandCruiser.jpg', 'nom' => 'Toyota Land Cruiser', 'reference' => 'REF004', 'prix' => 85000,'quantite'=> 5, 'categorie' => 'SUV'],
            ['photo' => 'Tiguan.jpg', 'nom' => 'Volkswagen Tiguan', 'reference' => 'REF005', 'prix' => 42000,'quantite'=> 5, 'categorie' => 'SUV'],

            ['photo' => 'Citroen.jpg', 'nom' => 'Citroen C3', 'reference' => 'REF006', 'prix' => 20000,'quantite'=> 5, 'categorie' => 'Citadines'],
            ['photo' => 'Volkswagen.jpg', 'nom' => 'Volkswagen Golf', 'reference' => 'REF007', 'prix' => 25000,'quantite'=> 5, 'categorie' => 'Citadines'],
            ['photo' => 'Renault.jpg', 'nom' => 'Renault Clio', 'reference' => 'REF008', 'prix' => 30000,'quantite'=> 5, 'categorie' => 'Citadines'],
            ['photo' => 'Peugeot.jpg', 'nom' => 'Peugeot 208', 'reference' => 'REF009', 'prix' => 35000,'quantite'=> 5, 'categorie' => 'Citadines'],
            ['photo' => 'ToyotaYaris.jpg', 'nom' => 'Toyota Yaris', 'reference' => 'REF010', 'prix' => 28000,'quantite'=> 5, 'categorie' => 'Citadines'],

            ['photo' => 'Ferrari.jpg', 'nom' => 'Ferrari 458 Italia', 'reference' => 'REF011', 'prix' => 300000,'quantite'=>5, 'categorie' => 'Sportives'],
            ['photo' => 'Lamborghini.jpg', 'nom' => 'Lamborghini Huracán', 'reference' => 'REF012', 'prix' => 350000,'quantite'=>5, 'categorie' => 'Sportives'],
            ['photo' => 'Porsche.jpg', 'nom' => 'Porsche 911', 'reference' => 'REF013', 'prix' => 250000,'quantite'=>5, 'categorie' => 'Sportives'],
            ['photo' => 'BMW_M3.jpg', 'nom' => 'BMW M3', 'reference' => 'REF014', 'prix' => 100000,'quantite'=>5, 'categorie' => 'Sportives'],
            ['photo' => 'AudiR8.jpg', 'nom' => 'Audi R8', 'reference' => 'REF015', 'prix' => 200000,'quantite'=>5, 'categorie' => 'Sportives'],
        ];

        $_SESSION["produits"] = $voitures;

        foreach ($voitures as $voiture) {
            $reference = $voiture['reference'];

            // Vérification si la voiture existe déjà dans la table Produits
            $sql_check = "SELECT COUNT(*) FROM Produits WHERE reference = '$reference'";
            $stmt_check = $conn->query($sql_check);
            $count = $stmt_check->fetchColumn();

            // Si la voiture n'existe pas, on l'insère
            if ($count == 0) {
                $photo = $voiture['photo'];
                $nom = $voiture['nom'];
                $reference = $voiture['reference'];
                $prix = $voiture['prix'];
                $categorie = $voiture['categorie'];
                $quantite=$voiture['quantite'];

                // Insertion des données dans la table Produits
                $sql = "INSERT INTO Produits (nom,Fichier, reference, prix,quantite, categorie_id) VALUES ('$nom','$photo', '$reference', $prix,$quantite, (SELECT id FROM Categories WHERE nom = '$categorie'))";
                $conn->exec($sql);
            }
        }

    //    echo "Données insérées avec succès";

    } catch(PDOException $e) {
        // echo "Erreur : " . $e->getMessage();
    }

    $conn = null;
}
