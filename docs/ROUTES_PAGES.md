# Routes et pages

| Route logique | Source | État |
|---|---|---|
| `/` | `front-page.php` | Implémenté |
| `/boutique/` | archive WooCommerce | Implémenté |
| `/categorie-produit/{slug}/` | `archive-product.php` | Implémenté |
| `/produit/{slug}/` | WooCommerce + styles/hooks AYRU | Implémenté |
| `/panier/`, `/commande/`, `/mon-compte/` | pages WooCommerce | Implémenté/stylé |
| `/favoris/` | shortcode AYRU Core | Implémenté |
| `/ma-liste/` | shortcode AYRU Core | Implémenté |
| `/liste-partagee/{token}/` | rewrite AYRU Core | Implémenté |
| `/suivi-commande/` | shortcode WooCommerce encadré AYRU | Implémenté |
| `/guides/`, `/guides/{slug}/` | type `guide` | Implémenté |
| `/faq/`, `/contact/`, `/livraison-retours/`, `/notre-histoire/` | pages WordPress | Template prêt, contenu requis |
| `/mentions-legales/`, `/cgv/`, `/confidentialite/`, `/cookies/` | pages WordPress | Template prêt, contenu légal requis |
| route inconnue | `404.php` | Implémenté |

WooCommerce crée ses pages système à l’installation. AYRU Core crée Favoris, Ma Liste et Suivi de commande à son activation.

