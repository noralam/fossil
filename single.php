<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Fossil
 */
$fossil_blog_container = get_theme_mod('fossil_blog_container', 'container');
$fossil_single_blog_layout = get_theme_mod('fossil_single_blog_layout', 'rightside');
$fossil_blog_style = get_theme_mod('fossil_blog_style', 'grid');
$fossil_single_post_navigation = get_theme_mod('fossil_single_post_navigation');
$fossil_single_post_author_bio = get_theme_mod('fossil_single_post_author_bio', 1);
$fossil_single_post_comment = get_theme_mod('fossil_single_post_comment', 1);
if (is_active_sidebar('sidebar-1') && $fossil_single_blog_layout != 'fullwidth') {
	$fossile_blog_column = 'col-lg-8';
} else {
	$fossile_blog_column = 'col-lg-12';
}
get_header();
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

<main id="primary" class="site-main blog-section section-padding">
	<div class="<?php echo esc_attr($fossil_blog_container); ?>">
		<div class="row">
			<?php if ($fossil_single_blog_layout == 'leftside'): ?>
				<div class="col-lg-4">
					<?php get_sidebar(); ?>
				</div>
			<?php endif; ?>
			<div class="<?php echo esc_attr($fossile_blog_column); ?>">
				<?php
				while (have_posts()) :
					the_post();

					get_template_part('template-parts/content', get_post_type());
					if ($fossil_single_post_navigation) {
						the_post_navigation(
							array(
								'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'fossil') . '</span> <span class="nav-title">%title</span>',
								'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'fossil') . '</span> <span class="nav-title">%title</span>',
							)
						);
					}
					if ($fossil_single_post_author_bio) {
						do_action('fossil_author_bio');
					}

					// If comments are open or we have at least one comment, load up the comment template.
					if ($fossil_single_post_comment == 1 && comments_open() || get_comments_number()) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>
			</div>
			<?php if ($fossil_single_blog_layout == 'rightside'): ?>
				<div class="col-lg-4">
					<?php get_sidebar(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main><!-- #main -->

<?php
get_footer();
