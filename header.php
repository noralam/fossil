<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fossil
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'fossil'); ?></a>
		<?php
		$fossil_header_style = get_theme_mod('fossil_header_style', 'style_one');
		if ($fossil_header_style == 'style_three') {
			$fossil_headerbg = 'green-bg';
		} else {
			$fossil_headerbg = 'dark-bg';
		}
		do_action('fossil_header_top_part', $fossil_headerbg);
		?>
		<header id="masthead" class="site-header <?php echo esc_attr($fossil_header_style); ?> header-area <?php if ($fossil_header_style == 'style_three' && is_front_page() && !is_home()): ?>absolute-header<?php endif; ?>">
			<?php if ($fossil_header_style == 'style_two'): ?>
				<div class="fossil-header2">
					<?php do_action('fossil_header_top_style_two'); ?>
					<div class="sticky-area">
						<?php do_action('fossil_header_menubar_style_two'); ?>
					</div>
				</div>
			<?php else: ?>
				<?php do_action('fossil_header_main_part'); ?>
			<?php endif; ?>
		</header><!-- #masthead -->