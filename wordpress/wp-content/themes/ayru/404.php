<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<main id="main-content" class="error-page section-shell"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/neb/neb-404-lost.webp' ); ?>" alt="NEB cherche son chemin"><div><p class="eyebrow">Erreur 404</p><h1>Oups, cette page s’est envolée…</h1><p>NEB n’a pas retrouvé ce que vous cherchez. Revenons vers un univers tout doux.</p><a class="button button--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l’accueil <?php echo ayru_icon( 'arrow', 18 ); ?></a></div></main>
<?php get_footer(); ?>
