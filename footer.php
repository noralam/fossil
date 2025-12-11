<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fossil
 */
$fossil_footer_text = get_theme_mod('fossil_footer_text');
$fossil_footer_menu_show = get_theme_mod('fossil_footer_menu_show', 1);
$fossil_topfooter_show = get_theme_mod('fossil_topfooter_show', 1);
$fossil_template_list = get_theme_mod('fossil_template_list', 'select');
$fossil_topfooter_subscribe_show = get_theme_mod('fossil_topfooter_subscribe_show');
$fossil_topfooter_subscribe_title = get_theme_mod('fossil_topfooter_subscribe_title', sprintf(
	'%s<br>%s',
	esc_html__('Subscribe newsletter', 'fossil'),
	esc_html__('For Any Update', 'fossil')
));
$fossil_topfooter_subscribe_shortcode = get_theme_mod('fossil_topfooter_subscribe_shortcode');


if ($fossil_topfooter_show && $fossil_topfooter_subscribe_show):
?>

	<div class="newsletter-section">
		<div class="container">
			<div class="newsletter-content d-flex align-items-center justify-content-between">
				<div class="section-title">
					<h2 class="text-white">
						<?php
						echo wp_kses($fossil_topfooter_subscribe_title, fossil_footer_allowed_html_tags()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						?>
					</h2>
				</div>
				<div class="subscribed-form d-flex">
					<?php
					if (isset($fossil_topfooter_subscribe_shortcode) && ! empty($fossil_topfooter_subscribe_shortcode)) {
						echo do_shortcode(esc_html($fossil_topfooter_subscribe_shortcode));
					}
					?>

				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<footer id="colophon" class="site-footer">
	<?php
	if (did_action('elementor/loaded') && $fossil_topfooter_show):
		if ('select' != $fossil_template_list):
	?>
			<div class="footer-area <?php if ($fossil_topfooter_subscribe_show): ?>footer-area-subscribe<?php endif; ?>">
				<div class="container">
					<div class="footer-up">
						<?php
						echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($fossil_template_list, true); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped	
						?>
					</div>
				</div>
			</div>
	<?php
		endif;
	endif;
	?>

	<div class="footer-bottom">
		<div class="container">
			<div class="row justify-content-center align-items-center justify-content-center">
				<div class="col-lg-6 col-md-6 col-sm-12">
					<p class="copyright-line">
						&copy; <?php
								if ($fossil_footer_text) {
									echo
									wp_kses($fossil_footer_text, fossil_footer_allowed_html_tags());
								} else {
									echo fossil_get_copyright_text();
								}
								?>
					</p>
				</div>
				<div class="col-lg-6 col-md-6 col-xs-12 text-md-end">
					<div class="footer-info-fossil">
						<?php
						/* translators: 1: Theme name, 2: Theme author. */
						printf(esc_html__('Built With %1$s by %2$s.', 'fossil'), '<a href="https://wpthemespace.com/product/fossil/">Fossil WordPress Theme</a>', 'Wp Theme Space');
						?>

					</div><!-- .site-info -->
					<?php
					if ($fossil_footer_menu_show) {
						wp_nav_menu(array(
							'theme_location' => 'footer-menu',
							'menu_id'        => 'fossil-footer-menu',
							'menu_class'        => 'fossil-footer-menu',
							'fallback_cb'     => '__return_false',
						));
					}

					?>
				</div>
			</div>
		</div>
	</div>

</footer><!-- #colophon -->
<!-- Scroll Top Area -->
<a href="#top" class="go-top"><i class="fas fa-long-arrow-alt-up"></i></a>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>

</html>