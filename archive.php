<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fossil
 */

$fossil_blog_container = get_theme_mod('fossil_blog_container', 'container');
$fossil_blog_layout = get_theme_mod('fossil_blog_layout', 'fullwidth');
$fossil_blog_style = get_theme_mod('fossil_blog_style', 'grid');
if (is_active_sidebar('sidebar-1') && $fossil_blog_layout != 'fullwidth') {
	$fossile_blog_column = 'col-lg-8';
} else {
	$fossile_blog_column = 'col-lg-12';
}
get_header();
?>
<div class="breadcroumb-area bread-bg">
	<div class="container">
		<?php fossil_breadcrumb(); ?>
		<?php the_archive_description('<div class="archive-description">', '</div>'); ?>
	</div>
</div>
<main id="primary" class="site-main blog-area section-padding">
	<div class="<?php echo esc_attr($fossil_blog_container); ?>">
		<div class="row">
			<?php if ($fossil_blog_layout == 'leftside'): ?>
				<div class="col-lg-4">
					<?php get_sidebar(); ?>
				</div>
			<?php endif; ?>
			<div class="<?php echo esc_attr($fossile_blog_column); ?>">
				<?php
				if (have_posts()) :

					if ($fossil_blog_style == 'grid' || !is_single()):
				?>
						<div class="row">
						<?php
					endif;
					/* Start the Loop */
					while (have_posts()) :
						the_post();

						/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
						get_template_part('template-parts/content', get_post_type());

					endwhile;
					if ($fossil_blog_style == 'grid' || !is_single()):
						?>
						</div>
					<?php
					endif;
					fossil_posts_pagination();
				else :
					?>

				<?php

					get_template_part('template-parts/content', 'none');

				endif;
				?>

			</div>

			<?php if ($fossil_blog_layout == 'rightside'): ?>
				<div class="col-lg-4">
					<?php get_sidebar(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main><!-- #main -->

<?php

get_footer();
