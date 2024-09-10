<?php

// Définir le tableau des catégories
$categories = [
    'SUV' => 'SUV',
    'Citadines' => 'Citadines',
    'Sportives' => 'Sportives'
];

// Vérifier si la catégorie est définie dans l'URL
if (isset($_GET['cat']) && array_key_exists($_GET['cat'], $categories)) {
    $categorie = $_GET['cat'];
} else {
    // Si aucune catégorie spécifiée, afficher les Citadines par défaut
    $categorie = 'Citadines';
}

// Redirection vers la page produits.php avec le paramètre de catégorie
header("Location: produits.php?cat=$categorie");
exit;

