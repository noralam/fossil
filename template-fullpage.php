<?php

/**
 * Template Name: Fullpage Template
 *
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

<main id="primary" class="site-main">
	<?php
	while (have_posts()) :
		the_post();

		get_template_part('template-parts/content', 'page');

	endwhile; // End of the loop.
	?>
</main><!-- #main -->

<?php

get_footer();
