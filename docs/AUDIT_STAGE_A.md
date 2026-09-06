# AYRU — Audit de préparation (Stage A)

## Décision de référence

La référence visuelle prioritaire est la série de maquettes approuvées orientées bébé :

- `Maquette viseul home.png` et `Maquette visuel home mobile.png` ;
- `Page collection visuel desktop.png` et `Page collection visuel mobile.png` ;
- `Produit visuel desktop.png` et `Produit visuel mobile.png` ;
- `Panier visuel desktop.png` et `Panier visuel mobile.png` ;
- les deux wireframes servent à confirmer l'ordre et le rôle des blocs.

`Page visuel.png` est une piste antérieure plus généraliste (Labubu, gadgets et petits meubles). Elle n'est pas utilisée comme référence d'interface, car elle contredit le positionnement bébé/maman et les maquettes finales plus complètes.

## Produit compris

AYRU est une boutique éditoriale mobile-first destinée principalement aux futures et jeunes mères. Le cœur commercial couvre les essentiels bébé, la chambre, les peluches et les cadeaux ; “Tendances” apporte une couche social-first sans transformer le site en catalogue de dropshipping. NEB guide et rassure, mais ne remplace jamais le produit.

Le parcours principal est : découverte depuis mobile/réseaux sociaux → univers ou produit → ajout au panier ou à Ma Liste AYRU → checkout WooCommerce → confirmation et suivi. Ma Liste AYRU est une liste de naissance partageable distincte du panier et des favoris.

## Système visuel compris

- Palette : violet `#7C5CFC`, lavande `#C4B5FD`, lilas `#EDE9FE`, crème `#FFF7E6`, marine `#18143D`, gris `#6B7280`.
- Titres : Poppins Rounded d'après la brand sheet ; texte : Inter.
- Signature : sparkle à quatre pointes uniquement.
- Surfaces : fond crème très pâle, cartes claires, bordures lilas discrètes, ombres sobres.
- Formes : rayons généreux, contrôles pilules et grandes compositions éditoriales.
- Mobile : header compact, grille de deux produits, rangées défilables et navigation basse.
- Desktop : navigation horizontale, hero panoramique, grille dense et colonnes éditoriales.

## Architecture pages et composants

### Pages

Accueil, boutique, archives catégories, recherche, fiche produit, favoris, mini-panier, panier, checkout, confirmation, compte et commandes, suivi, Ma Liste AYRU (propriétaire et partagée), guides, guide individuel, FAQ, contact, livraison/retours, histoire, pages légales et 404.

### Composants réutilisables

Announcement bar, header desktop/mobile, recherche, navigation mobile basse, boutons, breadcrumbs, carte produit, rails produits, raccourcis catégories, tuiles univers, hero éditorial, encart NEB, état vide, drawer, accordéon, champs/formulaires, réassurance configurable, newsletter et footer.

## Architecture WordPress / WooCommerce

- `ayru` : thème sur mesure. Il porte les tokens, composants, templates et adaptations WooCommerce par hooks et surcharges ciblées.
- `ayru-core` : plugin fonctionnel durable. Il porte favoris, Liste AYRU, métadonnées de conformité produit et réglages de marque.
- WooCommerce : source de vérité pour produits, variantes, stock, prix, coupons, panier, commandes et comptes.
- Éditeur WordPress : guides et pages éditoriales.
- Aucun traitement de carte bancaire personnalisé. La passerelle sera un plugin WooCommerce officiel/maintenu choisi avant production.

## Contenus et données encore non vérifiés

- identité légale de l'entreprise, adresse, SIREN/SIRET, TVA et contact officiel ;
- zones, délais, prix et seuils réels de livraison ;
- politique de retour et adresse de retour ;
- passerelle de paiement ;
- catalogue, photos produit individuelles, variantes, stocks et prix réels ;
- fabricants, responsables UE, références, avertissements et justificatifs CE/GPSR ;
- liens sociaux, email support et consentement newsletter ;
- fichiers de police Poppins Rounded et Inter sous licence ;
- avis clients vérifiés ; les avis présents dans les maquettes ne doivent pas être repris comme faits.

Tout contenu correspondant reste configurable ou explicitement marqué “DÉMO — à remplacer”, jamais présenté comme une promesse réelle.

## Assets ambigus ou manquants

- Les photos produit existent seulement à l'intérieur des maquettes : aucune galerie produit autonome exploitable n'est fournie.
- Les icônes de catégories existent sur une planche, pas en fichiers vectoriels séparés ; l'interface utilisera des icônes SVG sobres côté code jusqu'à fourniture du kit final.
- `empty-box-illustration.png` peut servir aux commandes vides, mais ce rôle reste à confirmer.
- Les fichiers de `Ep 1 asset pour les video des reso` sont réservés au contenu social et ne sont pas utilisés comme images produits.
- Les slogans et garanties chiffrées visibles dans certaines maquettes (`+10 000 parents`, livraison offerte, retours 30 jours, expédition 24 h) ne sont pas vérifiés et sont exclus par défaut.

## Jalons vérifiables

1. Fondation : thème, plugin, assets organisés, tokens, header/footer et composants.
2. Commerce : archives, cartes, fiche produit, mini-panier, panier, recherche et états.
3. Accueil : reproduction responsive des maquettes approuvées.
4. Secondaires : compte, checkout, confirmation, suivi, aide, légal et 404.
5. Liste AYRU : création, gestion, partage, confidentialité et mise à jour après achat.
6. Guides/SEO : contenu, métadonnées, données structurées et maillage interne.
7. QA : analyse PHP, tests fonctionnels et captures desktop/mobile comparées.

