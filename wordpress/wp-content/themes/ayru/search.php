<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<main id="main-content" class="content-page section-shell"><header class="page-heading"><p class="eyebrow">Recherche</p><h1><?php printf( esc_html__( 'Résultats pour « %s »', 'ayru' ), esc_html( get_search_query() ) ); ?></h1></header><?php if ( have_posts() ) : ?><div class="content-grid"><?php while ( have_posts() ) : the_post(); ?><article class="content-card"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php the_excerpt(); ?></article><?php endwhile; ?></div><?php else : get_template_part( 'template-parts/empty-state', null, array( 'type' => 'search' ) ); endif; ?></main>
<?php get_footer(); ?>

