<?php

/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
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



if (is_home() && ! is_front_page()) :
?>
	<div class="breadcroumb-area bread-bg">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<?php fossil_breadcrumb(); ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>


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
						<div class="row" data-masonry='{"percentPosition": true }'>
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
					?>

				<?php else :
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
