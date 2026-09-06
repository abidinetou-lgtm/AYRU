<?php
/**
 * Reaffecte les produits de demonstration vers la nouvelle taxonomie AYRU.
 *
 * Le positionnement passe de la niche bebe a la deco, la maison et le textile
 * femme. Ce script est destine aux fixtures de developpement : il ne doit pas
 * etre execute sur un catalogue reel sans relecture de la table de
 * correspondance ci-dessous.
 *
 * Usage : wp eval-file scripts/remap-categories.php
 *
 * @package AYRU
 */

defined( 'ABSPATH' ) || exit;

$map = array(
	'bebe'         => 'maison-cosy',
	'chambre-deco' => 'maison-cosy',
	'peluches'     => 'pyjamas-detente',
);

$products = get_posts(
	array(
		'post_type'      => 'product',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
	)
);

$moved = 0;

foreach ( $products as $product_id ) {
	$slugs = wp_get_object_terms( $product_id, 'product_cat', array( 'fields' => 'slugs' ) );
	if ( is_wp_error( $slugs ) ) {
		continue;
	}

	$new_slugs = array();
	$changed   = false;

	foreach ( $slugs as $slug ) {
		// Un pyjama reste un pyjama, quelle que soit son ancienne categorie.
		$title = get_the_title( $product_id );
		if ( false !== stripos( $title, 'pyjama' ) ) {
			$new_slugs[] = 'pyjamas-detente';
			$changed     = true;
			continue;
		}
		if ( isset( $map[ $slug ] ) ) {
			$new_slugs[] = $map[ $slug ];
			$changed     = true;
		} else {
			$new_slugs[] = $slug;
		}
	}

	if ( ! $changed ) {
		continue;
	}

	$new_slugs = array_values( array_unique( $new_slugs ) );
	wp_set_object_terms( $product_id, $new_slugs, 'product_cat', false );
	++$moved;
	WP_CLI::log( sprintf( '%s : %s -> %s', get_the_title( $product_id ), implode( ',', $slugs ), implode( ',', $new_slugs ) ) );
}

// Les anciennes categories, une fois videes, n'ont plus de raison d'exister.
foreach ( array_keys( $map ) as $old ) {
	$term = get_term_by( 'slug', $old, 'product_cat' );
	if ( $term && 0 === (int) $term->count ) {
		wp_delete_term( $term->term_id, 'product_cat' );
		WP_CLI::log( 'Categorie supprimee : ' . $old );
	}
}

WP_CLI::success( sprintf( '%d produit(s) reaffecte(s).', $moved ) );
