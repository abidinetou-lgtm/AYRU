# Rapport QA de l’installation locale

Date : 5 septembre 2026  
Environnement : WordPress 7.1, WooCommerce 11.1.0 sous Docker, France, EUR, interface `fr_FR`.

## Contrôles réussis

- syntaxe PHP validée sur les 20 fichiers et syntaxe JavaScript validée ;
- routes principales en HTTP 200 : accueil, boutique, catégorie, produit, panier, compte, favoris, Ma Liste, suivi, contact, FAQ, livraison/retours, histoire, légal, guides ;
- vraie 404 sur une route inconnue ;
- 27 liens cliquables de l’accueil contrôlés sans erreur ;
- ajout d’un produit, passage de la quantité à 2, suppression et retour à l’état panier vide ;
- checkout accessible avec une session contenant un produit ;
- produit simple, prix promotionnel, rupture de stock en français et formulaire de variations ;
- favori visiteur ajouté par AJAX, cookie persistant et état `aria-pressed` restauré au rechargement ;
- connexion locale, création d’une Liste AYRU, ajout d’un produit et accès public au lien partagé ;
- validations contact/newsletter et nonces anti-CSRF ;
- recherche avec et sans résultat ;
- sitemap, robots, canonical, Open Graph, schémas accueil/produit et `noindex` des URLs filtrées ;
- 37 images WebP optimisées pour un total d’environ 2,46 Mo ; 12 ressources intégrées aux pages et 5 références CSS contrôlées, aucune manquante ;
- images de contenu en `object-fit: contain` afin d’éviter les sujets coupés. Les heroes utilisent leurs exports desktop/mobile dédiés ; `neb-peeking` est le seul débordement décoratif volontaire.

## Contrôles restant obligatoires avant production

- comparaison visuelle par captures aux largeurs 390/430/768/1440/1536 px : le navigateur intégré n’était pas disponible dans cette session ; aucune validation « pixel perfect » n’est donc prétendue ;
- parcours clavier complet, lecteur d’écran, audit de contraste automatisé et Lighthouse/Core Web Vitals ;
- coupon valide/invalide, création de compte, mot de passe perdu et favoris connecté ;
- variation volontairement indisponible ;
- paiement test accepté/refusé/abandonné, emails transactionnels et webhooks après choix d’une passerelle ;
- achat depuis une Liste AYRU jusqu’au statut payé, puis vérification d’idempotence sur les changements de statut ;
- tests des taxes, zones, tarifs et délais d’expédition une fois les règles commerciales fournies ;
- test SMTP réel, délivrabilité et intégration éventuelle à un prestataire newsletter.

## Blocages de contenu

Les photos et données du catalogue réel, l’identité juridique, les politiques de livraison/retours, la fiscalité, le fournisseur de paiement, l’adresse de support, les liens sociaux et les informations GPSR/sécurité n’étaient pas présents dans les sources. Le site affiche donc des fixtures explicitement marquées `DÉMO` et des textes « à compléter », sans inventer de promesses commerciales.
