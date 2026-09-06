# AYRU — Boutique WordPress / WooCommerce

Implémentation sur mesure de l’univers AYRU à partir des maquettes desktop/mobile et des assets fournis. Le projet contient un thème WordPress, un plugin fonctionnel durable et un environnement Docker local.

## Ce qui est implémenté

- accueil AYRU responsive fidèle à la direction bébé/NEB ;
- navigation desktop/mobile, recherche, mini-panier et navigation basse ;
- collections WooCommerce avec hero, sous-catégories, filtres configurables, tri et grille ;
- fiche produit, panier, checkout, compte, confirmation et états vides stylés ;
- favoris persistants (compte ou cookie visiteur) ;
- Ma Liste AYRU : création, ajout produit, lien partagé et comptabilisation après paiement confirmé ;
- informations produit de sécurité/traçabilité administrables ;
- Guides & Conseils avec archive, article et données structurées ;
- SEO technique de base, accessibilité clavier et réduction des animations ;
- documentation de déploiement et de préparation production.

## Démarrage local

Pré-requis : Docker Desktop en fonctionnement.

```powershell
Copy-Item .env.example .env
powershell -ExecutionPolicy Bypass -File .\scripts\bootstrap.ps1
```

Le site est ensuite disponible sur `http://localhost:8080`. Le script installe WooCommerce, active le thème/plugin et ajoute des fixtures marquées `DÉMO`. Elles servent uniquement au contrôle de l’interface et doivent être supprimées avant production.

Administration locale : `http://localhost:8080/wp-admin/`

- identifiant : `ayru_admin` ;
- mot de passe : `ayru_local_only_change_me`.

Ces identifiants sont exclusivement destinés à Docker en local et ne doivent jamais être réutilisés en production.

## Arborescence

```text
wordpress/wp-content/themes/ayru/       thème et assets livrés
wordpress/wp-content/plugins/ayru-core/ favoris, Liste AYRU, conformité, guides
docs/                                   décisions et procédures
scripts/bootstrap.ps1                   installation locale reproductible
AYRU/AYRU/                              originaux fournis, jamais modifiés
```

## Avant mise en production

Ne pas publier les fixtures de démonstration. Compléter le catalogue réel, les informations légales, les politiques, les contacts, les liens sociaux, les informations GPSR/sécurité et la passerelle de paiement. Voir [DEPLOYMENT.md](docs/DEPLOYMENT.md), [QA_CHECKLIST.md](docs/QA_CHECKLIST.md), [QA_REPORT.md](docs/QA_REPORT.md) et [QA_CORRECTIONS_SCREENSHOTS.md](docs/QA_CORRECTIONS_SCREENSHOTS.md).
