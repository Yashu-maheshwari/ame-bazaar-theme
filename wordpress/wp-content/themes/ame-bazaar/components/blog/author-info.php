<?php
/**
 * Reusable Author Info block template.
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$author_id        = get_the_author_meta( 'ID' );
$author_name      = get_the_author();
$author_desc      = get_the_author_meta( 'description' ) ?: sprintf( __( 'Fashion Specialist at %s. Writing about local styling trends, fabric selection, alterations care, and family fashion coordinates in Delhi.', 'ame-bazaar' ), ame_bazaar_get_brand_name() );
$author_posts_url = get_author_posts_url( $author_id );
?>

<div class="ame-author-info-box" itemprop="author" itemscope itemtype="https://schema.org/Person">
	<div class="ame-author-avatar-wrap">
		<?php echo get_avatar( $author_id, 80, '', sprintf( __( 'Avatar photo of %s', 'ame-bazaar' ), esc_attr( $author_name ) ), array( 'class' => 'ame-author-avatar' ) ); ?>
	</div>
	<div class="ame-author-details">
		<span class="ame-author-label"><?php esc_html_e( 'About the Author', 'ame-bazaar' ); ?></span>
		<h4 class="ame-author-name" itemprop="name">
			<a href="<?php echo esc_url( $author_posts_url ); ?>" itemprop="url" class="ame-author-link"><?php echo esc_html( $author_name ); ?></a>
		</h4>
		<p class="ame-author-bio" itemprop="description">
			<?php echo esc_html( $author_desc ); ?>
		</p>
	</div>
</div>
