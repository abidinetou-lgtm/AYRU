<?php
/**
 * Import de catalogue AYRU depuis un fichier CSV.
 *
 * Cree ou met a jour des produits WooCommerce a partir d'un tableur rempli a la
 * main. Concu pour une selection curatee de vingt a quarante references, pas
 * pour deverser un catalogue entier : la curation est le seul avantage
 * concurrentiel d'une boutique qui revend les memes produits que tout le monde.
 *
 * Ce que le script fait et que l'importateur CSV natif de WooCommerce ne fait
 * pas :
 * - telecharge les images distantes dans la mediatheque et les rattache ;
 * - remplit les champs AYRU de conformite et de fournisseur ;
 * - cree les attributs globaux necessaires au fonctionnement des filtres ;
 * - genere les variations d'un produit variable ;
 * - echelonne les dates de creation, sinon le badge « Nouveau », calcule sur
 *   date_created, s'affiche sur tout le catalogue pendant trente jours.
 *
 * Usage :
 *   wp eval-file scripts/import-catalog.php /chemin/vers/catalogue.csv
 *
 * Le script est idempotent : relance-le autant de fois que tu veux, il met a
 * jour les produits existants au lieu de les dupliquer, en s'appuyant sur le
 * slug.
 *
 * @package AYRU
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WooCommerce' ) ) {
	WP_CLI::error( 'WooCommerce est inactif.' );
}

$chemin = $args[0] ?? '';
if ( '' === $chemin || ! file_exists( $chemin ) ) {
	WP_CLI::error( 'Fichier CSV introuvable. Usage : wp eval-file scripts/import-catalog.php catalogue.csv' );
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

/**
 * Recupere ou cree une categorie produit a partir de son slug.
 *
 * @param string $slug Slug de la categorie.
 * @return int Identifiant du terme, 0 si le slug est vide ou inconnu.
 */
function ayru_import_categorie( string $slug ): int {
	$slug = sanitize_title( trim( $slug ) );
	if ( '' === $slug ) {
		return 0;
	}
	$terme = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $terme ) {
		return (int) $terme->term_id;
	}
	WP_CLI::warning( 'Categorie inconnue, ignoree : ' . $slug );
	return 0;
}

/**
 * Importe une image dans la mediatheque et renvoie son identifiant.
 *
 * Accepte une URL distante ou un chemin local. Evite les doublons en
 * memorisant l'origine dans une meta.
 *
 * @param string $source  URL ou chemin du fichier.
 * @param int    $produit Produit auquel rattacher l'image.
 * @return int Identifiant de la piece jointe, 0 en cas d'echec.
 */
function ayru_import_image( string $source, int $produit ): int {
	$source = trim( $source );
	if ( '' === $source ) {
		return 0;
	}

	// Deja importee lors d'un passage precedent ?
	$existant = get_posts(
		array(
			'post_type'   => 'attachment',
			'post_status' => 'any',
			'numberposts' => 1,
			'fields'      => 'ids',
			'meta_key'    => '_ayru_source_image',
			'meta_value'  => $source,
		)
	);
	if ( $existant ) {
		return (int) $existant[0];
	}

	if ( preg_match( '#^https?://#i', $source ) ) {
		$temporaire = download_url( $source, 30 );
		if ( is_wp_error( $temporaire ) ) {
			WP_CLI::warning( 'Image non telechargee : ' . $source . ' (' . $temporaire->get_error_message() . ')' );
			return 0;
		}
		$fichier = array(
			'name'     => basename( wp_parse_url( $source, PHP_URL_PATH ) ) ?: 'image.jpg',
			'tmp_name' => $temporaire,
		);
		$id = media_handle_sideload( $fichier, $produit );
		if ( is_wp_error( $id ) ) {
			@unlink( $temporaire );
			WP_CLI::warning( 'Image non importee : ' . $source );
			return 0;
		}
	} else {
		if ( ! file_exists( $source ) ) {
			WP_CLI::warning( 'Fichier image absent : ' . $source );
			return 0;
		}
		$copie = wp_tempnam( basename( $source ) );
		copy( $source, $copie );
		$id = media_handle_sideload( array( 'name' => basename( $source ), 'tmp_name' => $copie ), $produit );
		if ( is_wp_error( $id ) ) {
			WP_CLI::warning( 'Fichier image non importe : ' . $source );
			return 0;
		}
	}

	update_post_meta( (int) $id, '_ayru_source_image', $source );
	return (int) $id;
}

