<?php
/**
 * Plugin Name: AYRU Core
 * Description: Favoris, Liste AYRU, conformité produit et réglages durables de la boutique.
 * Version: 1.1.6
 * Requires PHP: 8.1
 * Text Domain: ayru-core
 */

defined( 'ABSPATH' ) || exit;

final class AYRU_Core {
const VERSION = '1.1.6';
	const FAVORITES_META = '_ayru_favorites';

	public static function init(): void {
		add_action( 'init', array( __CLASS__, 'register_list_type' ) );
		add_action( 'init', array( __CLASS__, 'register_guide_type' ) );
		add_action( 'init', array( __CLASS__, 'register_subscriber_type' ) );
		add_action( 'init', array( __CLASS__, 'register_rewrite' ) );
		add_action( 'wp_ajax_ayru_toggle_favorite', array( __CLASS__, 'toggle_favorite' ) );
		add_action( 'wp_ajax_nopriv_ayru_toggle_favorite', array( __CLASS__, 'toggle_favorite' ) );
		add_action( 'wp_ajax_ayru_create_list', array( __CLASS__, 'create_list' ) );
		add_action( 'wp_ajax_ayru_add_to_list', array( __CLASS__, 'add_to_list' ) );
		add_action( 'admin_post_ayru_contact', array( __CLASS__, 'handle_contact' ) );
		add_action( 'admin_post_nopriv_ayru_contact', array( __CLASS__, 'handle_contact' ) );
		add_action( 'admin_post_ayru_newsletter', array( __CLASS__, 'handle_newsletter' ) );
		add_action( 'admin_post_nopriv_ayru_newsletter', array( __CLASS__, 'handle_newsletter' ) );
		add_shortcode( 'ayru_favorites', array( __CLASS__, 'favorites_shortcode' ) );
		add_shortcode( 'ayru_birth_list', array( __CLASS__, 'birth_list_shortcode' ) );
		add_shortcode( 'ayru_order_tracking', array( __CLASS__, 'tracking_shortcode' ) );
		add_shortcode( 'ayru_contact', array( __CLASS__, 'contact_shortcode' ) );
		add_filter( 'query_vars', array( __CLASS__, 'query_vars' ) );
		add_filter( 'the_content', array( __CLASS__, 'shared_list_content' ) );
		add_action( 'woocommerce_product_options_general_product_data', array( __CLASS__, 'product_compliance_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( __CLASS__, 'save_product_compliance_fields' ) );
		add_action( 'woocommerce_order_status_processing', array( __CLASS__, 'record_list_purchases' ), 20 );
		add_action( 'woocommerce_order_status_completed', array( __CLASS__, 'record_list_purchases' ), 20 );
		add_filter( 'woocommerce_add_cart_item_data', array( __CLASS__, 'attach_list_to_cart_item' ), 10, 3 );
		add_action( 'woocommerce_checkout_create_order_line_item', array( __CLASS__, 'attach_list_to_order_item' ), 10, 4 );
	}

	public static function activate(): void {
		self::register_list_type();
		self::register_guide_type();
		self::register_subscriber_type();
		self::register_rewrite();
		$pages = array(
			'favoris' => array( 'Mes favoris', '[ayru_favorites]' ),
			'ma-liste' => array( 'Ma Liste AYRU', '[ayru_birth_list]' ),
			'suivi-commande' => array( 'Suivi de commande', '[ayru_order_tracking]' ),
			'contact' => array( 'Nous contacter', '[ayru_contact]' ),
			'faq' => array( 'Questions fréquentes', '<!-- wp:paragraph --><p>Contenu à compléter à partir des questions réelles des clientes.</p><!-- /wp:paragraph -->' ),
			'livraison-retours' => array( 'Livraison & retours', '<!-- wp:paragraph --><p>Politique à compléter avec les zones, tarifs, délais et conditions réellement appliqués.</p><!-- /wp:paragraph -->' ),
			'notre-histoire' => array( 'Notre histoire', '<!-- wp:paragraph --><p>Présentez ici l’histoire vérifiée d’AYRU et ses engagements réels.</p><!-- /wp:paragraph -->' ),
			'mentions-legales' => array( 'Mentions légales', '<!-- wp:paragraph --><p>Contenu juridique à faire compléter et valider avant la mise en production.</p><!-- /wp:paragraph -->' ),
			'cgv' => array( 'Conditions générales de vente', '<!-- wp:paragraph --><p>CGV à faire rédiger ou valider pour l’activité réelle avant toute vente.</p><!-- /wp:paragraph -->' ),
			'confidentialite' => array( 'Politique de confidentialité', '<!-- wp:paragraph --><p>Politique à compléter selon les traitements, prestataires et durées de conservation réellement utilisés.</p><!-- /wp:paragraph -->' ),
			'cookies' => array( 'Gestion des cookies', '<!-- wp:paragraph --><p>La liste et la gestion des cookies seront à configurer selon les outils réellement activés.</p><!-- /wp:paragraph -->' ),
		);
		foreach ( $pages as $slug => $page ) {
			if ( ! get_page_by_path( $slug ) ) {
				wp_insert_post( array( 'post_title' => $page[0], 'post_name' => $slug, 'post_content' => $page[1], 'post_status' => 'publish', 'post_type' => 'page' ) );
			}
		}
		flush_rewrite_rules();
	}

	public static function register_list_type(): void {
		register_post_type( 'ayru_list', array(
			'labels' => array( 'name' => 'Listes AYRU', 'singular_name' => 'Liste AYRU' ),
			'public' => false, 'show_ui' => true, 'show_in_rest' => false, 'supports' => array( 'title', 'author' ),
			'capability_type' => 'post', 'map_meta_cap' => true,
		) );
	}

	public static function register_guide_type(): void {
		register_post_type( 'guide', array(
			'labels' => array(
				'name' => 'Guides & Conseils', 'singular_name' => 'Guide', 'add_new_item' => 'Ajouter un guide',
				'edit_item' => 'Modifier le guide', 'all_items' => 'Tous les guides',
			),
			'public' => true, 'show_in_rest' => true, 'has_archive' => 'guides', 'rewrite' => array( 'slug' => 'guides' ),
			'menu_icon' => 'dashicons-book-alt', 'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
			'taxonomies' => array( 'category' ),
		) );
	}

	public static function register_subscriber_type(): void {
		register_post_type( 'ayru_subscriber', array(
			'labels' => array( 'name' => 'Inscrits newsletter', 'singular_name' => 'Inscrit newsletter' ),
			'public' => false, 'show_ui' => true, 'supports' => array( 'title' ), 'menu_icon' => 'dashicons-email-alt',
		) );
	}

	public static function register_rewrite(): void {
		add_rewrite_rule( '^liste-partagee/([a-zA-Z0-9-]+)/?$', 'index.php?pagename=ma-liste&ayru_share=$matches[1]', 'top' );
	}

	public static function query_vars( array $vars ): array { $vars[] = 'ayru_share'; return $vars; }

	private static function favorites(): array {
		if ( is_user_logged_in() ) {
			return array_map( 'absint', (array) get_user_meta( get_current_user_id(), self::FAVORITES_META, true ) );
		}
		$cookie = isset( $_COOKIE['ayru_favorites'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['ayru_favorites'] ) ) : '';
		return array_filter( array_map( 'absint', explode( ',', $cookie ) ) );
	}

	public static function is_favorite( int $product_id ): bool {
		return in_array( $product_id, self::favorites(), true );
	}

	public static function toggle_favorite(): void {
		check_ajax_referer( 'ayru_favorite', 'nonce' );
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) { wp_send_json_error( array( 'message' => 'Produit invalide.' ), 400 ); }
		$favorites = self::favorites();
		if ( in_array( $product_id, $favorites, true ) ) { $favorites = array_values( array_diff( $favorites, array( $product_id ) ) ); $active = false; } else { $favorites[] = $product_id; $favorites = array_values( array_unique( $favorites ) ); $active = true; }
		if ( is_user_logged_in() ) { update_user_meta( get_current_user_id(), self::FAVORITES_META, $favorites ); } else { setcookie( 'ayru_favorites', implode( ',', $favorites ), array( 'expires' => time() + MONTH_IN_SECONDS, 'path' => COOKIEPATH ?: '/', 'secure' => is_ssl(), 'httponly' => true, 'samesite' => 'Lax' ) ); }
		wp_send_json_success( array( 'active' => $active, 'count' => count( $favorites ) ) );
	}

	public static function favorites_shortcode(): string {
		ob_start();
		$ids = self::favorites();
		if ( function_exists( 'wc_get_products' ) && $ids ) {
			echo '<div class="ayru-product-grid">';
			foreach ( wc_get_products( array( 'include' => $ids, 'limit' => -1 ) ) as $product ) { $GLOBALS['product'] = $product; get_template_part( 'template-parts/product-card' ); }
			echo '</div>';
		} else { get_template_part( 'template-parts/empty-state', null, array( 'type' => 'favorites' ) ); }
		return (string) ob_get_clean();
	}

	private static function user_list( int $user_id ): ?WP_Post {
		$lists = get_posts( array( 'post_type' => 'ayru_list', 'author' => $user_id, 'post_status' => array( 'private', 'publish' ), 'posts_per_page' => 1 ) );
		return $lists ? $lists[0] : null;
	}

	public static function create_list(): void {
		check_ajax_referer( 'ayru_list', 'nonce' );
		if ( ! is_user_logged_in() ) { wp_send_json_error( array( 'message' => 'Connectez-vous pour créer votre liste.' ), 401 ); }
		if ( self::user_list( get_current_user_id() ) ) { wp_send_json_error( array( 'message' => 'Votre liste existe déjà.' ), 409 ); }
		$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : 'Ma Liste AYRU';
		$id = wp_insert_post( array( 'post_type' => 'ayru_list', 'post_title' => $name ?: 'Ma Liste AYRU', 'post_status' => 'publish', 'post_author' => get_current_user_id() ) );
		if ( is_wp_error( $id ) ) { wp_send_json_error( array( 'message' => 'La liste n’a pas pu être créée.' ), 500 ); }
		update_post_meta( $id, '_ayru_share_token', wp_generate_password( 20, false, false ) );
		update_post_meta( $id, '_ayru_list_items', array() );
		wp_send_json_success( array( 'reload' => true ) );
	}

	public static function add_to_list(): void {
		check_ajax_referer( 'ayru_list', 'nonce' );
		if ( ! is_user_logged_in() ) { wp_send_json_error( array( 'message' => 'Connectez-vous pour utiliser Ma Liste AYRU.' ), 401 ); }
		$product_id = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
		$list = self::user_list( get_current_user_id() );
		if ( ! $list || 'product' !== get_post_type( $product_id ) ) { wp_send_json_error( array( 'message' => 'Créez d’abord votre liste.' ), 400 ); }
		$items = (array) get_post_meta( $list->ID, '_ayru_list_items', true );
		$items[ $product_id ] = array( 'quantity' => max( 1, (int) ( $items[ $product_id ]['quantity'] ?? 1 ) ), 'purchased' => (int) ( $items[ $product_id ]['purchased'] ?? 0 ) );
		update_post_meta( $list->ID, '_ayru_list_items', $items );
		wp_send_json_success( array( 'message' => 'Ajouté à votre liste.' ) );
	}

	public static function birth_list_shortcode(): string {
		$share = get_query_var( 'ayru_share' );
		if ( $share ) { return self::render_shared_list( sanitize_text_field( $share ) ); }
		ob_start();
		echo '<section class="ayru-list-hero"><div><p class="eyebrow">Ma Liste AYRU</p><h2>Préparez l’arrivée de bébé sereinement.</h2><p>Ajoutez vos coups de cœur, organisez vos envies et partagez votre liste avec vos proches.</p></div><img src="' . esc_url( get_template_directory_uri() . '/assets/images/neb/neb-birth-list.webp' ) . '" alt="NEB présente une liste"></section>';
		if ( ! is_user_logged_in() ) { $account_url = function_exists( 'ayru_account_url' ) ? ayru_account_url( get_permalink() ) : wp_login_url( get_permalink() ); echo '<div class="ayru-list-gate"><h2>Votre liste vous suit partout</h2><p>Un compte est nécessaire pour conserver et partager votre liste en toute sécurité.</p><a class="button button--primary" href="' . esc_url( $account_url ) . '">Se connecter ou créer un compte</a></div>'; return (string) ob_get_clean(); }
		$list = self::user_list( get_current_user_id() );
		if ( ! $list ) { echo '<form class="ayru-list-create" data-ayru-list-create><label for="list-name">Donnez un nom à votre liste</label><input id="list-name" name="name" value="Ma liste de naissance"><input type="hidden" name="nonce" value="' . esc_attr( wp_create_nonce( 'ayru_list' ) ) . '"><button class="button button--primary">Créer ma liste</button><p class="form-message" aria-live="polite"></p></form>'; } else { self::render_owner_list( $list ); }
		return (string) ob_get_clean();
	}

	private static function render_owner_list( WP_Post $list ): void {
		$items = (array) get_post_meta( $list->ID, '_ayru_list_items', true );
		$token = (string) get_post_meta( $list->ID, '_ayru_share_token', true );
		$link = home_url( '/liste-partagee/' . $token . '/' );
		echo '<div class="list-owner-heading"><div><p class="eyebrow">Votre liste</p><h2>' . esc_html( $list->post_title ) . '</h2></div><button class="button button--secondary" type="button" data-copy="' . esc_url( $link ) . '">' . ( function_exists( 'ayru_icon' ) ? ayru_icon( 'share', 18 ) : '' ) . ' Copier le lien de partage</button></div>';
		self::render_list_items( $items, true, $list->ID );
	}

	private static function render_shared_list( string $token ): string {
		$lists = get_posts( array( 'post_type' => 'ayru_list', 'post_status' => array( 'private', 'publish' ), 'meta_key' => '_ayru_share_token', 'meta_value' => $token, 'posts_per_page' => 1 ) );
		if ( ! $lists ) { return '<div class="empty-state"><h2>Cette liste n’est pas disponible</h2><p>Le lien a peut-être été modifié ou rendu privé.</p></div>'; }
		ob_start(); echo '<div class="list-owner-heading"><div><p class="eyebrow">Liste partagée</p><h2>' . esc_html( $lists[0]->post_title ) . '</h2><p>Choisissez un cadeau disponible. Un article n’est compté comme acheté qu’après paiement confirmé.</p></div></div>'; self::render_list_items( (array) get_post_meta( $lists[0]->ID, '_ayru_list_items', true ), false, $lists[0]->ID ); return (string) ob_get_clean();
	}

	private static function render_list_items( array $items, bool $owner, int $list_id ): void {
		if ( ! $items || ! function_exists( 'wc_get_product' ) ) { echo '<div class="empty-state"><h2>Votre liste attend ses premiers coups de cœur</h2><p>Ajoutez des produits depuis leur fiche.</p></div>'; return; }
		echo '<div class="ayru-list-items">';
		foreach ( $items as $product_id => $data ) { $product = wc_get_product( $product_id ); if ( ! $product ) { continue; } $remaining = max( 0, (int) $data['quantity'] - (int) $data['purchased'] ); echo '<article class="list-item">' . $product->get_image( 'woocommerce_thumbnail' ) . '<div><h3><a href="' . esc_url( $product->get_permalink() ) . '">' . esc_html( $product->get_name() ) . '</a></h3><p>' . wp_kses_post( $product->get_price_html() ) . '</p><small>' . esc_html( $remaining ) . ' encore souhaité(s)</small></div>'; if ( ! $owner && $remaining && $product->is_purchasable() && $product->is_in_stock() ) { echo '<a class="button button--primary" href="' . esc_url( add_query_arg( array( 'add-to-cart' => $product_id, 'ayru_list_id' => $list_id ), $product->get_permalink() ) ) . '">Offrir ce produit</a>'; } echo '</article>'; }
		echo '</div>';
	}

	public static function shared_list_content( string $content ): string { return $content; }

	public static function tracking_shortcode(): string {
		if ( class_exists( 'WC_Shortcode_Order_Tracking' ) ) { ob_start(); echo '<div class="tracking-intro"><img src="' . esc_url( get_template_directory_uri() . '/assets/images/neb/neb-order-tracking.webp' ) . '" alt="NEB suit un colis"><div><h2>Suivez votre commande</h2><p>Renseignez les informations reçues dans votre email de confirmation.</p></div></div>'; WC_Shortcode_Order_Tracking::output( array() ); return (string) ob_get_clean(); }
		return '<p>WooCommerce doit être activé pour suivre une commande.</p>';
	}

	public static function contact_shortcode(): string {
		$status = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : '';
		ob_start();
		if ( 'sent' === $status ) { echo '<div class="woocommerce-message" role="status">Votre message a bien été envoyé. L’équipe AYRU vous répondra dès que possible.</div>'; }
		if ( 'invalid' === $status ) { echo '<ul class="woocommerce-error" role="alert"><li>Vérifiez votre email et saisissez un message d’au moins 10 caractères.</li></ul>'; }
		if ( 'rate' === $status ) { echo '<ul class="woocommerce-error" role="alert"><li>Veuillez patienter une minute avant un nouvel envoi.</li></ul>'; }
		if ( 'error' === $status ) { echo '<ul class="woocommerce-error" role="alert"><li>Le message n’a pas pu être transmis. Réessayez ou utilisez l’adresse de support affichée sur le site.</li></ul>'; }
		echo '<div class="contact-layout"><div><h2>Une question ?</h2><p>Écrivez-nous en précisant autant que possible votre demande. Pour une commande, indiquez son numéro sans communiquer de données bancaires.</p></div><form class="ayru-contact-form" method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '"><input type="hidden" name="action" value="ayru_contact">';
		wp_nonce_field( 'ayru_contact', 'ayru_contact_nonce' );
		echo '<p class="ayru-honeypot" aria-hidden="true"><label>Ne pas remplir<input name="website" tabindex="-1" autocomplete="off"></label></p><p><label for="contact-name">Votre nom</label><input id="contact-name" name="name" required autocomplete="name"></p><p><label for="contact-email">Votre email</label><input id="contact-email" name="email" type="email" required autocomplete="email"></p><p><label for="contact-subject">Sujet</label><select id="contact-subject" name="subject"><option>Question produit</option><option>Commande</option><option>Livraison ou retour</option><option>Ma Liste AYRU</option><option>Autre demande</option></select></p><p><label for="contact-message">Votre message</label><textarea id="contact-message" name="message" rows="7" required></textarea></p><button class="button button--primary" type="submit">Envoyer mon message</button></form></div>';
		return (string) ob_get_clean();
	}

	public static function handle_contact(): void {
		if ( ! isset( $_POST['ayru_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ayru_contact_nonce'] ) ), 'ayru_contact' ) ) { wp_die( 'Requête expirée.', 403 ); }
		if ( ! empty( $_POST['website'] ) ) { wp_safe_redirect( home_url( '/contact/?contact=sent' ) ); exit; }
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$name = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : 'Demande';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
		if ( ! is_email( $email ) || mb_strlen( $message ) < 10 ) { wp_safe_redirect( home_url( '/contact/?contact=invalid' ) ); exit; }
		$rate_key = 'ayru_contact_' . hash( 'sha256', (string) ( $_SERVER['REMOTE_ADDR'] ?? '' ) );
		if ( get_transient( $rate_key ) ) { wp_safe_redirect( home_url( '/contact/?contact=rate' ) ); exit; }
		set_transient( $rate_key, 1, MINUTE_IN_SECONDS );
		$recipient = get_theme_mod( 'ayru_support_email' ) ?: get_option( 'admin_email' );
		$sent = wp_mail( $recipient, '[AYRU] ' . $subject, "Nom : {$name}\nEmail : {$email}\n\n{$message}", array( 'Reply-To: ' . $name . ' <' . $email . '>' ) );
		wp_safe_redirect( home_url( '/contact/?contact=' . ( $sent ? 'sent' : 'error' ) ) ); exit;
	}

	public static function handle_newsletter(): void {
		if ( ! isset( $_POST['ayru_newsletter_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ayru_newsletter_nonce'] ) ), 'ayru_newsletter' ) ) { wp_die( 'Requête expirée.', 403 ); }
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$consent = ! empty( $_POST['consent'] );
		if ( ! is_email( $email ) || ! $consent ) { wp_safe_redirect( add_query_arg( 'newsletter', 'invalid', wp_get_referer() ?: home_url( '/' ) ) ); exit; }
		$email_hash = hash( 'sha256', strtolower( $email ) );
		$existing = get_posts( array( 'post_type' => 'ayru_subscriber', 'post_status' => array( 'publish', 'private' ), 'meta_key' => '_ayru_email_hash', 'meta_value' => $email_hash, 'posts_per_page' => 1 ) );
		if ( ! $existing ) {
			$id = wp_insert_post( array( 'post_type' => 'ayru_subscriber', 'post_status' => 'publish', 'post_title' => $email ) );
			if ( $id && ! is_wp_error( $id ) ) { update_post_meta( $id, '_ayru_email_hash', $email_hash ); update_post_meta( $id, '_ayru_consent_at', gmdate( 'c' ) ); update_post_meta( $id, '_ayru_consent_source', esc_url_raw( wp_get_referer() ) ); }
		}
		wp_safe_redirect( add_query_arg( 'newsletter', 'ok', wp_get_referer() ?: home_url( '/' ) ) ); exit;
	}

	public static function product_compliance_fields(): void {
		foreach ( array( '_ayru_manufacturer' => 'Fabricant', '_ayru_eu_responsible' => 'Responsable UE', '_ayru_age_warning' => 'Âge / avertissements', '_ayru_safety_info' => 'Informations de sécurité', '_ayru_product_reference' => 'Référence produit' ) as $id => $label ) {
			woocommerce_wp_textarea_input( array( 'id' => $id, 'label' => $label, 'description' => 'Afficher uniquement des informations fournisseur vérifiées.', 'desc_tip' => true ) );
		}
	}

	public static function attach_list_to_cart_item( array $cart_item_data, int $product_id, int $variation_id ): array {
		$list_id = isset( $_REQUEST['ayru_list_id'] ) ? absint( $_REQUEST['ayru_list_id'] ) : 0;
		if ( ! $list_id || 'ayru_list' !== get_post_type( $list_id ) ) { return $cart_item_data; }
		$items = (array) get_post_meta( $list_id, '_ayru_list_items', true );
		if ( ! isset( $items[ $product_id ] ) ) { return $cart_item_data; }
		$cart_item_data['_ayru_list_id'] = $list_id;
		return $cart_item_data;
	}

	public static function attach_list_to_order_item( WC_Order_Item_Product $item, string $cart_item_key, array $values, WC_Order $order ): void {
		if ( ! empty( $values['_ayru_list_id'] ) ) { $item->add_meta_data( '_ayru_list_id', absint( $values['_ayru_list_id'] ), true ); }
	}

	public static function save_product_compliance_fields( int $post_id ): void {
		foreach ( array( '_ayru_manufacturer', '_ayru_eu_responsible', '_ayru_age_warning', '_ayru_safety_info', '_ayru_product_reference' ) as $key ) { if ( isset( $_POST[ $key ] ) ) { update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) ); } }
	}

	public static function record_list_purchases( int $order_id ): void {
		$order = wc_get_order( $order_id );
		if ( ! $order || $order->get_meta( '_ayru_list_recorded' ) ) { return; }
		$updated = false;
		foreach ( $order->get_items() as $item ) { $list_id = absint( $item->get_meta( '_ayru_list_id' ) ); if ( ! $list_id ) { continue; } $items = (array) get_post_meta( $list_id, '_ayru_list_items', true ); $product_id = $item->get_product_id(); if ( isset( $items[ $product_id ] ) ) { $items[ $product_id ]['purchased'] = min( (int) $items[ $product_id ]['quantity'], (int) $items[ $product_id ]['purchased'] + $item->get_quantity() ); update_post_meta( $list_id, '_ayru_list_items', $items ); $updated = true; } }
		if ( $updated ) { $order->update_meta_data( '_ayru_list_recorded', gmdate( 'c' ) ); $order->save(); }
	}
}

register_activation_hook( __FILE__, array( 'AYRU_Core', 'activate' ) );
AYRU_Core::init();
