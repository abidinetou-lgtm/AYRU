<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<main id="main-content" class="content-page section-shell">
	<section class="guides-hero"><div><p class="eyebrow">Guides & Conseils</p><h1>Des idées douces,<br>saison après saison.</h1><p>Des repères pratiques pour rendre votre intérieur plus doux et votre quotidien plus serein.</p></div><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/neb/neb-guide-book.webp' ); ?>" alt="NEB ouvre un guide"></section>
	<div class="section-heading"><h2>Tous nos guides</h2></div>
	<div class="guide-grid"><?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?><article class="guide-tile"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large' ); } ?><div><p class="eyebrow">Guide AYRU</p><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php the_excerpt(); ?><a class="text-link" href="<?php the_permalink(); ?>">Lire le guide <?php echo ayru_icon( 'arrow', 17 ); ?></a></div></article><?php endwhile; the_posts_pagination(); else : ?><div class="content-card"><h2>Les premiers guides sont en préparation</h2><p>Publiez vos contenus depuis “Guides & Conseils” dans l’administration WordPress.</p></div><?php endif; ?></div>
	<?php ayru_ad_slot( 'archive' ); ?>
</main>
<?php get_footer(); ?>
