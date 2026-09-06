<?php
defined( 'ABSPATH' ) || exit;
get_header();
$theme_uri = get_template_directory_uri();
$is_cosy   = is_product_category( 'maison-cosy' );
?>
<main id="main-content" class="shop-layout section-shell">
	<?php woocommerce_breadcrumb(); ?>
	<section class="category-hero <?php echo $is_cosy ? 'category-hero--cosy' : ''; ?>">
		<div><p class="eyebrow"><?php echo is_shop() ? 'La boutique AYRU' : 'Un univers AYRU'; ?></p><h1><?php woocommerce_page_title(); ?></h1><?php do_action( 'woocommerce_archive_description' ); ?></div>
	</section>
	<div class="subcategories" aria-label="Sous-catégories">
		<?php
		$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => is_product_category() ? get_queried_object_id() : 0, 'number' => 8 ) );
		if ( ! is_wp_error( $terms ) ) : foreach ( $terms as $term ) :
			// Meme correspondance d'icones que les raccourcis de l'accueil, pour eviter
			// une rangee de pastilles toutes identiques. Repli neutre sur l'etincelle AYRU.
			$icon = ayru_category_icon( $term->slug );
			?><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><span class="shortcut-icon"><?php echo ayru_icon( $icon, 25 ); ?></span><?php echo esc_html( $term->name ); ?></a><?php endforeach; endif;
		?>
	</div>
	<div class="shop-toolbar"><button class="button button--secondary filter-button" type="button" data-open="filter-drawer"><?php echo ayru_icon( 'filter', 19 ); ?> Filtres</button><div class="shop-result"><?php woocommerce_result_count(); ?></div><div><?php woocommerce_catalog_ordering(); ?></div></div>
	<div class="shop-content">
		<aside class="shop-filters desktop-only" aria-label="Filtres produits"><h2>Filtres</h2><?php if ( is_active_sidebar( 'shop-filters' ) ) { dynamic_sidebar( 'shop-filters' ); } else { ?><p>Ajoutez ici les blocs de filtre WooCommerce depuis Apparence → Widgets.</p><?php } ?></aside>
		<section class="shop-products" aria-label="Produits"><?php if ( woocommerce_product_loop() ) { woocommerce_product_loop_start(); if ( wc_get_loop_prop( 'total' ) ) { while ( have_posts() ) { the_post(); do_action( 'woocommerce_shop_loop' ); wc_get_template_part( 'content', 'product' ); } } woocommerce_product_loop_end(); do_action( 'woocommerce_after_shop_loop' ); } else { do_action( 'woocommerce_no_products_found' ); } ?></section>
	</div>
</main>
<div class="drawer" id="filter-drawer" role="dialog" aria-modal="true" aria-labelledby="filter-title" hidden><div class="drawer-backdrop" data-close></div><div class="drawer-panel drawer-panel--left"><div class="drawer-heading"><strong id="filter-title">Filtres</strong><button class="icon-button" data-close><?php echo ayru_icon( 'close' ); ?><span class="screen-reader-text">Fermer</span></button></div><?php if ( is_active_sidebar( 'shop-filters' ) ) { dynamic_sidebar( 'shop-filters' ); } else { ?><p>Les filtres apparaîtront après configuration des attributs produits.</p><?php } ?></div></div>
<?php get_footer(); ?>

