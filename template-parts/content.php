<?php

/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fossil
 */
$fossil_post_meta = get_theme_mod('fossil_post_meta', 1);
$fossil_single_post_meta = get_theme_mod('fossil_single_post_meta');
$fossil_blog_style = get_theme_mod('fossil_blog_style', 'grid');

if (!is_single() && $fossil_blog_style == 'grid') {
	get_template_part('template-parts/content', 'grid');
} else {

?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php fossil_post_thumbnail(); ?>
		<?php
		if (is_single() && $fossil_single_post_meta == 1) {
			fossil_single_blog_meta();
		} elseif ($fossil_post_meta == 1) {
			echo fossil_post_meta();
		}
		the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
		?>


		<div class="entry-content <?php if (!is_single()): ?>classic-blog mb-5<?php endif; ?>">
			<?php
			if (is_single()) {
				the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__('Continue reading<span class="screen-reader-text"> "%s"</span>', 'fossil'),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post(get_the_title())
					)
				);

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__('Pages:', 'fossil'),
						'after'  => '</div>',
					)
				);
			} else {
				the_excerpt();
			}
			?>
		</div><!-- .entry-content -->
		<?php if (is_single() && $fossil_single_post_meta == 1): ?>
			<footer class="entry-footer">
				<?php fossil_entry_footer(); ?>
			</footer><!-- .entry-footer -->
		<?php endif; ?>
	</article><!-- #post-<?php the_ID(); ?> -->

<?php
}
