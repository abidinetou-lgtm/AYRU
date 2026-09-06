# Corrections QA — révision 1.2.0

Date : 6 septembre 2026
Méthode : rendu réel dans Chrome headless (Puppeteer) sur `http://localhost:8080`,
aux largeurs 390 px (mobile), 820 px (tablette) et 1440 px (desktop).
12 pages contrôlées à chaque passe : accueil, boutique, collection, fiche produit,
panier, compte, favoris, Ma Liste, guides, FAQ, contact, 404.

Les mesures sont automatisées (`documentElement.scrollWidth`, rectangles de tous
les éléments, taille des cibles tactiles, état de chargement des images).

---

## 1. Typographie de marque — défaut le plus grave

**Constat.** Aucune police n'était chargée. `main.css` déclarait
`"Poppins Rounded"` et `Inter`, mais rien ne les téléchargeait. Sur Windows le
rendu retombait sur Trebuchet MS, sur Android et iOS sur la police système.
La charte AYRU n'était donc respectée sur aucun appareil.

**Correction.** Poppins (600/700) et Inter (400–700 variable) sont désormais
auto-hébergées dans `assets/fonts/`, sous-ensembles `latin` et `latin-ext`
(le `latin-ext` est nécessaire au français : œ, ligatures, symboles).

- 6 fichiers `woff2`, 160 Ko au total ; 3 seulement sont téléchargés au premier
  rendu grâce au découpage par `unicode-range`.
- `font-display: swap` : le texte reste lisible pendant le chargement.
- Préchargement des deux fichiers du premier écran via `wp_head` (priorité 1).
- Aucun appel à Google Fonts : conformité RGPD/CNIL et une latence en moins.

`"Poppins Rounded"` reste en tête de la pile. Si la fonte ronde sous licence est
achetée un jour, il suffira de l'ajouter et elle prendra le relais.

## 2. Débordement horizontal de l'accueil sur mobile

**Constat.** À 390 px, `body.scrollWidth` valait 408 px : la page glissait
latéralement de 18 px.

**Cause.** `.community` cumulait `width: 100%` (hérité de `.section-shell`) et
`margin-inline: 18px`.

**Correction.** `width: auto` sur ce bloc. Mesure après correction :
`body.scrollWidth = 390`, aucun panoramique, zéro élément non contenu — y
compris en retirant le filet `overflow-x: hidden`, ce qui garantit le résultat
sur iOS Safari où ce filet est peu fiable.

> `documentElement.scrollWidth` affiche encore 509 px. C'est une particularité
> de mesure de Chrome, qui additionne la largeur interne des conteneurs à
> défilement horizontal (rails produits, raccourcis). Ces conteneurs sont de
> vrais `overflow-x: auto` : rien ne fuit vers la page.

## 3. Hero mobile reconstruit

**Constat.** Le visuel servait de fond plein cadre derrière le texte. Le visage
de NEB tombait exactement sous le bloc de titre, donc invisible, et le
paragraphe d'accroche reposait sur la zone chargée de l'image.

**Correction.** Empilement fidèle à la maquette : discours en haut sur le champ
lilas, puis le visuel en bande basse (`min(80vw, 370px)`, cadrage `center 40%`)
où NEB apparaît en entier.

Le titre passe de quatre lignes avec orphelins à deux lignes équilibrées.
`text-wrap: balance` est réservé au mobile ; sur desktop il dégradait le titre,
qui utilise `text-wrap: pretty`.

## 4. Pied de page — liens masqués

**Constat.** Deux règles supprimaient des colonnes entières pour « tasser » la
grille : « À propos & légal » disparaissait sous 1180 px, « Aide » sous 680 px.
Perte de navigation, perte de maillage interne, et accès aux mentions légales et
aux CGV coupé sur mobile — ce qui est un risque de conformité, pas seulement un
défaut de design.

**Correction.** Règles supprimées. La grille se réorganise réellement :
quatre colonnes en tablette, deux en mobile, toutes les rubriques présentes.
Les lignes de liens passent à 40 px de haut sur mobile.

Le NEB décoratif chevauchait la mention « France (EUR) ». L'espace réservé est
maintenant calculé sur sa largeur réelle.

