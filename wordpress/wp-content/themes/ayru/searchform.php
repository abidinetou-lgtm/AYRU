<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="site-search-field">Rechercher</label><?php echo ayru_icon( 'search', 20 ); ?><input id="site-search-field" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Rechercher un produit, une catégorie, un guide…"><button type="submit" class="button button--primary">Rechercher</button>
</form>

