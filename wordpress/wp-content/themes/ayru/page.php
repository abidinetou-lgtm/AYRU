<?php
/**
 * AYRU page template.
 *
 * @package AYRU
 */
defined( 'ABSPATH' ) || exit;
get_header();

while ( have_posts() ) :
	the_post();
	$slug = get_post_field( 'post_name', get_the_ID() );
	$heroes = array(
		'mon-compte'        => array( 'Votre espace', 'Connectez-vous ou créez votre compte AYRU.', 'neb-account-welcome.webp' ),
		'favoris'           => array( 'Vos coups de cœur', 'Retrouvez facilement les produits que vous aimez.', 'neb-favorites-heart.webp' ),
		'ma-liste'          => array( 'Préparer en douceur', 'Créez et partagez votre Liste AYRU avec vos proches.', 'neb-wish-list.webp' ),
		'suivi-commande'    => array( 'Votre colis', 'Consultez l’avancement réel de votre commande.', 'neb-order-tracking.webp' ),
		'contact'           => array( 'Nous sommes là', 'Une question ? Écrivez à l’équipe AYRU.', 'neb-support.webp' ),
		'faq'               => array( 'Besoin d’aide ?', 'Les réponses utiles, réunies au même endroit.', 'neb-support.webp' ),
		'livraison-retours' => array( 'À vos côtés', 'Les modalités appliquées par la boutique seront précisées ici.', 'NEB_05_colis.webp' ),
		'panier'            => array( 'Votre sélection', 'Vérifiez vos articles avant de poursuivre.', 'neb-shopping-cart.webp' ),
		'commande'          => array( 'Dernière étape', 'Vos informations et le récapitulatif, clairement présentés.', '' ),
	);
	$hero = $heroes[ $slug ] ?? array( 'L’univers AYRU', '', '' );
	$is_wide = ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) || in_array( $slug, array( 'favoris', 'ma-liste', 'suivi-commande', 'contact' ), true );
	$show_hero = 'ma-liste' !== $slug;
	?>
	<main id="main-content" class="content-page section-shell page-<?php echo esc_attr( $slug ); ?>">
		<article <?php post_class(); ?>>
			<?php if ( $show_hero ) : ?><header class="page-hero<?php echo $hero[2] ? ' page-hero--has-art' : ''; ?>">
				<div class="page-hero__copy"><p class="eyebrow"><?php echo esc_html( $hero[0] ); ?></p><h1><?php the_title(); ?></h1><?php if ( $hero[1] ) : ?><p><?php echo esc_html( $hero[1] ); ?></p><?php endif; ?></div>
				<?php if ( $hero[2] ) : ?><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/neb/' . $hero[2] ); ?>" alt="" loading="eager"><?php endif; ?>
			</header><?php endif; ?>
			<div class="page-content<?php echo $is_wide ? ' page-content--wide' : ' prose'; ?>"><?php the_content(); ?></div>
		</article>
	</main>
	<?php
endwhile;
get_footer();