/**
 * Cree si besoin un attribut global et renvoie son nom de taxonomie.
 *
 * Les attributs globaux (pa_*) sont indispensables : sans eux les filtres de
 * la boutique restent vides, quel que soit le nombre de produits.
 *
 * @param string $label Libelle lisible, par exemple « Couleur ».
 * @return string Nom de taxonomie, par exemple pa_couleur.
 */
function ayru_import_attribut( string $label ): string {
	$slug     = wc_sanitize_taxonomy_name( $label );
	$taxonomie = wc_attribute_taxonomy_name( $slug );

	if ( ! taxonomy_exists( $taxonomie ) ) {
		$id = wc_create_attribute(
			array(
				'name'         => $label,
				'slug'         => $slug,
				'type'         => 'select',
				'order_by'     => 'menu_order',
				'has_archives' => false,
			)
		);
		if ( is_wp_error( $id ) ) {
			WP_CLI::warning( 'Attribut non cree : ' . $label );
			return '';
		}
		register_taxonomy(
			$taxonomie,
			'product',
			array( 'hierarchical' => false, 'show_ui' => false, 'query_var' => true, 'rewrite' => false )
		);
	}

	return $taxonomie;
}

// ---------------------------------------------------------------------------
// Lecture du fichier
// ---------------------------------------------------------------------------

$flux = fopen( $chemin, 'r' );
if ( ! $flux ) {
	WP_CLI::error( 'Lecture impossible : ' . $chemin );
}

$entetes = fgetcsv( $flux, 0, ',' );
if ( ! $entetes ) {
	WP_CLI::error( 'CSV vide.' );
}
// Retire un eventuel BOM laisse par Excel, qui casserait la premiere colonne.
$entetes[0] = preg_replace( '/^\xEF\xBB\xBF/', '', $entetes[0] );
$entetes    = array_map( fn( $h ) => trim( strtolower( $h ) ), $entetes );

$cree     = 0;
$maj      = 0;
$ignore   = 0;
$ligne_no = 1;
// Les dates sont echelonnees d'une heure par produit pour eviter que tout le
// catalogue porte le badge « Nouveau » au meme instant.
$decalage = 0;

