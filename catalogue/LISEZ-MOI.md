# Remplir le catalogue AYRU

Ouvre `MODELE-catalogue.csv` dans Excel, Google Sheets ou LibreOffice.
Une ligne = un produit. Garde la ligne d'en-tête telle quelle.

## Les colonnes

| Colonne | Obligatoire | Détail |
|---|---|---|
| `nom` | oui | Le titre affiché. |
| `slug` | non | L'adresse de la fiche. Laissé vide, il est déduit du nom. **Sert de clé** : ne le change plus après le premier import, sinon un doublon sera créé. |
| `categorie` | oui | Un seul slug parmi : `maison-cosy`, `deco-murale`, `pyjamas-detente`, `papeterie`, `cadeaux`, `tendances`. |
| `prix` | oui | Point comme séparateur décimal : `39.90`, jamais `39,90`. |
| `prix_promo` | non | À ne remplir que si la promotion est réelle. |
| `stock` | oui | Mets une quantité prudente, 5 à 10. À zéro, la fiche passe en rupture. |
| `description_courte` | oui | Une à deux phrases, affichées au-dessus du bouton d'achat. |
| `description` | oui | Le texte long de la fiche. |
| `image` | oui | URL ou chemin local. Sans elle, la carte affiche « Photo produit à fournir ». |
| `galerie` | non | Plusieurs URL séparées par `|`. |
| `variante_nom` | non | `Couleur`, `Taille`… |
| `variantes` | non | Options séparées par `|`, par exemple `Lilas|Crème`. Rempli, le produit devient variable. |
| `sku` | non | Ta référence interne. |
| `fournisseur`, `sku_fournisseur`, `prix_achat`, `url_fournisseur` | recommandé | **Internes, jamais affichés sur le site.** Servent à ta marge et à ton réassort. |
| `fabricant`, `responsable_ue`, `reference`, `pays_origine`, `matieres`, `dimensions`, `ce`, `age_avertissement`, `securite` | recommandé | **Publics**, affichés dans l'onglet « Sécurité & traçabilité ». Ne remplis que des informations vérifiées auprès du fournisseur. |

## Lancer l'import

```
docker compose run --rm wpcli eval-file wp-content/themes/ayru/import.php catalogue.csv
```

Le script est rejouable : relancé, il met à jour les produits existants au lieu
de les dupliquer. Il repère un produit par son `slug`.

## Deux pièges à éviter

**L'enregistrement Excel.** Choisis « CSV UTF-8 (délimité par des virgules) ».
Sinon les accents deviennent illisibles.

**Les virgules dans le texte.** Si une description contient une virgule,
entoure-la de guillemets doubles. Excel le fait automatiquement.
