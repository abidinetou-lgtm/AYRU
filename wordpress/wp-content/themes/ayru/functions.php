<?php
/**
 * AYRU theme bootstrap.
 *
 * @package AYRU
 */

defined( 'ABSPATH' ) || exit;

define( 'AYRU_VERSION', '1.6.0' );

function ayru_setup(): void {
	load_theme_textdomain( 'ayru', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 520,
		'single_image_width'    => 900,
		'product_grid'          => array( 'default_rows' => 2, 'min_rows' => 1, 'max_rows' => 8, 'default_columns' => 4, 'min_columns' => 2, 'max_columns' => 5 ),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	register_nav_menus( array(
		'primary' => __( 'Navigation principale', 'ayru' ),
		'footer'  => __( 'Navigation du pied de page', 'ayru' ),
	) );
	add_image_size( 'ayru-product-card', 640, 640, true );
}
add_action( 'after_setup_theme', 'ayru_setup' );

function ayru_assets(): void {
	$uri = get_template_directory_uri();
	wp_enqueue_style( 'ayru-main', $uri . '/assets/css/main.css', array(), AYRU_VERSION );
	wp_enqueue_script( 'ayru-main', $uri . '/assets/js/main.js', array(), AYRU_VERSION, true );
	wp_localize_script( 'ayru-main', 'ayruSettings', array(
		'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
		'favoriteNonce' => wp_create_nonce( 'ayru_favorite' ),
		'cartUrl'       => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/panier/' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'ayru_assets' );

/**
 * Precharge les deux polices du premier rendu.
 * Les polices de marque sont auto-hebergees dans le theme : aucun appel a Google
 * Fonts (conformite RGPD/CNIL) et une latence en moins au chargement.
 */
function ayru_preload_fonts(): void {
	$uri = get_template_directory_uri();
	foreach ( array( 'poppins-700-latin.woff2', 'inter-var-latin.woff2' ) as $font ) {
		printf(
			'<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin>',
			esc_url( $uri . '/assets/fonts/' . $font )
		);
	}
}
add_action( 'wp_head', 'ayru_preload_fonts', 1 );

function ayru_widgets(): void {
	register_sidebar( array(
		'name'          => __( 'Filtres de la boutique', 'ayru' ),
		'id'            => 'shop-filters',
		'description'   => __( 'Blocs de filtres WooCommerce pour les collections.', 'ayru' ),
		'before_widget' => '<section class="filter-widget">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'ayru_widgets' );

function ayru_customize_register( WP_Customize_Manager $customizer ): void {
	$customizer->add_section( 'ayru_store', array( 'title' => __( 'AYRU — Boutique', 'ayru' ), 'priority' => 25 ) );
	$settings = array(
		'announcement' => array( 'Texte d’annonce', 'Une belle aventure vous attend ✦' ),
		'support_email' => array( 'Email du support', '' ),
		'instagram_url' => array( 'Lien Instagram', '' ),
		'tiktok_url'    => array( 'Lien TikTok', '' ),
		'pinterest_url' => array( 'Lien Pinterest', '' ),
		'youtube_url'   => array( 'Lien YouTube', '' ),
		'ga4_id'        => array( 'Google Analytics 4 (G-XXXXXXXXXX)', '' ),
		'meta_pixel_id' => array( 'Pixel Meta / Instagram (identifiant)', '' ),
		'tiktok_pixel_id' => array( 'Pixel TikTok (identifiant)', '' ),
	);
	foreach ( $settings as $key => $config ) {
		$is_url   = str_ends_with( $key, '_url' );
		$sanitize = $is_url ? 'esc_url_raw' : ( str_ends_with( $key, '_email' ) ? 'sanitize_email' : 'sanitize_text_field' );
		$customizer->add_setting( 'ayru_' . $key, array( 'default' => $config[1], 'sanitize_callback' => $sanitize ) );
		$customizer->add_control( 'ayru_' . $key, array( 'label' => $config[0], 'section' => 'ayru_store', 'type' => 'text' ) );
	}
}
add_action( 'customize_register', 'ayru_customize_register' );

function ayru_icon( string $name, int $size = 22 ): string {
	$icons = array(
		'menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close' => '<path d="m6 6 12 12M18 6 6 18"/>',
		'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
		'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'heart' => '<path d="M20.8 4.6a5.4 5.4 0 0 0-7.6 0L12 5.8l-1.2-1.2a5.4 5.4 0 0 0-7.6 7.6l1.2 1.2L12 21l7.6-7.6 1.2-1.2a5.4 5.4 0 0 0 0-7.6Z"/>',
		'bag' => '<path d="M6 8h12l1 13H5L6 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/>',
		'arrow' => '<path d="M5 12h14M14 7l5 5-5 5"/>',
		'chevron' => '<path d="m9 18 6-6-6-6"/>',
		'home' => '<path d="m3 11 9-8 9 8v10h-6v-6H9v6H3V11Z"/>',
		'gift' => '<path d="M4 10h16v11H4zM2 7h20v3H2zM12 7v14"/><path d="M12 7c-3 0-5-1-5-3 0-1 1-2 2-2 2 0 3 3 3 5Zm0 0c3 0 5-1 5-3 0-1-1-2-2-2-2 0-3 3-3 5Z"/>',
		'book' => '<path d="M4 4h6a3 3 0 0 1 3 3v13a3 3 0 0 0-3-3H4V4Zm16 0h-6a3 3 0 0 0-3 3v13a3 3 0 0 1 3-3h6V4Z"/>',
		'truck' => '<path d="M3 6h11v11H3zM14 10h4l3 3v4h-7z"/><circle cx="7" cy="19" r="2"/><circle cx="18" cy="19" r="2"/>',
		'lock' => '<rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
		'box' => '<path d="m4 7 8-4 8 4-8 4-8-4Zm0 0v10l8 4 8-4V7M12 11v10"/>',
		'headset' => '<path d="M4 14v-2a8 8 0 0 1 16 0v2M4 14h3v6H5a1 1 0 0 1-1-1v-5Zm16 0h-3v6h2a1 1 0 0 0 1-1v-5Z"/>',
		'filter' => '<path d="M4 6h16M7 12h10M10 18h4"/>',
		'sparkle' => '<path d="M12 2c.9 6 2 7.1 8 8-6 .9-7.1 2-8 8-.9-6-2-7.1-8-8 6-.9 7.1-2 8-8Z"/>',
		'star' => '<path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"/>',
		'plus' => '<path d="M12 5v14M5 12h14"/>',
		'minus' => '<path d="M5 12h14"/>',
		'share' => '<circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="m8.6 10.5 6.8-4M8.6 13.5l6.8 4"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="16.9" cy="7.1" r=".9" fill="currentColor" stroke="none"/>',
		'tiktok' => '<path d="M14.8 3.5c.5 2.2 1.9 3.5 4.2 3.7v3.1c-1.6.1-3-.4-4.2-1.3v5.8a5.6 5.6 0 1 1-4.9-5.5v3.2a2.5 2.5 0 1 0 1.9 2.4V3.5h3Z"/>',
		'youtube' => '<rect x="2.5" y="6" width="19" height="12" rx="3.5"/><path d="m10.4 9.4 5.2 2.6-5.2 2.6V9.4Z"/>',
		'pinterest' => '<circle cx="12" cy="12" r="9"/><path d="M9.7 20.1c-.5-1.5-.1-3.1.2-4.5l1-4.3a3 3 0 0 1 3-3.6c1.6 0 2.7 1.2 2.7 2.9 0 2-1.1 4.3-2.9 4.3-.9 0-1.5-.7-1.3-1.6"/>',
	);
	$path = $icons[ $name ] ?? $icons['arrow'];
	return sprintf( '<svg class="ayru-icon" width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">%2$s</svg>', $size, $path );
}

function ayru_logo(): string {
	return '<img class="site-logo" src="' . esc_url( get_template_directory_uri() . '/assets/images/brand/ayru-logo.svg' ) . '" width="146" height="52" alt="AYRU">';
}

function ayru_primary_fallback(): void {
	$links = array(
		'Maison & Cosy' => '/categorie-produit/maison-cosy/', 'Déco murale' => '/categorie-produit/deco-murale/',
		'Pyjamas & Détente' => '/categorie-produit/pyjamas-detente/', 'Papeterie' => '/categorie-produit/papeterie/',
		'Cadeaux' => '/categorie-produit/cadeaux/',
		'Tendances' => '/categorie-produit/tendances/', 'Guides & Conseils' => '/guides/',
	);
	echo '<ul class="menu">';
	foreach ( $links as $label => $url ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( $url ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

function ayru_cart_count(): int {
	return function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

function ayru_wc_fragments( array $fragments ): array {
	$fragments['.ayru-cart-count'] = '<span class="ayru-cart-count" aria-label="' . esc_attr( sprintf( _n( '%d article', '%d articles', ayru_cart_count(), 'ayru' ), ayru_cart_count() ) ) . '">' . esc_html( ayru_cart_count() ) . '</span>';
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ayru_wc_fragments' );

function ayru_body_classes( array $classes ): array {
	$classes[] = 'ayru-site';
	if ( ! class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woocommerce-unavailable';
	}
	return $classes;
}
add_filter( 'body_class', 'ayru_body_classes' );

function ayru_account_url( string $redirect = '' ): string {
	$url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
	return $redirect ? add_query_arg( 'redirect_to', $redirect, $url ) : $url;
}

function ayru_login_redirect( string $redirect, $user ): string {
	if ( isset( $_REQUEST['redirect_to'] ) ) {
		return wp_validate_redirect( esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ), $redirect );
	}
	return $redirect;
}
add_filter( 'woocommerce_login_redirect', 'ayru_login_redirect', 10, 2 );

function ayru_login_redirect_field(): void {
	if ( isset( $_GET['redirect_to'] ) ) {
		printf( '<input type="hidden" name="redirect_to" value="%s">', esc_url( wp_unslash( $_GET['redirect_to'] ) ) );
	}
}
add_action( 'woocommerce_login_form_end', 'ayru_login_redirect_field' );
add_action( 'woocommerce_register_form_end', 'ayru_login_redirect_field' );

function ayru_product_query( string $mode = 'recent', int $limit = 5 ): array {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}
	$args = array( 'status' => 'publish', 'limit' => $limit, 'orderby' => 'date', 'order' => 'DESC', 'return' => 'objects' );
	if ( 'featured' === $mode ) {
		$args['featured'] = true;
	} elseif ( 'sale' === $mode ) {
		$args['include'] = wc_get_product_ids_on_sale();
	}
	return wc_get_products( $args );
}

function ayru_render_product_rail( string $title, string $mode = 'recent', int $limit = 5 ): void {
	$products = ayru_product_query( $mode, $limit );
	echo '<section class="product-section section-shell" aria-labelledby="rail-' . esc_attr( sanitize_title( $title ) ) . '">';
	echo '<div class="section-heading"><div><h2 id="rail-' . esc_attr( sanitize_title( $title ) ) . '">' . esc_html( $title ) . '</h2></div><a class="text-link" href="' . esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/boutique/' ) ) . '">Voir tout ' . ayru_icon( 'arrow', 17 ) . '</a></div>';
	echo '<div class="product-rail">';
	if ( $products ) {
		foreach ( $products as $product ) {
			$GLOBALS['product'] = $product;
			get_template_part( 'template-parts/product-card' );
		}
		wp_reset_postdata();
	} else {
		get_template_part( 'template-parts/catalog-pending' );
	}
	echo '</div></section>';
}

function ayru_wrapper_start(): void { echo '<main id="main-content" class="site-main section-shell">'; }
function ayru_wrapper_end(): void { echo '</main>'; }
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
add_action( 'woocommerce_before_main_content', 'ayru_wrapper_start', 10 );
add_action( 'woocommerce_after_main_content', 'ayru_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

function ayru_add_to_list_button(): void {
	global $product;
	if ( ! $product instanceof WC_Product ) { return; }
	$logged_in = is_user_logged_in();
	printf(
		'<button class="button button--secondary ayru-add-list" type="button" data-ayru-add-list="%1$d" data-nonce="%2$s"%3$s>%4$s Ajouter à Ma Liste AYRU</button>',
		$product->get_id(),
		esc_attr( wp_create_nonce( 'ayru_list' ) ),
		$logged_in ? '' : ' data-login-url="' . esc_url( ayru_account_url( get_permalink() ) ) . '"',
		ayru_icon( 'heart', 19 )
	);
	echo '<p class="ayru-list-feedback" aria-live="polite"></p>';
}
add_action( 'woocommerce_after_add_to_cart_form', 'ayru_add_to_list_button', 12 );

function ayru_product_compliance_tab( array $tabs ): array {
	global $product;
	if ( ! $product instanceof WC_Product ) { return $tabs; }
	$fields = array_filter( array(
		'Fabricant' => $product->get_meta( '_ayru_manufacturer' ),
		'Responsable UE' => $product->get_meta( '_ayru_eu_responsible' ),
		'Référence produit' => $product->get_meta( '_ayru_product_reference' ),
		'Âge / avertissements' => $product->get_meta( '_ayru_age_warning' ),
		'Informations de sécurité' => $product->get_meta( '_ayru_safety_info' ),
	) );
	if ( $fields ) {
		$tabs['ayru_compliance'] = array(
			'title' => __( 'Sécurité & traçabilité', 'ayru' ),
			'priority' => 25,
			'callback' => function () use ( $fields ) {
				echo '<h2>' . esc_html__( 'Informations de sécurité et de traçabilité', 'ayru' ) . '</h2><dl class="compliance-list">';
				foreach ( $fields as $label => $value ) { printf( '<div><dt>%s</dt><dd>%s</dd></div>', esc_html( $label ), nl2br( esc_html( $value ) ) ); }
				echo '</dl>';
			},
		);
	}
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'ayru_product_compliance_tab' );

function ayru_order_received_visual( int $order_id ): void {
	if ( ! $order_id ) { return; }
	printf( '<div class="order-success-visual"><img src="%s" alt="NEB célèbre votre commande"><div><p class="eyebrow">Commande confirmée</p><h2>Merci, votre commande est bien enregistrée.</h2><p>Les prochaines informations seront envoyées à l’adresse indiquée lors de la commande.</p></div></div>', esc_url( get_template_directory_uri() . '/assets/images/neb/neb-order-success.webp' ) );
}
add_action( 'woocommerce_thankyou', 'ayru_order_received_visual', 5 );

function ayru_preload_hero(): void {
	if ( is_front_page() ) {
		printf( '<link rel="preload" as="image" type="image/webp" href="%s" media="(min-width: 768px)" fetchpriority="high"><link rel="preload" as="image" type="image/webp" href="%s" media="(max-width: 767px)" fetchpriority="high">', esc_url( get_template_directory_uri() . '/assets/images/hero/home-desktop.webp' ), esc_url( get_template_directory_uri() . '/assets/images/hero/home-mobile.webp' ) );
	}
}
add_action( 'wp_head', 'ayru_preload_hero', 1 );

function ayru_favicon(): void {
	printf( '<link rel="icon" href="%s" type="image/webp">', esc_url( get_template_directory_uri() . '/assets/images/brand/favicon-source.webp' ) );
}
add_action( 'wp_head', 'ayru_favicon', 2 );

function ayru_robots( array $robots ): array {
	$filter_keys = preg_grep( '/^(filter_|query_type_|min_price|max_price|orderby$)/', array_keys( $_GET ) );
	if ( is_search() || $filter_keys ) {
		$robots['noindex'] = true;
		$robots['follow'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'ayru_robots' );

function ayru_social_meta(): void {
	if ( is_admin() ) { return; }
	$title = wp_get_document_title();
	$description = get_bloginfo( 'description' );
	$url = is_singular() ? get_permalink() : home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) );
	$image = '';
	if ( is_singular() && has_post_thumbnail() ) { $image = get_the_post_thumbnail_url( null, 'large' ); }
	if ( ! $image ) { $image = get_template_directory_uri() . '/assets/images/hero/home-desktop.webp'; }
	printf( "\n<meta name=\"description\" content=\"%s\">\n<meta property=\"og:type\" content=\"%s\">\n<meta property=\"og:title\" content=\"%s\">\n<meta property=\"og:description\" content=\"%s\">\n<meta property=\"og:url\" content=\"%s\">\n<meta property=\"og:image\" content=\"%s\">\n<meta name=\"twitter:card\" content=\"summary_large_image\">\n", esc_attr( $description ), is_singular( 'guide' ) ? 'article' : 'website', esc_attr( $title ), esc_attr( $description ), esc_url( $url ), esc_url( $image ) );
}
add_action( 'wp_head', 'ayru_social_meta', 5 );

function ayru_structured_data(): void {
	if ( ! is_front_page() && ! is_singular( 'guide' ) ) { return; }
	$data = is_singular( 'guide' ) ? array(
		'@context' => 'https://schema.org', '@type' => 'Article', 'headline' => get_the_title(),
		'datePublished' => get_the_date( DATE_W3C ), 'dateModified' => get_the_modified_date( DATE_W3C ),
		'mainEntityOfPage' => get_permalink(), 'publisher' => array( '@type' => 'Organization', 'name' => 'AYRU', 'url' => home_url( '/' ) ),
	) : array(
		'@context' => 'https://schema.org', '@graph' => array(
			array( '@type' => 'Organization', '@id' => home_url( '/#organization' ), 'name' => 'AYRU', 'url' => home_url( '/' ), 'logo' => get_template_directory_uri() . '/assets/images/brand/ayru-logo.svg' ),
			array( '@type' => 'WebSite', '@id' => home_url( '/#website' ), 'name' => 'AYRU', 'url' => home_url( '/' ), 'publisher' => array( '@id' => home_url( '/#organization' ) ), 'potentialAction' => array( '@type' => 'SearchAction', 'target' => home_url( '/?s={search_term_string}' ), 'query-input' => 'required name=search_term_string' ) ),
		),
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
}
add_action( 'wp_head', 'ayru_structured_data', 20 );

/**
 * Icone associee a une categorie produit.
 *
 * Aligne les pastilles des pages collection sur les raccourcis de l'accueil.
 * Toute categorie inconnue retombe sur l'etincelle AYRU, sans casser l'affichage.
 *
 * @param string $slug Identifiant de la categorie.
 * @return string Nom d'icone connu de ayru_icon().
 */
function ayru_category_icon( string $slug ): string {
	$map = array(
		'maison-cosy'     => 'home',
		'deco-murale'     => 'sparkle',
		'pyjamas-detente' => 'heart',
		'papeterie'       => 'book',
		'cadeaux'       => 'gift',
		'tendances'     => 'star',
		'guides'        => 'book',
		'repas'         => 'box',
		'sorties'       => 'truck',
	);
	return $map[ $slug ] ?? 'sparkle';
}

/**
 * Traceurs de mesure d'audience et de publicite.
 *
 * Les scripts sont emis en `type="text/plain"` avec l'attribut
 * `data-ayru-consent` : le navigateur ne les execute pas. Ils ne sont actives
 * qu'apres acceptation explicite dans le bandeau de consentement (voir
 * assets/js/main.js). Sans identifiant renseigne dans le personnalisateur,
 * rien n'est emis du tout.
 */
function ayru_tracking_scripts(): void {
	if ( is_admin() ) {
		return;
	}

	$ga4    = trim( (string) get_theme_mod( 'ayru_ga4_id', '' ) );
	$meta   = trim( (string) get_theme_mod( 'ayru_meta_pixel_id', '' ) );
	$tiktok = trim( (string) get_theme_mod( 'ayru_tiktok_pixel_id', '' ) );

	if ( $ga4 ) {
		printf(
			'<script type="text/plain" data-ayru-consent async src="https://www.googletagmanager.com/gtag/js?id=%s"></script>',
			esc_attr( $ga4 )
		);
		printf(
			'<script type="text/plain" data-ayru-consent>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag("js",new Date());gtag("config",%s);</script>',
			wp_json_encode( $ga4 )
		);
	}

	if ( $meta ) {
		printf(
			'<script type="text/plain" data-ayru-consent>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0";n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,"script","https://connect.facebook.net/en_US/fbevents.js");fbq("init",%s);fbq("track","PageView");</script>',
			wp_json_encode( $meta )
		);
	}

	if ( $tiktok ) {
		printf(
			'<script type="text/plain" data-ayru-consent>!function(w,d,t){w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"];ttq.setAndDefer=function(e,i){e[i]=function(){e.push([i].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.load=function(e){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{};ttq._i[e]=[];ttq._i[e]._u=i;ttq._t=ttq._t||{};ttq._t[e]=+new Date;ttq._o=ttq._o||{};ttq._o[e]={};var o=d.createElement("script");o.type="text/javascript";o.async=!0;o.src=i+"?sdkid="+e+"&lib="+t;var a=d.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};ttq.load(%s);ttq.page();}(window,document,"ttq");</script>',
			wp_json_encode( $tiktok )
		);
	}
}
add_action( 'wp_footer', 'ayru_tracking_scripts', 20 );
