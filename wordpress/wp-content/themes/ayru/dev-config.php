<?php
/** Local-only site configuration. */
defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WC_Install' ) ) {
	WC_Install::create_pages();
	// WooCommerce active désormais un écran "prochainement" sur les nouvelles boutiques.
	// Le désactiver est indispensable pour tester le vrai catalogue en environnement local.
	update_option( 'woocommerce_coming_soon', 'no' );
	update_option( 'woocommerce_store_pages_only', 'no' );
	update_option( 'woocommerce_currency', 'EUR' );
	update_option( 'woocommerce_default_country', 'FR' );
	update_option( 'woocommerce_hide_out_of_stock_items', 'no' );
	update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
	update_option( 'woocommerce_registration_generate_username', 'yes' );
	update_option( 'woocommerce_registration_generate_password', 'no' );
	update_option( 'woocommerce_checkout_privacy_policy_text', 'Vos données personnelles sont utilisées pour traiter votre commande et gérer votre expérience sur AYRU. Consultez notre [privacy_policy].' );
	update_option( 'woocommerce_registration_privacy_policy_text', 'Vos données personnelles sont utilisées pour créer et gérer votre compte AYRU. Consultez notre [privacy_policy].' );
	$pages = array(
		'woocommerce_shop_page_id' => array( 'Boutique', 'boutique', '' ),
		'woocommerce_cart_page_id' => array( 'Panier', 'panier', '[woocommerce_cart]' ),
		'woocommerce_checkout_page_id' => array( 'Commande', 'commande', '[woocommerce_checkout]' ),
		'woocommerce_myaccount_page_id' => array( 'Mon compte', 'mon-compte', '[woocommerce_my_account]' ),
	);
	foreach ( $pages as $option => $data ) {
		$page_id = absint( get_option( $option ) );
		if ( $page_id ) {
			wp_update_post(
				array(
					'ID'           => $page_id,
					'post_title'   => $data[0],
					'post_name'    => $data[1],
					'post_content' => $data[2],
				)
			);
		}
	}
	$permalinks = get_option( 'woocommerce_permalinks', array() );
	$permalinks['product_base']  = '/produit/';
	$permalinks['category_base'] = 'categorie-produit';
	update_option( 'woocommerce_permalinks', $permalinks );
}
$sample_page = get_page_by_path( 'sample-page', OBJECT, 'page' );
if ( $sample_page && 'Sample Page' === $sample_page->post_title ) {
	wp_update_post( array( 'ID' => $sample_page->ID, 'post_status' => 'draft' ) );
}
$privacy_page = get_page_by_path( 'confidentialite', OBJECT, 'page' );
if ( $privacy_page ) { update_option( 'wp_page_for_privacy_policy', $privacy_page->ID ); }
$terms_page = get_page_by_path( 'cgv', OBJECT, 'page' );
if ( $terms_page ) { update_option( 'woocommerce_terms_page_id', $terms_page->ID ); }
flush_rewrite_rules();
