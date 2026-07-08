<?php
/**
 * The template for displaying knowledge hub archive (index of guides).
 *
 * @package Ame_Bazaar
 */

get_header();

require_once AME_BAZAAR_PATH . '/inc/knowledge-components.php';
?>

<div id="primary" class="content-area ame-knowledge-archive-container">
	<main id="main" class="site-main" role="main">
		
		<header class="page-header ame-knowledge-hero">
			<?php ame_bazaar_render_breadcrumb(); ?>
			<h1 class="entry-title"><?php esc_html_e( 'AME Bazaar Knowledge Hub', 'ame-bazaar' ); ?></h1>
			<div class="entry-subtitle">
				<p><?php esc_html_e( 'Your central source of factual guides, store facts, fashion buying tools, and local shopping guidelines.', 'ame-bazaar' ); ?></p>
			</div>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="ame-knowledge-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'knowledge-card-item' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="card-thumbnail">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
							</div>
						<?php endif; ?>
						<div class="card-content">
							<h3 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<div class="card-excerpt">
								<?php echo wp_trim_words( get_the_excerpt(), 20 ); ?>
							</div>
							<div class="card-footer">
								<span class="read-time"><?php echo esc_html( ame_bazaar_get_reading_time( get_the_content() ) ); ?></span>
								<a href="<?php the_permalink(); ?>" class="read-more-btn"><?php esc_html_e( 'Explore Guide →', 'ame-bazaar' ); ?></a>
							</div>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<?php
			the_posts_pagination( array(
				'prev_text' => esc_html__( 'Previous', 'ame-bazaar' ),
				'next_text' => esc_html__( 'Next', 'ame-bazaar' ),
			) );
			?>

		<?php else : ?>
			<div class="no-results not-found">
				<p><?php esc_html_e( 'No guides or articles published yet. Check back soon!', 'ame-bazaar' ); ?></p>
			</div>
		<?php endif; ?>

	</main>
</div>

<?php
get_footer();
