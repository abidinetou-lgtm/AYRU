# Architecture

## Séparation des responsabilités

Le thème `ayru` contient exclusivement la présentation, les templates, les hooks WooCommerce d’affichage et les assets de marque. Le plugin `ayru-core` contient les données qui doivent survivre à un changement de thème : favoris, listes, guides et champs de conformité.

WooCommerce reste seul responsable des produits, variantes, stocks, prix, coupons, panier, taxes, commandes, comptes et emails. Aucun fichier du cœur WordPress ou WooCommerce n’est modifié.

## Flux principal

1. WordPress résout la page et le thème compose l’interface.
2. Les cartes interrogent `wc_get_products`; aucune copie statique du catalogue n’est maintenue.
3. Le panier et le checkout utilisent les APIs et templates WooCommerce.
4. Les favoris appellent un endpoint AJAX protégé par nonce et sont enregistrés en `user_meta` ou cookie HTTP-only.
5. Une Liste AYRU est un contenu privé de l’interface (`ayru_list`, non publiquement interrogeable) accessible au propriétaire ou via un jeton aléatoire de partage.
6. L’identifiant de liste est attaché à la ligne de panier puis à la ligne de commande. La quantité “achetée” n’est mise à jour qu’au statut `processing` ou `completed`, avec protection anti double comptage.

## Dépendances

- WordPress ;
- WooCommerce ;
- AYRU Core (livré) ;
- une passerelle WooCommerce officielle/maintenue à choisir avant production.

Aucun framework JavaScript, constructeur de pages ou plugin de favoris n’est requis.

