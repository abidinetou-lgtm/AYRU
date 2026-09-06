<?php
/**
 * Bandeau de consentement aux traceurs.
 *
 * Rendu cote serveur mais masque par defaut ; le script l'affiche uniquement
 * si aucun choix n'a encore ete enregistre. Les traceurs publicitaires ne sont
 * jamais deposes avant un consentement explicite.
 *
 * @package AYRU
 */
defined( 'ABSPATH' ) || exit;
?>
<section class="ayru-consent" id="ayru-consent" role="dialog" aria-labelledby="ayru-consent-title" aria-describedby="ayru-consent-text" hidden>
	<h2 id="ayru-consent-title">Un mot sur les cookies</h2>
	<p id="ayru-consent-text">AYRU utilise des cookies de mesure d’audience et de publicité pour comprendre ce qui vous plaît. Vous pouvez refuser : le site fonctionne exactement pareil. <a href="<?php echo esc_url( home_url( '/cookies/' ) ); ?>">En savoir plus</a></p>
	<div class="ayru-consent__actions">
		<button class="button button--secondary" type="button" data-consent="refuse">Refuser</button>
		<button class="button button--primary" type="button" data-consent="accept">Accepter</button>
	</div>
</section>
