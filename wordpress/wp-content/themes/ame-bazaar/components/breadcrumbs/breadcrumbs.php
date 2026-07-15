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
		<?php elseif ( is_singular( 'product' ) ) : ?>
			<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
			<li class="ame-breadcrumb-item">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="ame-breadcrumb-link"><?php esc_html_e( 'Shop', 'ame-bazaar' ); ?></a>
			</li>
			<?php
			$terms = get_the_terms( get_the_ID(), 'product_cat' );
			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				// Find the deepest term or first term
				$primary_term = $terms[0];
				$ancestors = get_ancestors( $primary_term->term_id, 'product_cat' );
				$ancestors = array_reverse( $ancestors );
				
				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, 'product_cat' );
					if ( $ancestor && ! is_wp_error( $ancestor ) ) {
						?>
						<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
						<li class="ame-breadcrumb-item">
							<a href="<?php echo esc_url( get_term_link( $ancestor ) ); ?>" class="ame-breadcrumb-link"><?php echo esc_html( $ancestor->name ); ?></a>
						</li>
						<?php
					}
				}
				?>
				<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
				<li class="ame-breadcrumb-item">
					<a href="<?php echo esc_url( get_term_link( $primary_term ) ); ?>" class="ame-breadcrumb-link"><?php echo esc_html( $primary_term->name ); ?></a>
				</li>
				<?php
			}
			?>
			<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
			<li class="ame-breadcrumb-item ame-breadcrumb-item-current">
				<span aria-current="page"><?php the_title(); ?></span>
			</li>
		<?php elseif ( is_tax( 'product_cat' ) ) : ?>
			<?php
			$current_term = get_queried_object();
			$ancestors = get_ancestors( $current_term->term_id, 'product_cat' );
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $ancestor_id ) {
				$ancestor = get_term( $ancestor_id, 'product_cat' );
				if ( $ancestor && ! is_wp_error( $ancestor ) ) {
					?>
					<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
					<li class="ame-breadcrumb-item">
						<a href="<?php echo esc_url( get_term_link( $ancestor ) ); ?>" class="ame-breadcrumb-link"><?php echo esc_html( $ancestor->name ); ?></a>
					</li>
					<?php
				}
			}
			?>
			<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
			<li class="ame-breadcrumb-item ame-breadcrumb-item-current">
				<span aria-current="page"><?php echo esc_html( $current_term->name ); ?></span>
			</li>
		<?php else : ?>
			<li class="ame-breadcrumb-separator" aria-hidden="true">/</li>
			<li class="ame-breadcrumb-item ame-breadcrumb-item-current">
				<span aria-current="page"><?php the_title(); ?></span>
			</li>
		<?php endif; ?>
	</ol>
</nav>