while ( ( $ligne = fgetcsv( $flux, 0, ',' ) ) !== false ) {
	++$ligne_no;
	if ( count( array_filter( $ligne, fn( $v ) => '' !== trim( (string) $v ) ) ) === 0 ) {
		continue;
	}

	$d = array_combine( $entetes, array_pad( array_slice( $ligne, 0, count( $entetes ) ), count( $entetes ), '' ) );
	$d = array_map( fn( $v ) => trim( (string) $v ), $d );

	$nom = $d['nom'] ?? '';
	if ( '' === $nom ) {
		WP_CLI::warning( 'Ligne ' . $ligne_no . ' ignoree : colonne « nom » vide.' );
		++$ignore;
		continue;
	}

	$slug     = sanitize_title( '' !== ( $d['slug'] ?? '' ) ? $d['slug'] : $nom );
	$existant = get_page_by_path( $slug, OBJECT, 'product' );
	$variantes = array_filter( array_map( 'trim', explode( '|', $d['variantes'] ?? '' ) ) );
	$variable  = ! empty( $variantes );

	if ( $existant ) {
		$produit = wc_get_product( $existant->ID );
	} else {
		$produit = $variable ? new WC_Product_Variable() : new WC_Product_Simple();
	}

	$produit->set_name( $nom );
	$produit->set_slug( $slug );
	$produit->set_status( 'publish' );
	$produit->set_catalog_visibility( 'visible' );
	$produit->set_description( $d['description'] ?? '' );
	$produit->set_short_description( $d['description_courte'] ?? '' );

	if ( '' !== ( $d['sku'] ?? '' ) ) {
		$produit->set_sku( $d['sku'] );
	}

	if ( ! $variable ) {
		$produit->set_regular_price( $d['prix'] ?? '' );
		$produit->set_sale_price( '' !== ( $d['prix_promo'] ?? '' ) ? $d['prix_promo'] : '' );
		$stock = (int) ( $d['stock'] ?? 0 );
		$produit->set_manage_stock( true );
		$produit->set_stock_quantity( $stock );
		$produit->set_stock_status( $stock > 0 ? 'instock' : 'outofstock' );
	}

	$categorie = ayru_import_categorie( $d['categorie'] ?? '' );
	if ( $categorie ) {
		$produit->set_category_ids( array( $categorie ) );
	}

	if ( ! $existant ) {
		$produit->set_date_created( time() - ( $decalage * HOUR_IN_SECONDS ) );
		++$decalage;
	}

	$produit_id = $produit->save();

	// Images : principale puis galerie.
	$image_id = ayru_import_image( $d['image'] ?? '', $produit_id );
	if ( $image_id ) {
		$produit->set_image_id( $image_id );
	}
	$galerie = array();
	foreach ( array_filter( array_map( 'trim', explode( '|', $d['galerie'] ?? '' ) ) ) as $url ) {
		$gid = ayru_import_image( $url, $produit_id );
		if ( $gid ) {
			$galerie[] = $gid;
		}
	}
	if ( $galerie ) {
		$produit->set_gallery_image_ids( $galerie );
	}

	// Attribut de variation, par exemple Couleur ou Taille.
	if ( $variable ) {
		$label     = '' !== ( $d['variante_nom'] ?? '' ) ? $d['variante_nom'] : 'Couleur';
		$taxonomie = ayru_import_attribut( $label );
		if ( '' !== $taxonomie ) {
			wp_set_object_terms( $produit_id, $variantes, $taxonomie );
			$attribut = new WC_Product_Attribute();
			$attribut->set_id( wc_attribute_taxonomy_id_by_name( $taxonomie ) );
			$attribut->set_name( $taxonomie );
			$attribut->set_options( $variantes );
			$attribut->set_visible( true );
			$attribut->set_variation( true );
			$produit->set_attributes( array( $attribut ) );
		}
	}

	$produit->save();

	// Champs AYRU. Les cles publiques et internes viennent du plugin, pour que
	// le script ne redefinisse jamais sa propre liste en parallele.
	$correspondance = array(
		'fabricant'          => '_ayru_manufacturer',
		'responsable_ue'     => '_ayru_eu_responsible',
		'reference'          => '_ayru_product_reference',
		'pays_origine'       => '_ayru_country_origin',
		'matieres'           => '_ayru_materials',
		'dimensions'         => '_ayru_dimensions',
		'ce'                 => '_ayru_ce_marking',
		'age_avertissement'  => '_ayru_age_warning',
		'securite'           => '_ayru_safety_info',
		'fournisseur'        => '_ayru_supplier',
		'sku_fournisseur'    => '_ayru_supplier_sku',
		'prix_achat'         => '_ayru_supplier_cost',
		'url_fournisseur'    => '_ayru_supplier_url',
	);
	foreach ( $correspondance as $colonne => $meta ) {
		if ( isset( $d[ $colonne ] ) && '' !== $d[ $colonne ] ) {
			update_post_meta( $produit_id, $meta, sanitize_textarea_field( $d[ $colonne ] ) );
		}
	}

	// Variations, une par option, au meme prix par defaut.
	if ( $variable ) {
		$label     = '' !== ( $d['variante_nom'] ?? '' ) ? $d['variante_nom'] : 'Couleur';
		$taxonomie = ayru_import_attribut( $label );
		$stock     = (int) ( $d['stock'] ?? 0 );
		foreach ( $variantes as $option ) {
			$slug_option = sanitize_title( $option );
			$deja = false;
			foreach ( wc_get_product( $produit_id )->get_children() as $enfant_id ) {
				$enfant = wc_get_product( $enfant_id );
				if ( $enfant && ( $enfant->get_attributes()[ $taxonomie ] ?? '' ) === $slug_option ) {
					$deja = true;
					break;
				}
			}
			if ( $deja ) {
				continue;
			}
			$variation = new WC_Product_Variation();
			$variation->set_parent_id( $produit_id );
			$variation->set_attributes( array( $taxonomie => $slug_option ) );
			$variation->set_regular_price( $d['prix'] ?? '' );
			if ( '' !== ( $d['prix_promo'] ?? '' ) ) {
				$variation->set_sale_price( $d['prix_promo'] );
			}
			$variation->set_manage_stock( true );
			$variation->set_stock_quantity( $stock );
			$variation->save();
		}
		WC_Product_Variable::sync( $produit_id );
	}

	if ( $existant ) {
		++$maj;
		WP_CLI::log( 'Mis a jour : ' . $nom );
	} else {
		++$cree;
		WP_CLI::log( 'Cree : ' . $nom );
	}
}

fclose( $flux );

WP_CLI::success( sprintf( '%d cree(s), %d mis a jour, %d ignore(s).', $cree, $maj, $ignore ) );
