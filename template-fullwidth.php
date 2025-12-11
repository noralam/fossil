<?php

/**
 * The template for displaying fullwidth pages
 *
 * Template Name: Fullwidth Template
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fossil
 */

get_header();
?>

<main id="primary" class="site-main fullwidth-template">

	<?php
	while (have_posts()) :
		the_post();

	?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php fossil_post_thumbnail(); ?>

			<div class="entry-content">
				<?php the_content(); ?>
			</div><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->

	<?php

	endwhile; // End of the loop.
	?>
</main><!-- #main -->

<?php

get_footer();
