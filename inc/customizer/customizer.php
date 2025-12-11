<?php

/**
 * Fossil Theme Customizer
 *
 * @package Fossil
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
require get_template_directory() . '/inc/customizer/google-fonts.php';
require get_template_directory() . '/inc/customizer/general-customizer.php';
require get_template_directory() . '/inc/customizer/general-style.php';

function fossil_header_search_callback()
{
	$fossil_show_search_icon = get_theme_mod('fossil_show_search_icon');
	if ($fossil_show_search_icon) {
		return true;
	}
	return false;
}
function fossil_header_style_two_callback()
{
	$fossil_header_style = get_theme_mod('fossil_header_style', 'style_one');
	if ($fossil_header_style == 'style_two') {
		return true;
	}
	return false;
}
function fossil_header_btn_callback()
{
	$fossil_show_header_button = get_theme_mod('fossil_show_header_button', 1);
	if ($fossil_show_header_button) {
		return true;
	}
	return false;
}

function fossil_customize_register($wp_customize)
{
	$wp_customize->get_setting('blogname')->transport         = 'postMessage';
	$wp_customize->get_setting('blogdescription')->transport  = 'postMessage';
	$wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

	function fossil_sanitize_select($input, $setting)
	{
		$input = sanitize_key($input);
		$choices = $setting->manager->get_control($setting->id)->choices;
		return (array_key_exists($input, $choices) ? $input : $setting->default);
	}



	$wp_customize->add_setting('fossil_show_tagline', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'       =>  '',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_show_tagline', array(
		'label'      => __('Show Site Tagline?', 'fossil'),
		'section'    => 'title_tagline',
		'settings'   => 'fossil_show_tagline',
		'type'       => 'checkbox',
	));
	// Fossil Theme Settings Panel
	$wp_customize->add_panel('fossil_theme_settings', array(
		'priority'       => 50,
		'title'          => __('Fossil Theme settings', 'fossil'),
		'description'    => __('Fossil Theme All Settings', 'fossil'),
	));
	$wp_customize->add_section('fossil_top_header', array(
		'title' => __('Fossil Header Top Settings', 'fossil'),
		'capability'     => 'edit_theme_options',
		'description'     => __('Fossil theme top header settings', 'fossil'),
		'panel'    => 'fossil_theme_settings',
	));
	$wp_customize->add_setting('fossil_topheader_show', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'       =>  1,
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_topheader_show', array(
		'label'      => __('Display Header Top', 'fossil'),
		'description'     => __('You can show or hide top header from theme options', 'fossil'),
		'section'    => 'fossil_top_header',
		'settings'   => 'fossil_topheader_show',
		'type'       => 'checkbox',
	));
	$wp_customize->add_setting('fossil_topheader_email', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'		=>  __('info@example.com', 'fossil'),
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_topheader_email', array(
		'label'      => __('Email Address', 'fossil'),
		'section'    => 'fossil_top_header',
		'settings'   => 'fossil_topheader_email',
		'type'       => 'text',
	));
	$wp_customize->add_setting('fossil_topheader_address', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'		=>  __('66, Broklyn St, New York, USA', 'fossil'),
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_topheader_address', array(
		'label'      => __('Header Address', 'fossil'),
		'section'    => 'fossil_top_header',
		'settings'   => 'fossil_topheader_address',
		'type'       => 'text',
	));
	$wp_customize->add_setting('fossil_topheader_slogan', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'		=>  __('Turning big ideas into great services!', 'fossil'),
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_topheader_slogan', array(
		'label'      => __('Header Slogan', 'fossil'),
		'section'    => 'fossil_top_header',
		'settings'   => 'fossil_topheader_slogan',
		'type'       => 'text',
	));
	$wp_customize->add_setting('fossil_header_social', array(
		'default'        => 1,
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_header_social', array(
		'label'      => __('Show Header Social Icons?', 'fossil'),
		'section'    => 'fossil_top_header',
		'settings'   => 'fossil_post_meta',
		'type'       => 'checkbox',
	));

	$wp_customize->add_setting('fossil_hsocial_facebook', array(
		'default' =>  '',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_hsocial_facebook', array(
		'label'      => __('Facebook url', 'fossil'),
		'section'    => 'fossil_top_header',
		'settings'   => 'fossil_hsocial_facebook',
		'type'       => 'url',
	));
	$wp_customize->add_setting('fossil_hsocial_instagram', array(
		'default' =>  '',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_hsocial_instagram', array(
		'label'      => __('Instagram url', 'fossil'),
		'section'    => 'fossil_top_header',
		'settings'   => 'fossil_hsocial_instagram',
		'type'       => 'url',
	));
	$wp_customize->add_setting('fossil_hsocial_twitter', array(
		'default' =>  '',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_hsocial_twitter', array(
		'label'      => __('Twitter url', 'fossil'),
		'section'    => 'fossil_top_header',
		'settings'   => 'fossil_hsocial_twitter',
		'type'       => 'url',
	));
	$wp_customize->add_setting('fossil_hsocial_skype', array(
		'default' =>  '',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'esc_url_raw',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_hsocial_skype', array(
		'label'      => __('Skype url', 'fossil'),
		'section'    => 'fossil_top_header',
		'settings'   => 'fossil_hsocial_skype',
		'type'       => 'url',
	));

	// Add Header Settings section
	$wp_customize->add_section('fossil_header_settings', array(
		'title'    => __('Header Settings', 'fossil'),
		'capability'     => 'edit_theme_options',
		'panel'    => 'fossil_theme_settings',
	));
	// Header Style setting
	$wp_customize->add_setting('fossil_header_style', array(
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'default'           => 'style_one',
		'sanitize_callback' => 'fossil_sanitize_select',
		'transport'         => 'refresh',
	));
	$wp_customize->add_control('fossil_header_style', array(
		'label'       => __('Header Style', 'fossil'),
		'section'     => 'fossil_header_settings',
		'settings'    => 'fossil_header_style',
		'type'        => 'select',
		'choices'     => array(
			'style_one' => __('Style One', 'fossil'),
			'style_two' => __('Style Two', 'fossil'),
			'style_three' => __('Style Three', 'fossil'),
		),
	));

	$wp_customize->add_setting('fossil_header_stwo_time_text', array(
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'default'           => '',
		'sanitize_callback' => 'fossil_sanitize_lite_html',
		'transport'         => 'refresh',
	));
	$wp_customize->add_control('fossil_header_stwo_time_text', array(
		'label'       => __('Time Text', 'fossil'),
		'section'     => 'fossil_header_settings',
		'settings'    => 'fossil_header_stwo_time_text',
		'type'        => 'text',
		'active_callback' => 'fossil_header_style_two_callback',
		'description' => __('Header style time text goes here.', 'fossil'),
	));
	$wp_customize->add_setting('fossil_header_stwo_place_text', array(
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'default'           => '',
		'sanitize_callback' => 'fossil_sanitize_lite_html',
		'transport'         => 'refresh',
	));
	$wp_customize->add_control('fossil_header_stwo_place_text', array(
		'label'       => __('Place Text', 'fossil'),
		'section'     => 'fossil_header_settings',
		'settings'    => 'fossil_header_stwo_place_text',
		'type'        => 'text',
		'active_callback' => 'fossil_header_style_two_callback',
		'description' => __('Header style place text goes here.', 'fossil'),
	));
	$wp_customize->add_setting('fossil_header_stwo_info_text', array(
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'default'           => '',
		'sanitize_callback' => 'fossil_sanitize_lite_html',
		'transport'         => 'refresh',
	));
	$wp_customize->add_control('fossil_header_stwo_info_text', array(
		'label'       => __('Info Text', 'fossil'),
		'section'     => 'fossil_header_settings',
		'settings'    => 'fossil_header_stwo_info_text',
		'type'        => 'text',
		'active_callback' => 'fossil_header_style_two_callback',
		'description' => __('Header style info text goes here.', 'fossil'),
	));

	// Show Search Icon setting
	$wp_customize->add_setting('fossil_show_search_icon', array(
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'default'           => 1,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	));
	$wp_customize->add_control('fossil_show_search_icon', array(
		'label'       => __('Show Search Icon', 'fossil'),
		'section'     => 'fossil_header_settings',
		'settings'    => 'fossil_show_search_icon',
		'type'        => 'checkbox',
		'description' => __('Toggle to display or hide the search icon in the header.', 'fossil'),
	));
	$wp_customize->add_setting('fossil_header_search_label', array(
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'default'           => __('Search for anything.', 'fossil'),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));
	$wp_customize->add_control('fossil_header_search_label', array(
		'label'       => __('Search Label', 'fossil'),
		'section'     => 'fossil_header_settings',
		'settings'    => 'fossil_header_search_label',
		'type'        => 'text',
		'active_callback' => 'fossil_header_search_callback',
		'description' => __('The search label displayed in the search popup.', 'fossil'),
	));

	// Show Button setting
	$wp_customize->add_setting('fossil_show_header_button', array(
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'default'           => 1,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	));
	$wp_customize->add_control('fossil_show_header_button', array(
		'label'       => __('Show Header Button', 'fossil'),
		'section'     => 'fossil_header_settings',
		'settings'    => 'fossil_show_header_button',
		'type'        => 'checkbox',
		'description' => __('Toggle to display or hide the button in the header.', 'fossil'),
	));

	// Button Text setting
	$wp_customize->add_setting('fossil_header_button_text', array(
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'default'           => __('Get Quote', 'fossil'),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));
	$wp_customize->add_control('fossil_header_button_text', array(
		'label'       => __('Button Text', 'fossil'),
		'section'     => 'fossil_header_settings',
		'settings'    => 'fossil_header_button_text',
		'type'        => 'text',
		'active_callback' => 'fossil_header_btn_callback',
		'description' => __('Text for the button displayed in the header.', 'fossil'),
	));
	// Button url setting
	$wp_customize->add_setting('fossil_header_button_url', array(
		'capability'        => 'edit_theme_options',
		'type'              => 'theme_mod',
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));
	$wp_customize->add_control('fossil_header_button_url', array(
		'label'       => __('Button Url', 'fossil'),
		'section'     => 'fossil_header_settings',
		'settings'    => 'fossil_header_button_url',
		'type'        => 'url',
		'active_callback' => 'fossil_header_btn_callback',
		'description' => __('Enter the url for the button displayed in the header.', 'fossil'),
	));


	//Post Settings 
	$wp_customize->add_section('fossil_blog', array(
		'title' => __('Fossil Blog Settings', 'fossil'),
		'capability'     => 'edit_theme_options',
		'description'     => __('Fossil theme blog settings', 'fossil'),
		'panel'    => 'fossil_theme_settings',

	));
	$wp_customize->add_setting('fossil_blog_container', array(
		'default'        => 'container',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'fossil_sanitize_select',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_blog_container', array(
		'label'      => __('Container type', 'fossil'),
		'description' => __('You can set standard container or full width container. ', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_blog_container',
		'type'       => 'select',
		'choices'    => array(
			'container' => __('Standard Container', 'fossil'),
			'container-fluid' => __('Full width Container', 'fossil'),
		),
	));

	$wp_customize->add_setting('fossil_blog_layout', array(
		'default'        => 'fullwidth',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'fossil_sanitize_select',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_blog_layout', array(
		'label'      => __('Select Blog Layout', 'fossil'),
		'description' => __('Right and Left sidebar only show when sidebar widget is available. ', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_blog_layout',
		'type'       => 'select',
		'choices'    => array(
			'rightside' => __('Right Sidebar', 'fossil'),
			'leftside' => __('Left Sidebar', 'fossil'),
			'fullwidth' => __('No Sidebar', 'fossil'),
		),
	));
	$wp_customize->add_setting('fossil_blog_style', array(
		'default'        => 'grid',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'fossil_sanitize_select',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_blog_style', array(
		'label'      => __('Select Blog Style', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_blog_style',
		'type'       => 'select',
		'choices'    => array(
			'grid' => __('Grid Style', 'fossil'),
			'classic' => __('Classic Style', 'fossil'),
		),
	));

	$wp_customize->add_setting('fossil_redmore_text', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'		=>  __('Read More', 'fossil'),
		'sanitize_callback' => 'sanitize_text_field',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_redmore_text', array(
		'label'      => __('Read More text', 'fossil'),
		'description'     => __('You can change blog readmore text.', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_redmore_text',
		'type'       => 'text',
	));
	$wp_customize->add_setting('fossil_post_meta', array(
		'default'        => 1,
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_post_meta', array(
		'label'      => __('Show Post meta?', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_post_meta',
		'type'       => 'checkbox',
	));

	$wp_customize->add_setting('fossil_single_blog_layout', array(
		'default'        => 'rightside',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'fossil_sanitize_select',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_single_blog_layout', array(
		'label'      => __('Select Single Blog Layout', 'fossil'),
		'description' => __('Right and Left sidebar only show when sidebar widget is available. ', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_single_blog_layout',
		'type'       => 'select',
		'choices'    => array(
			'rightside' => __('Right Sidebar', 'fossil'),
			'leftside' => __('Left Sidebar', 'fossil'),
			'fullwidth' => __('No Sidebar', 'fossil'),
		),
	));

	$wp_customize->add_setting('fossil_single_post_meta', array(
		'default'        => '',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_single_post_meta', array(
		'label'      => __('Show Single Post Title Meta?', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_single_post_meta',
		'type'       => 'checkbox',
	));
	$wp_customize->add_setting('fossil_single_post_author_bio', array(
		'default'        => 1,
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_single_post_author_bio', array(
		'label'      => __('Show Single Post Author Bio?', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_single_post_author_bio',
		'type'       => 'checkbox',
	));
	$wp_customize->add_setting('fossil_single_post_navigation', array(
		'default'        => '',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_single_post_navigation', array(
		'label'      => __('Show Single Post Navigation?', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_single_post_navigation',
		'type'       => 'checkbox',
	));
	$wp_customize->add_setting('fossil_single_post_comment', array(
		'default'        => 1,
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_single_post_comment', array(
		'label'      => __('Show Single Post Comment?', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_single_post_comment',
		'type'       => 'checkbox',
	));

	// Fossil Page Settings
	$wp_customize->add_section('fossil_page', array(
		'title' => __('Fossil page Settings', 'fossil'),
		'capability'     => 'edit_theme_options',
		'description'     => __('Fossil theme page settings', 'fossil'),
		'panel'    => 'fossil_theme_settings',

	));
	$wp_customize->add_setting('fossil_page_container', array(
		'default'        => 'container',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'fossil_sanitize_select',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_page_container', array(
		'label'      => __('Page Container', 'fossil'),
		'description' => __('You can set standard container or full width container. ', 'fossil'),
		'section'    => 'fossil_page',
		'settings'   => 'fossil_page_container',
		'type'       => 'select',
		'choices'    => array(
			'container' => __('Standard Container', 'fossil'),
			'container-fluid' => __('Full width Container', 'fossil'),
		),
	));
	$wp_customize->add_setting('fossil_single_page_comment', array(
		'default'        => '',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_single_page_comment', array(
		'label'      => __('Show Page Comment?', 'fossil'),
		'section'    => 'fossil_blog',
		'settings'   => 'fossil_single_page_comment',
		'type'       => 'checkbox',
	));

	$wp_customize->add_section('fossil_top_footer', array(
		'title' => __('Fossil Top Footer Settings', 'fossil'),
		'capability'     => 'edit_theme_options',
		'description'     => __('Fossil theme top footer settings', 'fossil'),
		'panel'    => 'fossil_theme_settings',
	));
	$wp_customize->add_setting('fossil_topfooter_show', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'       =>  1,
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_topfooter_show', array(
		'label'      => __('Display Top Footer', 'fossil'),
		'description'     => __('You can show or hide top footer from theme options', 'fossil'),
		'section'    => 'fossil_top_footer',
		'settings'   => 'fossil_topfooter_show',
		'type'       => 'checkbox',
	));
	$wp_customize->add_setting('fossil_topfooter_subscribe_show', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'       =>  '',
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_topfooter_subscribe_show', array(
		'label'      => __('Display Subscribe Newsletter', 'fossil'),
		'description'     => __('You can show or hide footer subscribe newsletter section', 'fossil'),
		'section'    => 'fossil_top_footer',
		'settings'   => 'fossil_topfooter_subscribe_show',
		'type'       => 'checkbox',
	));
	$wp_customize->add_setting('fossil_topfooter_subscribe_title', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'		=> sprintf(
			'%s<br>%s',
			esc_html__('Subscribe newsletter', 'fossil'),
			esc_html__('For Any Update', 'fossil')
		),
		'sanitize_callback' => 'fossil_footer_allowed_html_tags',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_topfooter_subscribe_title', array(
		'label'      => __('Subscribe Newsletter Title', 'fossil'),
		'section'    => 'fossil_top_footer',
		'settings'   => 'fossil_topfooter_subscribe_title',
		'type'       => 'textarea',
		'active_callback' => function () {
			$show_subscribe = get_theme_mod('fossil_topfooter_subscribe_show');
			return ($show_subscribe !== 0);
		}
	));
	$wp_customize->add_setting('fossil_topfooter_subscribe_shortcode', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'transport' => 'refresh',
		'sanitize_callback' => 'sanitize_text_field',
	));
	$wp_customize->add_control('fossil_topfooter_subscribe_shortcode', array(
		'label'      => __('Subscribe Form Shortcode', 'fossil'),
		'section'    => 'fossil_top_footer',
		'settings'   => 'fossil_topfooter_subscribe_shortcode',
		'type'       => 'textarea',
		'active_callback' => function () {
			$show_subscribe = get_theme_mod('fossil_topfooter_subscribe_show');
			return ($show_subscribe !== 0);
		}
	));
	$wp_customize->add_setting('fossil_template_list', array(
		'default'        => 'select',
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'sanitize_callback' => 'fossil_sanitize_select',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_template_list', array(
		'label'      => __('Template For Footer Top', 'fossil'),
		'description' => fossil_el_template_list_desc(__('For Footer Top?', 'fossil')),
		'section'    => 'fossil_top_footer',
		'settings'   => 'fossil_template_list',
		'type'       => 'select',
		'choices'    => fossil_el_template_list(),
		'active_callback' => function () {
			$show_subscribe = get_theme_mod('fossil_topfooter_show');
			return ($show_subscribe !== 0);
		}
	));




	$wp_customize->add_section('fossil_footer', array(
		'title' => __('Fossil Footer Settings', 'fossil'),
		'capability'     => 'edit_theme_options',
		'description'     => __('Fossil theme footer settings', 'fossil'),
		'panel'    => 'fossil_theme_settings',

	));
	// Footer text change
	$wp_customize->add_setting('fossil_footer_text', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'		=>  fossil_get_copyright_text(),
		'sanitize_callback' => 'fossil_footer_allowed_html_tags',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_footer_text', array(
		'label'      => __('Footer text', 'fossil'),
		'description'     => __('Footer text change by this field', 'fossil'),
		'section'    => 'fossil_footer',
		'settings'   => 'fossil_footer_text',
		'type'       => 'textarea',
	));

	$wp_customize->add_setting('fossil_footer_menu_show', array(
		'capability'     => 'edit_theme_options',
		'type'           => 'theme_mod',
		'default'       =>  1,
		'sanitize_callback' => 'absint',
		'transport' => 'refresh',
	));
	$wp_customize->add_control('fossil_footer_menu_show', array(
		'label'      => __('Display Footer Menu', 'fossil'),
		'description'     => __('Footer menu will only be displayed if a footer menu is set in Appearance -> Menus', 'fossil'),
		'section'    => 'fossil_footer',
		'settings'   => 'fossil_footer_menu_show',
		'type'       => 'checkbox',
	));












	if (isset($wp_customize->selective_refresh)) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'fossil_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'fossil_customize_partial_blogdescription',
			)
		);
	}
}
add_action('customize_register', 'fossil_customize_register');

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function fossil_customize_partial_blogname()
{
	esc_html(bloginfo('name'));
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function fossil_customize_partial_blogdescription()
{
	esc_html(bloginfo('description'));
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function fossil_customize_preview_js()
{
	wp_enqueue_script('fossil-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array('customize-preview'), FOSSIL_THEME_VERSION, true);
}
add_action('customize_preview_init', 'fossil_customize_preview_js');
