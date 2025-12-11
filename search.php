<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Fossil
 */
$fossil_blog_container = get_theme_mod('fossil_blog_container', 'container');
get_header();
?>
<div class="breadcroumb-area bread-bg search-bread-bg">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<?php fossil_breadcrumb(); ?>
			</div>
		</div>
	</div>
</div>
<main id="primary" class="site-main blog-area section-padding">
	<div class="<?php echo esc_attr($fossil_blog_container); ?>">
		<?php if (have_posts()) : ?>
			<div class="row">
				<?php
				/* Start the Loop */
				while (have_posts()) :
					the_post();

					/**
					 * Run the loop for the search to output the results.
					 * If you want to overload this in a child theme then include a file
					 * called content-search.php and that will be used instead.
					 */
					get_template_part('template-parts/content', 'search');

				endwhile;

				fossil_posts_pagination();
				?>
			</div>
		<?php
		else :

			get_template_part('template-parts/content', 'none');

		endif;
		?>

	</div>

</main><!-- #main -->

<?php
get_footer();
