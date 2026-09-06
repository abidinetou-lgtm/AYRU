<?php
/**
 * Local development fixtures. Run explicitly with WP-CLI only.
 * All catalog data is labelled DEMO and must be deleted before production.
 */
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) || 'v2' === get_option( 'ayru_demo_seeded' ) ) { return; }

$categories = array( 'Maison & Cosy' => 'maison-cosy', 'Déco murale' => 'deco-murale', 'Pyjamas & Détente' => 'pyjamas-detente', 'Papeterie' => 'papeterie', 'Cadeaux' => 'cadeaux', 'Tendances' => 'tendances' );
$term_ids = array();
foreach ( $categories as $name => $slug ) {
	$existing = term_exists( $slug, 'product_cat' );
	$term = $existing ?: wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
	$term_ids[ $slug ] = is_array( $term ) ? (int) $term['term_id'] : (int) $term;
}

$products = array(
	array( 'Plaid nuage — DÉMO', 'maison-cosy', '34.90', '', 20 ),
	array( 'Coussin étoile — DÉMO', 'maison-cosy', '24.90', '', 20 ),
	array( 'Affiche pastel — DÉMO', 'deco-murale', '17.90', '', 20 ),
	array( 'Stickers muraux — DÉMO', 'deco-murale', '12.90', '', 20 ),
	array( 'Carnet nuage — DÉMO', 'papeterie', '14.90', '', 20 ),
	array( 'Boîte à souvenirs — DÉMO', 'papeterie', '27.90', '', 20 ),
	array( 'Coffret douceur — DÉMO', 'cadeaux', '39.90', '31.90', 12 ),
	array( 'Bougie cocon — DÉMO', 'tendances', '19.90', '', 20 ),
	array( 'Chaussons douillets — DÉMO', 'pyjamas-detente', '22.90', '', 20 ),
	array( 'Pyjama indisponible — DÉMO', 'pyjamas-detente', '29.90', '', 0 ),
);

foreach ( $products as $index => $fixture ) {
	$slug = sanitize_title( $fixture[0] );
	if ( get_page_by_path( $slug, OBJECT, 'product' ) ) { continue; }
	$product = new WC_Product_Simple();
	$product->set_name( $fixture[0] );
	$product->set_slug( $slug );
	$product->set_status( 'publish' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_description( 'Produit de démonstration locale. Remplacer la description, les visuels, le prix et les informations fournisseur avant toute mise en production.' );
	$product->set_short_description( 'Donnée de démonstration à remplacer avant mise en ligne.' );
	$product->set_regular_price( $fixture[2] );
	if ( $fixture[3] ) { $product->set_sale_price( $fixture[3] ); }
	$product->set_manage_stock( true );
	$product->set_stock_quantity( $fixture[4] );
	$product->set_stock_status( $fixture[4] > 0 ? 'instock' : 'outofstock' );
	$product->set_category_ids( array( $term_ids[ $fixture[1] ] ) );
	$product->set_featured( $index < 5 );
	$product->save();
}

$variable_slug = 'body-douceur-variable-demo';
if ( ! get_page_by_path( $variable_slug, OBJECT, 'product' ) ) {
	$variable = new WC_Product_Variable();
	$variable->set_name( 'Body douceur à tailles — DÉMO' );
	$variable->set_slug( $variable_slug );
	$variable->set_status( 'publish' );
	$variable->set_catalog_visibility( 'visible' );
	$variable->set_description( 'Produit variable de démonstration locale. Remplacer toutes les données avant la mise en production.' );
	$variable->set_short_description( 'Cas de test des tailles, clairement identifié comme démonstration.' );
	$variable->set_category_ids( array( $term_ids['pyjamas-detente'] ) );
	$attribute = new WC_Product_Attribute();
	$attribute->set_id( 0 );
	$attribute->set_name( 'Taille' );
	$attribute->set_options( array( '3 mois', '6 mois' ) );
	$attribute->set_position( 0 );
	$attribute->set_visible( true );
	$attribute->set_variation( true );
	$variable->set_attributes( array( $attribute ) );
	$variable_id = $variable->save();
	foreach ( array( '3 mois' => '18.90', '6 mois' => '19.90' ) as $size => $price ) {
		$variation = new WC_Product_Variation();
		$variation->set_parent_id( $variable_id );
		$variation->set_attributes( array( 'taille' => $size ) );
		$variation->set_regular_price( $price );
		$variation->set_manage_stock( true );
		$variation->set_stock_quantity( 8 );
		$variation->set_status( 'publish' );
		$variation->save();
	}
	WC_Product_Variable::sync( $variable_id );
}

$home = get_page_by_path( 'accueil', OBJECT, 'page' );
$home_id = $home ? $home->ID : wp_insert_post( array( 'post_title' => 'Accueil', 'post_name' => 'accueil', 'post_type' => 'page', 'post_status' => 'publish' ) );
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home_id );
update_option( 'ayru_demo_seeded', 'v2' );
