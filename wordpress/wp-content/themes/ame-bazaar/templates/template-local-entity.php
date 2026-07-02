<?php
/**
 * Template Name: Local Entity Page
 *
 * @package Ame_Bazaar
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main" role="main">
	<?php get_template_part( 'components/breadcrumbs/breadcrumbs' ); ?>
	
	<div class="ame-bazaar-container ame-bazaar-section">
		<div class="ame-local-entity-layout">
			<!-- Main Content Column -->
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'ame-local-entity-main' ); ?>>
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<header class="entry-header" style="margin-bottom: 1.5rem;">
						<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>

					<div class="entry-content">
						<?php the_content(); ?>
					</div>
					
					<?php get_template_part( 'components/local-entity/ai-content-sections' ); ?>
				<?php endwhile; ?>
			</article>

			<!-- Sidebar Column -->
			<aside class="ame-local-entity-sidebar">
				<?php get_template_part( 'components/local-entity/business-info' ); ?>
				<?php get_template_part( 'components/local-entity/opening-hours' ); ?>
				<?php get_template_part( 'components/local-entity/payment-methods' ); ?>
				<?php get_template_part( 'components/local-entity/tailoring-status' ); ?>
				<?php get_template_part( 'components/local-entity/local-navigation' ); ?>
			</aside>
		</div>
	</div>
</main>
<?php
get_footer();