## 5. Cibles tactiles

Passage de 17 à 29 cibles sous 40 px par page, à 0 à 2 selon les pages.

| Élément | Avant | Après |
|---|---|---|
| Bouton favori | 34 × 34 | 40 × 40 (zone tactile 46 × 46) |
| Liens du pied de page | 167 × 19 | pleine largeur × 40 |
| Liens « Voir tout » | 75 × 19 | 75 × 32 |
| Lien logo | 100 × 30 | 100 × 44 |

Les deux cibles restantes signalées sont conformes (24 px minimum, WCAG 2.2
critère 2.5.8). Le `select` de tri mesuré à 1 × 1 px est le champ natif masqué
derrière le composant AYRU accessible : ce n'est pas la cible réelle.

## 6. Cohérence du système de design

- Prix soldés : le rouge `#db334f` est repassé au violet de marque. Ce rouge
  reste réservé aux erreurs de formulaire.
- Badge « Promo ! » de la fiche produit : c'était la pastille vert olive par
  défaut de WooCommerce. Elle reprend la pastille blanche des cartes produit.
- Prix soldé : suppression du soulignement `<ins>`, prix barré atténué et réduit.
- Tuiles « Nos univers » : la flèche chevauchait les libellés sur deux lignes.
  Elle est replacée dans une pastille ronde en haut à droite, comme la maquette.
- Sous-catégories des collections : les cinq pastilles portaient la même
  étincelle. Nouvelle fonction `ayru_category_icon()`, alignée sur les
  raccourcis de l'accueil, repli neutre sur l'étincelle.
- Boutons de soumission WooCommerce : connexion, inscription et coupon
  utilisaient le fond pâle des actions secondaires et semblaient désactivés.
  Ils passent en bouton principal. La spécificité des sélecteurs a été alignée
  sur la règle générique, sinon elle ne l'emportait pas.
- Encre de marque corrigée : `#18143d` → `#1b143d`, valeur exacte de la charte.
- Consentement newsletter : en `flex`, le lien se détachait de la phrase.
  Retour au flux inline, case en position absolue.

## 7. Faux positif écarté

Une première passe signalait cinq illustrations NEB manquantes. Vérification
faite, les fichiers `webp` sont valides et servis en 200 : elles sont en
`loading="lazy"` et n'avaient pas eu le temps de se charger pendant la capture.
Le protocole de capture force désormais le chargement et parcourt la page.
Aucune image cassée sur les 12 pages.

---

## Reste à faire — hors périmètre technique

Ces points ne sont pas des bugs : ce sont des contenus à fournir.

- Photos produit réelles. Les cartes affichent « Photo produit à fournir ».
- Bloc de réassurance : « modalités à renseigner », « solution à connecter »,
  « politique à valider », « contact à renseigner ». Textes honnêtes, mais
  visibles en production.
- Liens sociaux : `ayru_instagram_url`, `ayru_tiktok_url`, `ayru_pinterest_url`
  ne sont pas renseignés dans le personnalisateur, la rangée reste donc vide.
- Contenus légaux : mentions, CGV, confidentialité, cookies.
- Passerelle de paiement : l'extension officielle `woocommerce-gateway-stripe`
  est installee et activee. Il ne manque que les cles API (mode test puis
  production) a saisir dans WooCommerce > Reglages > Paiements > Stripe.
- Suppression des fixtures « DÉMO ».

---

## Consentement et mesure d'audience (revision 1.3.0)

Bandeau de consentement conforme aux regles CNIL : refus aussi simple que
l'acceptation, aucun pre-cochage, choix conserve six mois, reouverture possible
depuis la page Cookies via un element portant `data-consent-reset`.

Les traceurs (GA4, Pixel Meta, Pixel TikTok) sont declares dans le
personnalisateur. Ils sont emis en `type="text/plain"` avec l'attribut
`data-ayru-consent` : le navigateur ne les execute pas tant que l'internaute
n'a pas accepte. Sans identifiant renseigne, aucun script n'est emis.

Verifie au rendu : bandeau affiche au premier passage, masque apres choix,
choix conserve apres rechargement, boutons non recouverts par la barre de
navigation basse.
