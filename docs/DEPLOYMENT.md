# Déploiement

1. Préparer un hébergement PHP 8.2+ avec HTTPS, base MariaDB/MySQL, sauvegardes et cron fiable.
2. Installer WordPress et WooCommerce propres.
3. Déployer `ayru` et `ayru-core`, puis les activer.
4. Configurer permaliens, devise, taxes, zones et méthodes d’expédition.
5. Importer le catalogue réel et supprimer tous les produits `— DÉMO`.
6. Ajouter les photos originales optimisées, informations fournisseur et avertissements vérifiés.
7. Créer/relire toutes les pages d’aide et légales avec les données réelles de l’entreprise.
8. Configurer SMTP transactionnel, consentement newsletter et anti-spam.
9. Installer/configurer la passerelle officielle en mode test puis production.
10. Exécuter toute la checklist QA, sauvegarder et seulement ensuite ouvrir l’indexation.

Ne jamais copier `.env` en production, committer des secrets, ni activer le paiement réel avant les commandes test et les webhooks.

