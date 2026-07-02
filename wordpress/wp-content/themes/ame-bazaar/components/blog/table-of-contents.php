<?php
/**
 * Component: Blog Post Table of Contents
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$toc_html = ame_bazaar_generate_toc_html( get_the_content() );
if ( ! $toc_html ) {
	return;
}
?>
<div class="ame-blog-toc-wrapper" style="margin-bottom: 2rem;">
	<?php echo $toc_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
