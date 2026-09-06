# Design system AYRU

## Tokens

| Token | Valeur | Rôle |
|---|---:|---|
| Violet | `#7C5CFC` | Accent principal |
| Lavande | `#C4B5FD` | Accent secondaire |
| Lilas | `#EDE9FE` | Fonds doux |
| Crème | `#FFF7E6` | Chaleur de marque |
| Marine | `#18143D` | Texte et CTA principal |
| Gris | `#6B7280` | Texte secondaire |

Titres : Poppins (600/700). Corps : Inter (variable 400-700). Les deux polices sont auto-hebergees dans `assets/fonts/`, sous-ensembles latin et latin-ext, en woff2, avec `font-display: swap` et prechargement des deux fichiers du premier rendu. Aucun appel a Google Fonts (conformite RGPD/CNIL).

La charte nomme la fonte de titre « Poppins Rounded », qui n'est pas distribuee librement. `"Poppins Rounded"` reste en tete de la pile CSS : si la fonte ronde sous licence est acquise, il suffira de deposer ses fichiers et d'ajouter la regle `@font-face` correspondante pour qu'elle prenne le relais sans autre modification.

## Mise en page

- conteneur maximal : 1440 px ;
- gouttières fluides : 18 à 52 px ;
- rayons : 12, 20 et 30 px ;
- mobile : deux colonnes produit ou rail horizontal ;
- desktop : quatre à cinq colonnes produit selon le contexte ;
- breakpoint structurel principal : 920 px ; breakpoint mobile étroit : 680 px.

## Images

Les héros utilisent une source desktop et mobile dédiée. Les illustrations d’état, les blocs NEB et les photos produits utilisent `object-fit: contain` afin de ne jamais couper un sujet ou un produit. Seul `neb-peeking` est volontairement partiel. Les cadres gardent le ratio des maquettes grâce à un fond neutre lorsque l’image source ne remplit pas toute la zone.

## Interaction

Focus violet visible, drawers fermables avec Échap, piégeage du focus, cibles tactiles d’au moins 44 px et respect de `prefers-reduced-motion`.
