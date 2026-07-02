<?php
/**
 * Search content template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'ame-bazaar-card' ); ?>>
	<div class="ame-bazaar-card__body">
		<header class="entry-header">
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		</header>

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div>
	</div>
</article>
