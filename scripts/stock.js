document.addEventListener("DOMContentLoaded", function() {
    // Gestion de l'ajout au panier
    document.querySelectorAll(".ajouter-panier").forEach(function(button) {
        button.addEventListener("click", function() {
            const productId = button.getAttribute("data-id");
            const quantityElement = document.querySelector("#stock-" + productId);

            // Mise à jour de l'affichage du stock sans requête AJAX
            const currentStock = parseInt(quantityElement.textContent); // Récupérer la quantité actuelle de stock
            if (currentStock > 0) {
                quantityElement.textContent = currentStock - 1; // Décrémenter la quantité de stock affichée
            } else {
                // Afficher un message d'erreur si le stock est déjà à zéro
                console.error("Erreur : La quantité en stock est déjà à zéro.");
            }
        });
    });

    document.querySelectorAll(".afficher-stock").forEach(function(button) {
        button.addEventListener("click", function() {
            const productId = button.getAttribute("data-id");
            const stockElement = document.getElementById("stock-" + productId);

            stockElement.classList.toggle("hidden"); 
        });
    });
});
