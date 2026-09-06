<?php
defined( 'ABSPATH' ) || exit;
global $product;
if ( ! $product instanceof WC_Product ) { return; }
$product_id = $product->get_id();
$is_favorite = class_exists( 'AYRU_Core' ) && AYRU_Core::is_favorite( $product_id );
$element = isset( $args['element'] ) && 'li' === $args['element'] ? 'li' : 'article';
?>
<<?php echo esc_attr( $element ); ?> <?php wc_product_class( 'product-card', $product ); ?>>
	<div class="product-card__media">
		<a href="<?php echo esc_url( $product->get_permalink() ); ?>" aria-label="<?php echo esc_attr( $product->get_name() ); ?>">
			<?php if ( $product->get_image_id() ) { echo wp_get_attachment_image( $product->get_image_id(), 'ayru-product-card', false, array( 'loading' => 'lazy' ) ); } else { ?><span class="product-image-placeholder"><span>Photo produit<br>à fournir</span></span><?php } ?>
		</a>
		<?php if ( $product->is_on_sale() ) : ?><span class="product-badge">Promo</span><?php elseif ( strtotime( $product->get_date_created() ) > strtotime( '-30 days' ) ) : ?><span class="product-badge">Nouveau</span><?php endif; ?>
		<button class="favorite-button" type="button" data-favorite-product="<?php echo esc_attr( $product_id ); ?>" data-label-add="Ajouter aux favoris" data-label-remove="Retirer des favoris" aria-label="<?php echo esc_attr( ( $is_favorite ? 'Retirer des favoris : ' : 'Ajouter aux favoris : ' ) . $product->get_name() ); ?>" aria-pressed="<?php echo $is_favorite ? 'true' : 'false'; ?>"><?php echo ayru_icon( 'heart', 19 ); ?></button>
	</div>
	<div class="product-card__body"><h3><a href="<?php echo esc_url( $product->get_permalink() ); ?>"><?php echo esc_html( $product->get_name() ); ?></a></h3><div class="product-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div><div class="product-card__actions"><?php if ( $product->is_in_stock() ) { woocommerce_template_loop_add_to_cart(); } else { ?><span class="stock-out">Rupture de stock</span><?php } ?></div></div>
</<?php echo esc_attr( $element ); ?>>
