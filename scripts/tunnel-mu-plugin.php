<?php
/**
 * Plugin Name: AYRU — Adaptation aux tunnels de démonstration
 * Description: Fait suivre l'URL du site à l'hôte réellement demandé, pour que le
 *              site s'affiche correctement derrière un tunnel Cloudflare.
 * Version:     1.1.0
 *
 * Pourquoi ce fichier existe.
 *
 * WordPress stocke son adresse en base (`siteurl` et `home`). En local elle vaut
 * `http://localhost:8080`. Derrière un tunnel, la page est servie depuis une
 * adresse publique en HTTPS, mais WordPress continue de fabriquer ses liens, ses
 * feuilles de style et ses images vers `localhost` : le visiteur distant reçoit
 * une page sans styles et sans images, avec des liens morts.
 *
 * Trois corrections sont nécessaires, et elles sont indissociables.
 *
 * 1. L'hôte. On réécrit `siteurl` et `home` à la volée à partir de l'en-tête
 *    `Host` reçu. Rien n'est écrit en base : l'URL du tunnel change à chaque
 *    session et le site local doit continuer de fonctionner sans lui.
 *
 * 2. Le protocole. Le tunnel termine le HTTPS de son côté et transmet la requête
 *    en HTTP à Apache. Sans correction, `is_ssl()` répond faux, WordPress génère
 *    des URL en `http://` sur une page servie en `https://`, et le navigateur
 *    bloque le tout en contenu mixte. On s'appuie sur `X-Forwarded-Proto`, que
 *    Cloudflare renseigne.
 *
 * 3. Les constantes d'URL. `WP_CONTENT_URL` est définie dans `wp-settings.php`
 *    à partir de la valeur brute en base, *avant* le chargement des mu-plugins :
 *    filtrer `option_siteurl` arrive donc trop tard pour elle. Résultat, les
 *    ressources du thème continuaient de pointer vers `localhost`. On rattrape
 *    cela sur les filtres d'URL que WordPress applique à chaque appel.
 *
 * Sécurité : l'en-tête `Host` est fourni par le client et ne doit jamais être
 * considéré comme fiable. On n'accepte que `localhost` et les domaines de tunnel
 * connus, et on ignore tout le reste. Ce fichier est un outil de démonstration :
 * il n'a rien à faire sur un hébergement de production.
 *
 * @package AYRU
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hôtes autorisés à piloter l'URL du site.
 *
 * @param string $hote Valeur brute de l'en-tête Host.
 * @return bool
 */
function ayru_tunnel_hote_autorise( string $hote ): bool {
	$hote = strtolower( $hote );

	// Un en-tête Host valide ne contient ni espace, ni barre oblique, ni retour ligne.
	if ( '' === $hote || preg_match( '#[^a-z0-9\.\-:]#', $hote ) ) {
		return false;
	}

	$sans_port = explode( ':', $hote )[0];

	foreach ( array( '.trycloudflare.com', '.cfargotunnel.com' ) as $suffixe ) {
		if ( str_ends_with( $sans_port, $suffixe ) ) {
			return true;
		}
	}

	return 'localhost' === $sans_port;
}

$ayru_hote = isset( $_SERVER['HTTP_HOST'] ) ? (string) $_SERVER['HTTP_HOST'] : '';

if ( ayru_tunnel_hote_autorise( $ayru_hote ) ) {

	// Le tunnel termine le TLS : sans cette ligne, WordPress se croit en clair.
	$ayru_transfere = isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] )
		? strtolower( (string) $_SERVER['HTTP_X_FORWARDED_PROTO'] )
		: '';
	if ( 'https' === $ayru_transfere ) {
		$_SERVER['HTTPS'] = 'on';
	}

	$ayru_schema = ( 'https' === $ayru_transfere ) ? 'https' : 'http';
	$ayru_racine = $ayru_schema . '://' . $ayru_hote;

	add_filter( 'option_siteurl', static fn() => $ayru_racine );
	add_filter( 'option_home', static fn() => $ayru_racine );

	/**
	 * Remplace la base enregistrée en base de données par l'hôte courant.
	 *
	 * Appliqué aux filtres d'URL de WordPress, il rattrape tout ce qui dérive de
	 * constantes figées trop tôt dans le chargement.
	 *
	 * @param string $url URL fabriquée par WordPress.
	 * @return string
	 */
	$ayru_reecrire = static function ( $url ) use ( $ayru_racine ) {
		if ( ! is_string( $url ) || '' === $url ) {
			return $url;
		}
		return preg_replace( '#^https?://localhost(:\d+)?#i', $ayru_racine, $url );
	};

	foreach ( array( 'content_url', 'plugins_url', 'theme_root_uri', 'includes_url', 'admin_url', 'site_url', 'home_url' ) as $ayru_filtre ) {
		add_filter( $ayru_filtre, $ayru_reecrire, 20 );
	}

	// Dernier filet : certaines extensions, dont WooCommerce pour ses blocs,
	// fabriquent l'URL de leurs scripts en contournant `plugins_url`. Ces deux
	// filtres s'appliquent juste avant l'écriture de la balise, donc à tout.
	add_filter( 'script_loader_src', $ayru_reecrire, 20 );
	add_filter( 'style_loader_src', $ayru_reecrire, 20 );

	// La médiathèque stocke une base absolue dans ses options.
	add_filter(
		'upload_dir',
		static function ( array $dirs ) use ( $ayru_reecrire ): array {
			foreach ( array( 'url', 'baseurl' ) as $cle ) {
				if ( isset( $dirs[ $cle ] ) ) {
					$dirs[ $cle ] = $ayru_reecrire( $dirs[ $cle ] );
				}
			}
			return $dirs;
		},
		20
	);

	// WooCommerce refuse le paiement hors HTTPS ; derrière le tunnel il l'est bien.
	add_filter( 'woocommerce_force_ssl_checkout', '__return_false' );
}
