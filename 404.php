<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Fossil
 */

get_header();
?>

<main id="primary" class="site-main error-404">

	<div class="blog-area section-padding pb-0 content-none">
		<div class="container">
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="single-blog-item">
					<div class="blog-content no-result text-center">
						<h1 class="content-none-title">
							<?php esc_html_e('404', 'fossil'); ?>
						</h1>

						<div class="page-content">
							<p class="content-none"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'fossil'); ?></p>
							<a href="<?php echo esc_url(home_url('/')); ?>" class="main-btn"><?php esc_html_e('Back to Home', 'fossil'); ?></a>
						</div>
					</div>
			</article>

		</div>
	</div>

</main><!-- #main -->

<?php
get_footer();
