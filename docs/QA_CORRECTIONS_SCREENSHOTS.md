# Corrections issues du dossier `probleme`

Date : 5 septembre 2026  
Révision : thème/plugin AYRU 1.1.6

## Accueil et footer

- NEB du footer ramené entièrement dans le cadre, réduit et placé dans une zone réservée sous les colonnes ; il est masqué sur mobile.
- Suppression du point isolé après le lien de confidentialité de la newsletter.
- Blocs « Guides & Conseils » et « Ma Liste » reconstruits en deux rangées : texte complet puis illustration. Aucun texte ne repose désormais sur l’image.
- Bloc « Sélection sommeil » reconstruit en grille texte/image sur desktop et en pile verticale sur mobile.
- Image sommeil affichée en `cover`, sans marge interne, pour toucher proprement les bords arrondis du bloc sans effet d’illustration flottante.
- Images « Ma Liste AYRU » et « Guides & Conseils » également passées en `cover`, sans marge interne, avec ancrage bas sur mobile.

## Produits et catalogue

- Produits similaires/upsells/cross-sells forcés dans une grille de quatre colonnes desktop, trois tablette et deux mobile.
- Correction du balisage WooCommerce : les cartes placées dans une liste sont désormais de vrais éléments `<li>`.
- Bouton « Ajouter à Ma Liste AYRU » limité à une largeur raisonnable et recoloré dans le système AYRU.
- Sélecteurs de tri et de variations remplacés progressivement par un composant AYRU accessible : menu arrondi, options survolées en lilas, clavier, Échap et synchronisation WooCommerce.
- Formulaire de variations réaligné, sans contenu repoussé hors de la fiche.
- Notification d’ajout au panier réduite et transformée en carte compacte avec action claire.

## Compte et inscription

- Activation de l’inscription WooCommerce depuis « Mon compte ».
- Formulaire d’inscription avec email et choix direct du mot de passe.
- Tous les CTA de connexion côté boutique dirigent vers `/mon-compte/`, jamais vers l’écran WordPress brut.
- Présentation connexion/inscription en deux cartes desktop, une colonne mobile.
- Textes de confidentialité traduits et lien relié à `/confidentialite/`.

## Panier et commande

- Suppression de la largeur `prose` de 900 px qui comprimait les écrans système.
- Checkout organisé en deux colonnes stables : informations client à gauche, commande/paiement à droite.
- Facturation et informations complémentaires empilées dans la colonne principale.
- Récapitulatif placé en haut, sans vide vertical artificiel.
- Coupon, paiement, messages et champs remis aux proportions AYRU.
- Texte de confidentialité du checkout traduit et CGV reliées à `/cgv/`.
- Erreurs de validation checkout affichées en carte verticale lisible ; les champs obligatoires invalides sont maintenant clairement signalés.

## Pages et mobile

- Hero cloud/soft-cloud ajouté aux pages secondaires ; illustrations contextuelles pour compte, favoris, suivi, contact, livraison et panier.
- Fonds cloud ajoutés aux catégories génériques et au hub Guides.
- Navigation desktop explicitement masquée sous 920 px : le hamburger et le menu normal ne peuvent plus apparaître ensemble.
- Largeur globale verrouillée au viewport et débordement horizontal neutralisé.
- Défilement latéral accidentel neutralisé au niveau de la page ; les rangées réellement horizontales restent défilables à l’intérieur de leur propre zone.
- Drawer mobile limité à `viewport - 24 px`, bouton de fermeture maintenu dans son cadre.
- Barre de navigation basse mobile verrouillée à la largeur du viewport, avec colonnes `minmax(0, 1fr)` et icônes/liens incapables de provoquer un étirement horizontal.
- Checkout, compte, produits liés, notices et formulaire coupon disposent de règles mobiles dédiées.

## Validation disponible

- 21 captures utilisateur examinées.
- Syntaxe PHP et JavaScript contrôlée.
- Présence des formulaires connexion/inscription et des pages françaises contrôlée par rendu HTTP.
- Le navigateur visuel intégré n’était pas disponible ; une nouvelle comparaison par captures réelles reste donc recommandée après actualisation forcée du navigateur.
