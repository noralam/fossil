<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fossil
 */
$fossil_page_container = get_theme_mod('fossil_page_container', 'container');
$fossil_single_page_comment = get_theme_mod('fossil_single_page_comment');
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
	<div class="<?php echo esc_attr($fossil_page_container); ?>">

		<?php
		while (have_posts()) :
			the_post();

			get_template_part('template-parts/content', 'page');

			// If comments are open or we have at least one comment, load up the comment template.
			if ($fossil_single_page_comment == 1 && (comments_open() || get_comments_number())) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
	</div>
</main><!-- #main -->

<?php

get_footer();
