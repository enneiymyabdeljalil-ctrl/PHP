function gmToast(message, type = 'success') {
    const toast = document.getElementById('gm-toast');
    if (!toast) {
        alert(message);
        return;
    }
    toast.className = 'alert alert-' + (type === 'success' ? 'success' : 'danger');
    toast.textContent = message;
    toast.classList.remove('d-none');
    setTimeout(() => toast.classList.add('d-none'), 2400);
}

function updateCartBadge(count, total) {
    const badge = document.getElementById('cart-badge');
    const totalSpan = document.getElementById('cart-total');
    if (badge && count !== undefined) {
        badge.textContent = count;
        badge.classList.toggle('d-none', parseInt(count, 10) <= 0);
    }
    if (totalSpan && total !== undefined) {
        totalSpan.textContent = total + ' DH';
    }
}

document.querySelectorAll('.btn-ajouter-panier').forEach((button) => {
    button.addEventListener('click', () => {
        const id = button.dataset.id;
        if (!id) return;

        const formData = new FormData();
        formData.append('id_produit', id);

        fetch('ajoute_panier.php', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status !== 'success') {
                    gmToast(data.message || "Erreur lors de l'ajout au panier.", 'danger');
                    return;
                }
                updateCartBadge(data.total_quantite, data.total_prix);
                gmToast('Produit ajouté au panier.');
            })
            .catch(() => gmToast('Erreur réseau.', 'danger'));
    });
});

document.querySelectorAll('.cart-form').forEach((form) => {
    form.addEventListener('submit', (event) => {
        event.preventDefault();
        fetch('./ajoute_panier.php', {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.status !== 'success') {
                    gmToast(data.message || "Erreur lors de l'ajout au panier.", 'danger');
                    return;
                }
                updateCartBadge(data.total_quantite, data.total_prix);
                gmToast('Produit ajouté au panier.');
            })
            .catch(() => gmToast('Erreur réseau.', 'danger'));
    });
});

document.querySelectorAll('.js-favoris').forEach((button) => {
    button.addEventListener('click', () => {
        const id = button.dataset.id;
        if (!id) return;

        const formData = new FormData();
        formData.append('id_produit', id);

        fetch('add_favoris.php', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then((response) => response.json())
            .then((data) => {
                if (!data.success) {
                    gmToast(data.message || 'Erreur favoris.', 'danger');
                    return;
                }
                const icon = button.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fa-solid', data.active);
                    icon.classList.toggle('fa-regular', !data.active);
                }
                gmToast(data.message);
            })
            .catch(() => gmToast('Erreur réseau.', 'danger'));
    });
});
