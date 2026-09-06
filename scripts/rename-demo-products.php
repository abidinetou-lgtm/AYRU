<?php
/**
 * Renomme les fixtures de demonstration heritees de la niche bebe.
 *
 * Sert uniquement a rendre l'apercu local coherent avec le nouveau
 * positionnement. Ces produits restent marques DEMO et doivent etre supprimes
 * avant toute mise en production.
 *
 * Usage : wp eval-file scripts/rename-demo-products.php
 *
 * @package AYRU
 */

defined( 'ABSPATH' ) || exit;

$renames = array(
	'Coffret naissance en promotion — DÉMO' => 'Coffret cocon en promotion — DÉMO',
	'Biberon doux — DÉMO'                   => 'Bougie cocon — DÉMO',
	'Gigoteuse fleurie — DÉMO'              => 'Plaid fleuri — DÉMO',
	'Tour d’éveil — DÉMO'                   => 'Carnet nuage — DÉMO',
	'Doudou ourson — DÉMO'                  => 'Chaussons douillets — DÉMO',
	'Body douceur à tailles — DÉMO'         => 'Pyjama douceur à tailles — DÉMO',
	'Cape de bain — DÉMO'                   => 'Trousse douceur — DÉMO',
	'Mobile musical — DÉMO'                 => 'Affiche pastel — DÉMO',
	'Veilleuse nuage — DÉMO'                => 'Coussin nuage — DÉMO',
);

$products = get_posts(
	array(
		'post_type'      => 'product',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	)
);

$done = 0;

foreach ( $products as $product_id ) {
	$title = get_the_title( $product_id );
	if ( ! isset( $renames[ $title ] ) ) {
		continue;
	}

	// Le slug suit le titre : sans cela les anciennes URL bebe resteraient visibles.
	wp_update_post(
		array(
			'ID'         => $product_id,
			'post_title' => $renames[ $title ],
			'post_name'  => sanitize_title( $renames[ $title ] ),
		)
	);
	++$done;
	WP_CLI::log( $title . ' -> ' . $renames[ $title ] );
}

WP_CLI::success( sprintf( '%d fixture(s) renommee(s).', $done ) );
