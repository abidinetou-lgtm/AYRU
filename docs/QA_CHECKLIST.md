# Checklist QA

## Visuel

- [ ] comparaison accueil 390/430 px avec la maquette mobile ;
- [ ] comparaison accueil 1440/1536 px avec la maquette desktop ;
- [ ] contrôle collection, produit, mini-panier et panier aux deux formats ;
- [ ] aucun visage, sparkle, texte intégré ou sujet principal coupé involontairement ;
- [ ] images NEB en entier sauf `neb-peeking` et compositions explicitement débordantes ;
- [ ] cartes et boutons alignés, pas de débordement horizontal.

## Fonctionnel

- [x] produit simple, variable, promotion et rupture ;
- [ ] variation indisponible ;
- [x] ajout/suppression/quantité panier ;
- [ ] coupon valide et invalide ;
- [ ] création compte, connexion, mot de passe perdu ;
- [ ] checkout invité et connecté ;
- [ ] paiement test réussi, refusé et abandonné ;
- [ ] emails et confirmation ;
- [x] favoris visiteur et restauration de l’état côté serveur ;
- [ ] favoris connecté ;
- [x] Liste AYRU : connexion, création, ajout, propriétaire et partage public ;
- [ ] Liste AYRU : achat test et double changement de statut ;
- [x] recherche avec/sans résultat ;
- [x] 404 et états vides.

## Qualité

- [x] syntaxe PHP de tous les fichiers ;
- [ ] navigation complète au clavier et lecteur d’écran ;
- [ ] contraste WCAG 2.2 AA ;
- [x] réduction des animations ;
- [ ] Lighthouse mobile et Core Web Vitals ;
- [ ] validation HTML et console sans erreur ;
- [x] sitemap, robots, canonical, Open Graph et données structurées ;
- [x] aucune promesse, norme, note ou donnée légale non vérifiée dans les contenus livrés ; les données DÉMO et contenus à valider sont explicitement signalés.
