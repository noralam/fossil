<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package Fossil
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function fossil_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (! is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (! is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter('body_class', 'fossil_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function fossil_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'fossil_pingback_header');


function fossil_posts_pagination($args = [])
{
	global $wp_query;

	// If there's only 1 page, return early
	if ($wp_query->max_num_pages <= 1) {
		return;
	}
	$defaults = [
		'prev_icon' => '<i class="fas fa-arrow-left"></i>',
		'next_icon' => '<i class="fas fa-arrow-right"></i>',
		'class'     => 'fossil-posts-pagination',
	];
	$args = wp_parse_args($args, $defaults);

	echo '<div class="pagination-block mb-15">';
	the_posts_pagination([
		'prev_text' => $args['prev_icon'],
		'next_text' => $args['next_icon'],
		'class'     => $args['class'],
	]);
	echo '</div>';
}


function fossil_add_preloader()
{
	$fossil_preloader_show = get_theme_mod('fossil_preloader_show', 1);
	if (empty($fossil_preloader_show)) {
		return;
	}


?>
	<!-- Pre-Loader -->
	<div id="loader">
		<div class="loading">
			<div class="spinner">
				<div class="bounce1"></div>
				<div class="bounce2"></div>
				<div class="bounce3"></div>
			</div>
		</div>
	</div>
<?php
}
// Hook the loader to wp_body_open
add_action('wp_body_open', 'fossil_add_preloader');


function fossil_footer_allowed_html_tags()
{
	return array(
		'a' => array(
			'href'   => array(),
			'title'  => array(),
			'class'  => array(),
			'target' => array(),
			'rel'    => array(),
		),
		'span' => array(
			'class' => array(),
			'style' => array(),
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'p'      => array(
			'class' => array(),
		),
	);
}

function fossil_get_copyright_text()
{
	$current_year = date('Y');
	$site_url = home_url('/');
	$site_name = get_bloginfo('name');

	$copyright_text = sprintf(
		'<span class="copyright">%s</span> <a href="%s">%s</a>. <span class="rights">%s</span>',
		esc_html($current_year),
		esc_url($site_url),
		esc_html($site_name),
		esc_html__('All rights reserved', 'fossil')
	);

	return wp_kses($copyright_text, fossil_footer_allowed_html_tags());
}

function fossil_el_template_list()
{
	$templates = get_posts(
		array(
			'post_type' => 'elementor_library',
			'numberposts' => -1,
			'post_status' => 'publish',
		)
	);
	$template_items = [];
	$template_items['select'] = esc_html__('Select Template', 'fossil');
	if (!empty($templates) && did_action('elementor/loaded')) {
		foreach ($templates as $template) {
			$template_items[$template->ID] = esc_html($template->post_title);
		}
	}
	return $template_items;
}

function fossil_el_template_list_desc($section = '?')
{
	$section = esc_html($section); // Sanitize the section parameter
	
	if (did_action('elementor/loaded')) {
		$help_message = __('don\'t available Template ', 'fossil');
		$help_link = home_url() . '/wp-admin/edit.php?post_type=elementor_library&tabs_group=library&elementor_library_type=section';
		$help_link_text = __('Create A Template', 'fossil');
	} else {
		$help_message = __('You need to install Elementor Plugin? ', 'fossil');
		$help_link = home_url() . '/wp-admin/plugin-install.php?s=elementor&tab=search&type=term';
		$help_link_text = __('Install & active Elementor', 'fossil');
	}

	$output = sprintf('%s %s <a target="_blank" href="%s">%s</a>', 
		esc_html($help_message), 
		$section, 
		esc_url($help_link), 
		esc_html($help_link_text)
	);

	return wp_kses($output, array(
		'a' => array(
			'href' => array(),
			'target' => array(),
		)
	));
}

function fossil_sanitize_lite_html($input = '')
{
	$allowed_html = array(
		'span' => array(
			'class' => array(),
			'style' => array(),
		),
		'strong' => array(
			'class' => array(),
			'style' => array(),
		),
		'br' => array(),
	);

	return wp_kses($input, $allowed_html);
}

function fossil_custom_search_form_inner($form)
{
	// Extract the original form's <form> tag
	preg_match('/<form[^>]*>/', $form, $form_tag);

	// Define your custom inner markup
	$custom_inner_markup = '
        <div class="form-group">
            <label class="screen-reader-text" for="search-field">
                ' . esc_html_x('Search for:', 'label', 'fossil') . '
            </label>
            <input type="search"
                id="search-field"
                class="search-field"
                name="s"
                value="' . esc_attr(get_search_query()) . '"
                placeholder="' . esc_attr_x('Search...', 'placeholder', 'fossil') . '"
                required>
            <button type="submit" aria-label="' . esc_attr_x('Search', 'submit button', 'fossil') . '">
                <i class="fas fa-search" aria-hidden="true"></i>
            </button>
        </div>';

	// Combine the <form> tag with the custom inner markup and closing </form> tag
	$custom_form = $form_tag[0] . $custom_inner_markup . '</form>';

	return $custom_form;
}
add_filter('get_search_form', 'fossil_custom_search_form_inner');
