document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.ajouter-panier').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            const formData = new FormData(document.getElementById('form-ajout-panier-' + productId));
            fetch('ajouter_panier.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (response.ok) {
                    alert('Produit ajouté au panier avec succès');
                } else {
                    alert('Une erreur s\'est produite lors de l\'ajout au panier');
                }
            })
            .catch(error => {
                console.error('Erreur lors de l\'ajout au panier:', error);
            });
        });
    });
});