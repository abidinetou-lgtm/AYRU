<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<main id="main-content" class="content-page section-shell">
	<header class="page-heading"><p class="eyebrow">AYRU</p><h1><?php single_post_title(); ?></h1></header>
	<div class="content-grid"><?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?><article <?php post_class( 'content-card' ); ?>><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php the_excerpt(); ?></article><?php endwhile; the_posts_pagination(); else : get_template_part( 'template-parts/empty-state', null, array( 'type' => 'search' ) ); endif; ?></div>
</main>
<?php get_footer(); ?>

