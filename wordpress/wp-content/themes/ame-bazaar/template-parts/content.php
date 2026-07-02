<?php
/**
 * Default content template.
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
			<?php
			if ( is_singular() ) {
				the_title( '<h1 class="entry-title">', '</h1>' );
			} else {
				the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			}
			?>
		</header>

		<div class="entry-content">
			<?php
			if ( is_singular() ) {
				the_content();
			} else {
				the_excerpt();
			}
			?>
		</div>
	</div>
</article>
