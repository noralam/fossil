<?php

/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fossil
 */

?>
<div class="col-lg-4 col-md-6 col-sm-12">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="single-blog-item mt-40">
			<div class="blog-bg">
				<?php the_post_thumbnail(); ?>
			</div>
			<div class="blog-content">
				<?php echo fossil_post_meta(); ?>
				<h5>
					<a href="<?php the_permalink(); ?>">
						<?php the_title(); ?>
					</a>
				</h5>
				<?php the_excerpt(); ?>
				<a href="<?php the_permalink(); ?>" class="read-more">
					<?php esc_html_e('Read More', 'fossil'); ?>
				</a>
			</div>
		</div>
	</article><!-- #post-<?php the_ID(); ?> -->
</div>