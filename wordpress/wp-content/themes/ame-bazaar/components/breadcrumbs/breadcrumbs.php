<?php
/**
 * Visual breadcrumbs component.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav class="ame-breadcrumbs-nav" aria-label="Breadcrumbs">
	<ol class="ame-breadcrumbs-list">
		<li class="ame-breadcrumb-item">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="ame-breadcrumb-link"><?php esc_html_e( 'Home', 'ame-bazaar' ); ?></a>
		</li>
		<?php if ( is_home() || is_archive() ) : ?>
			<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
			<li class="ame-breadcrumb-item ame-breadcrumb-item-current">
				<span aria-current="page"><?php post_type_archive_title( __( 'Blog', 'ame-bazaar' ) ); ?></span>
			</li>
		<?php elseif ( is_singular( 'post' ) ) : ?>
			<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
			<li class="ame-breadcrumb-item">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ?: home_url( '/blog/' ) ); ?>" class="ame-breadcrumb-link"><?php esc_html_e( 'Blog', 'ame-bazaar' ); ?></a>
			</li>
			<?php
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				$cat = $categories[0];
				?>
				<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
				<li class="ame-breadcrumb-item">
					<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="ame-breadcrumb-link"><?php echo esc_html( $cat->name ); ?></a>
				</li>
				<?php
			}
			?>
			<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
			<li class="ame-breadcrumb-item ame-breadcrumb-item-current">
				<span aria-current="page"><?php the_title(); ?></span>
			</li>
		<?php else : ?>
			<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
			<li class="ame-breadcrumb-item ame-breadcrumb-item-current">
				<span aria-current="page"><?php the_title(); ?></span>
			</li>
		<?php endif; ?>
	</ol>
</nav>
