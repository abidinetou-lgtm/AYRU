<?php
/**
 * AYRU home page.
 *
 * @package AYRU
 */
defined( 'ABSPATH' ) || exit;
get_header();
$theme_uri = get_template_directory_uri();
$shop_url  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/boutique/' );
$categories = array(
	array( 'label' => 'Maison & Cosy', 'slug' => 'maison-cosy', 'icon' => 'home' ),
	array( 'label' => 'Déco murale', 'slug' => 'deco-murale', 'icon' => 'sparkle' ),
	array( 'label' => 'Pyjamas & Détente', 'slug' => 'pyjamas-detente', 'icon' => 'heart' ),
	array( 'label' => 'Papeterie', 'slug' => 'papeterie', 'icon' => 'book' ),
	array( 'label' => 'Cadeaux', 'slug' => 'cadeaux', 'icon' => 'gift' ),
	array( 'label' => 'Tendances', 'slug' => 'tendances', 'icon' => 'star' ),
);
?>
<main id="main-content">
	<section class="home-hero">
		<div class="home-hero__image" role="img" aria-label="NEB installé dans un lit douillet aux tons lilas et crème"></div>
		<div class="home-hero__content section-shell">
			<p class="eyebrow">Doux aujourd’hui<br>pour demain</p>
			<h1>Votre <em>cocon</em><br>commence ici</h1>
			<p class="hero-lead">De la déco, du linge tout doux et des petites attentions<br class="desktop-only"> pour votre intérieur et pour vous.</p>
			<div class="button-row"><a class="button button--primary" href="<?php echo esc_url( $shop_url ); ?>">Découvrir la boutique <?php echo ayru_icon( 'arrow', 18 ); ?></a><a class="button button--secondary" href="#univers">Explorer nos univers</a></div>
		</div>
	</section>

	<nav class="category-shortcuts section-shell" aria-label="Explorer les univers AYRU">
		<?php foreach ( $categories as $category ) :
			$url = ! empty( $category['page'] ) ? home_url( '/' . $category['slug'] . '/' ) : home_url( '/categorie-produit/' . $category['slug'] . '/' ); ?>
			<a href="<?php echo esc_url( $url ); ?>"><span class="shortcut-icon"><?php echo ayru_icon( in_array( $category['icon'], array( 'home', 'heart', 'gift', 'book' ), true ) ? $category['icon'] : 'sparkle', 28 ); ?></span><span><?php echo esc_html( $category['label'] ); ?></span></a>
		<?php endforeach; ?>
	</nav>

	<div class="home-commerce-grid section-shell">
		<div class="home-main-column">
			<?php ayru_render_product_rail( 'Nouveautés', 'recent', 5 ); ?>
			<section class="universe-section" id="univers" aria-labelledby="univers-title">
				<div class="universe-intro"><p class="eyebrow">Nos univers</p><h2 id="univers-title">Tout pour un intérieur<br>qui vous ressemble.</h2><a class="button button--primary" href="<?php echo esc_url( $shop_url ); ?>">Explorer nos univers <?php echo ayru_icon( 'arrow', 18 ); ?></a></div>
				<a class="universe-card universe-card--room" href="<?php echo esc_url( home_url( '/categorie-produit/maison-cosy/' ) ); ?>"><span>Maison & Cosy</span><?php echo ayru_icon( 'arrow', 18 ); ?></a>
				<a class="universe-card universe-card--plush" href="<?php echo esc_url( home_url( '/categorie-produit/pyjamas-detente/' ) ); ?>"><img src="<?php echo esc_url( $theme_uri . '/assets/images/neb/NEB_06_love.webp' ); ?>" alt="" loading="lazy"><span>Pyjamas & Détente</span><?php echo ayru_icon( 'arrow', 18 ); ?></a>
			</section>
			<section class="editorial-banner sleep-banner"><div><p class="eyebrow">Sélection cocooning</p><h2>Des soirées douces,<br>des réveils tranquilles.</h2><p>Une sélection pensée pour ralentir le soir et se sentir bien chez soi.</p><a class="button button--primary" href="<?php echo esc_url( home_url( '/categorie-produit/maison-cosy/' ) ); ?>">Découvrir la sélection <?php echo ayru_icon( 'arrow', 18 ); ?></a></div><img src="<?php echo esc_url( $theme_uri . '/assets/images/neb/neb-sleeping-bed.webp' ); ?>" alt="NEB endormi sous une couverture lilas" loading="lazy"></section>
			<?php ayru_render_product_rail( 'Tendances du moment ✦', 'featured', 5 ); ?>
		</div>
		<aside class="home-side-column">
			<div class="side-note"><span>Des petites choses<br>pour de grands moments…</span><img src="<?php echo esc_url( $theme_uri . '/assets/images/neb/NEB_01_coucou.webp' ); ?>" alt="" loading="lazy"></div>
			<section class="birth-list-card"><div><p class="eyebrow">Ma Liste AYRU</p><h2>Vos envies, <em>rassemblées.</em></h2><p>Créez votre liste, ajoutez vos coups de cœur et partagez-la avec vos proches.</p><a class="button button--primary" href="<?php echo esc_url( home_url( '/ma-liste/' ) ); ?>">Créer ma liste <?php echo ayru_icon( 'arrow', 18 ); ?></a></div><img src="<?php echo esc_url( $theme_uri . '/assets/images/neb/neb-wish-list.webp' ); ?>" alt="NEB présente une liste de naissance" loading="lazy"></section>
			<section class="reassurance-card" aria-label="Services AYRU à finaliser"><div><?php echo ayru_icon( 'truck', 27 ); ?><span>Livraison<br><small>modalités à renseigner</small></span></div><div><?php echo ayru_icon( 'lock', 27 ); ?><span>Paiement<br><small>solution à connecter</small></span></div><div><?php echo ayru_icon( 'box', 27 ); ?><span>Retours<br><small>politique à valider</small></span></div><div><?php echo ayru_icon( 'headset', 27 ); ?><span>Support<br><small>contact à renseigner</small></span></div></section>
			<section class="guide-card"><div><p class="eyebrow">Guides & Conseils</p><h2>NEB vous souffle de bonnes idées</h2><p>Des repères utiles et des sélections pensées pour un quotidien plus doux.</p><a class="button button--primary" href="<?php echo esc_url( home_url( '/guides/' ) ); ?>">Découvrir nos guides <?php echo ayru_icon( 'arrow', 18 ); ?></a></div><img src="<?php echo esc_url( $theme_uri . '/assets/images/neb/neb-guide-book.webp' ); ?>" alt="NEB avec un guide" loading="lazy"></section>
		</aside>
	</div>

	<section class="community section-shell"><div><p class="eyebrow">La communauté AYRU</p><h2>Un quotidien<br>plus doux.</h2><p>Retrouvez nos nouveautés et nos conseils sur les réseaux.</p></div><img src="<?php echo esc_url( $theme_uri . '/assets/images/neb/NEB_02_content.webp' ); ?>" alt="NEB souriant" loading="lazy"><a class="button button--secondary" href="#footer-newsletter">Rejoindre l’univers AYRU</a></section>
</main>
<?php get_footer(); ?>
