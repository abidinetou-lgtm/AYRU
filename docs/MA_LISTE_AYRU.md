# Ma Liste AYRU

## Comportement livré

- l’utilisateur se connecte et crée une liste nommée ;
- depuis une fiche produit, il ajoute le produit à sa liste ;
- il copie un lien contenant un jeton aléatoire ;
- le proche ouvre la liste, voit les quantités restantes et achète via WooCommerce ;
- la ligne de commande conserve l’identifiant de la liste ;
- la quantité achetée est mise à jour uniquement quand la commande est payée/en traitement ou terminée ;
- un marqueur sur la commande empêche le double comptage lors des changements de statut.

Le simple ajout au panier ne réserve rien. Cela évite qu’un panier abandonné bloque un cadeau.

## Extensions futures possibles

Plusieurs listes par compte, suppression/quantité dans l’interface propriétaire, réglage public/privé explicite, messages des proches, notifications et import de liste. Ces extensions nécessitent des décisions métier et des tests complémentaires.

