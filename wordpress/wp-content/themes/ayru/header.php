<?php defined( 'ABSPATH' ) || exit; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main-content"><?php esc_html_e( 'Aller au contenu', 'ayru' ); ?></a>
<div class="announcement-bar"><span class="sparkle">✦</span> <?php echo esc_html( get_theme_mod( 'ayru_announcement', 'Une belle aventure vous attend ✦' ) ); ?></div>
<header class="site-header" data-site-header>
	<div class="header-shell section-shell">
		<button class="icon-button mobile-only" type="button" data-open="mobile-menu" aria-controls="mobile-menu" aria-expanded="false"><span class="screen-reader-text">Ouvrir le menu</span><?php echo ayru_icon( 'menu' ); ?></button>
		<a class="brand-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="AYRU — Accueil"><?php echo ayru_logo(); ?></a>
		<nav class="desktop-nav" aria-label="Navigation principale">
			<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'ayru_primary_fallback' ) ); ?>
		</nav>
		<form class="header-search desktop-only" role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php echo ayru_icon( 'search', 19 ); ?><label class="screen-reader-text" for="header-search">Rechercher</label><input id="header-search" name="s" type="search" placeholder="Rechercher un produit, une idée…"><input type="hidden" name="post_type" value="product">
		</form>
		<div class="header-actions">
			<button class="icon-button mobile-only" type="button" data-open="search-dialog" aria-controls="search-dialog"><span class="screen-reader-text">Rechercher</span><?php echo ayru_icon( 'search' ); ?></button>
			<a class="icon-button desktop-only" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/mon-compte/' ) ); ?>"><span class="screen-reader-text">Mon compte</span><?php echo ayru_icon( 'user' ); ?></a>
			<a class="icon-button desktop-only" href="<?php echo esc_url( home_url( '/favoris/' ) ); ?>"><span class="screen-reader-text">Mes favoris</span><?php echo ayru_icon( 'heart' ); ?></a>
			<button class="icon-button cart-trigger" type="button" data-open="mini-cart" aria-controls="mini-cart"><span class="screen-reader-text">Ouvrir le panier</span><?php echo ayru_icon( 'bag' ); ?><span class="ayru-cart-count" aria-label="<?php echo esc_attr( ayru_cart_count() . ' articles' ); ?>"><?php echo esc_html( ayru_cart_count() ); ?></span></button>
		</div>
	</div>
</header>
<div class="drawer" id="mobile-menu" role="dialog" aria-modal="true" aria-labelledby="mobile-menu-title" hidden>
	<div class="drawer-backdrop" data-close></div><div class="drawer-panel drawer-panel--left"><div class="drawer-heading"><strong id="mobile-menu-title">Explorer AYRU</strong><button class="icon-button" data-close><?php echo ayru_icon( 'close' ); ?><span class="screen-reader-text">Fermer</span></button></div><?php ayru_primary_fallback(); ?><a class="button button--secondary" href="<?php echo esc_url( home_url( '/ma-liste/' ) ); ?>">Ma Liste AYRU</a></div>
</div>
<div class="drawer" id="search-dialog" role="dialog" aria-modal="true" aria-labelledby="search-title" hidden>
	<div class="drawer-backdrop" data-close></div><div class="drawer-panel drawer-panel--top"><div class="drawer-heading"><strong id="search-title">Que recherchez-vous ?</strong><button class="icon-button" data-close><?php echo ayru_icon( 'close' ); ?><span class="screen-reader-text">Fermer</span></button></div><?php get_search_form(); ?></div>
</div>
<aside class="drawer" id="mini-cart" role="dialog" aria-modal="true" aria-labelledby="mini-cart-title" hidden>
	<div class="drawer-backdrop" data-close></div><div class="drawer-panel drawer-panel--right"><div class="drawer-heading"><strong id="mini-cart-title">Mon panier (<?php echo esc_html( ayru_cart_count() ); ?>)</strong><button class="icon-button" data-close><?php echo ayru_icon( 'close' ); ?><span class="screen-reader-text">Fermer</span></button></div><div class="widget_shopping_cart_content"><?php if ( function_exists( 'woocommerce_mini_cart' ) ) { woocommerce_mini_cart(); } else { get_template_part( 'template-parts/empty-state', null, array( 'type' => 'cart' ) ); } ?></div></div>
</aside>

