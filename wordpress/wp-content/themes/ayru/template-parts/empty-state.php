<?php
defined( 'ABSPATH' ) || exit;
$type = $args['type'] ?? 'search';
$states = array(
	'cart' => array( 'neb-shopping-cart.webp', 'Votre panier est vide', 'Mais de belles découvertes vous attendent !' ),
	'favorites' => array( 'neb-favorites-heart.webp', 'Aucun favori pour le moment', 'Gardez ici toutes vos jolies découvertes.' ),
	'orders' => array( 'empty-orders.webp', 'Aucune commande', 'Votre première aventure AYRU commencera ici.' ),
	'search' => array( 'search-empty.webp', 'Aucun résultat', 'Essayez un autre mot ou explorez nos univers.' ),
	'shop' => array( 'neb-searching.webp', 'Rien à afficher ici pour l’instant', 'Cet univers se remplit bientôt. En attendant, il y a de jolies choses à côté.' ),
);
$state = $states[ $type ] ?? $states['search'];
?>
<div class="empty-state"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/neb/' . $state[0] ); ?>" alt="" loading="lazy"><h2><?php echo esc_html( $state[1] ); ?></h2><p><?php echo esc_html( $state[2] ); ?></p><a class="button button--primary" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/boutique/' ) ); ?>">Explorer nos univers <?php echo ayru_icon( 'arrow', 18 ); ?></a></div>
