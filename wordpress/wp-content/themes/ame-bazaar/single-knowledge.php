<?php
/**
 * The template for displaying single knowledge articles.
 *
 * @package Ame_Bazaar
 */

get_header();

// Fetch template components
require_once AME_BAZAAR_PATH . '/inc/knowledge-components.php';
$post_id = get_the_ID();
$show_location = get_post_meta( $post_id, '_ame_knowledge_show_location', true ) === 'yes';
$show_services = get_post_meta( $post_id, '_ame_knowledge_show_services', true ) === 'yes';
?>

<div id="primary" class="content-area ame-knowledge-single-container">
	<main id="main" class="site-main" role="main">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'knowledge-article-full' ); ?>>
				
				<?php ame_bazaar_render_knowledge_hero(); ?>

				<div class="ame-knowledge-layout">
					<!-- Main Content Body -->
					<div class="ame-knowledge-main-content">
						<div class="entry-content">
							<?php
							the_content();
							
							wp_link_pages( array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ame-bazaar' ),
								'after'  => '</div>',
							) );
							?>
						</div>

						<!-- Related Product Categories -->
						<?php ame_bazaar_render_related_categories( $post_id ); ?>

						<!-- WooCommerce Products Integration Block -->
						<?php ame_bazaar_render_related_products( $post_id ); ?>

						<!-- FAQ Accordion Block -->
						<?php ame_bazaar_render_faq_block( $post_id ); ?>

						<!-- Author Box -->
						<?php ame_bazaar_render_author_box(); ?>

						<!-- Related Articles -->
						<?php ame_bazaar_render_related_articles( $post_id ); ?>
					</div>

					<!-- Sidebar containing Local Business Facts -->
					<aside class="ame-knowledge-sidebar" role="complementary">
						<!-- Centralized Business Facts -->
						<?php ame_bazaar_render_business_facts(); ?>

						<!-- Location Authority Facts -->
						<?php if ( $show_location ) : ?>
							<?php ame_bazaar_render_location_facts(); ?>
						<?php endif; ?>

						<!-- Extra Store Info if active -->
						<?php if ( $show_services ) : ?>
							<section class="ame-fact-card store-services-widget">
								<h3 class="card-title"><?php esc_html_e( 'In-Store Services Offered', 'ame-bazaar' ); ?></h3>
								<ul class="fact-list">
									<li><?php esc_html_e( '• Bespoke Tailoring & Alterations', 'ame-bazaar' ); ?></li>
									<li><?php esc_html_e( '• Free In-Store Pickup', 'ame-bazaar' ); ?></li>
									<li><?php esc_html_e( '• Safe Parking Available', 'ame-bazaar' ); ?></li>
									<li><?php esc_html_e( '• UPI & Cards Accepted', 'ame-bazaar' ); ?></li>
								</ul>
							</section>
						<?php endif; ?>
					</aside>
				</div>

			</article>
			<?php
		endwhile;
		?>
	</main>
</div>

<?php
get_footer();
