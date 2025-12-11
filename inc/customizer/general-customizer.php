<?php


if (!function_exists('fossil_general_customize_register')):
    function fossil_general_customize_register($wp_customize)
    {
        $wp_customize->remove_control('header_textcolor');

        $wp_customize->add_section('fossil_general_section', array(
            'title' => __('General Settings', 'fossil'),
            'capability'     => 'edit_theme_options',
            'panel'     => 'fossil_theme_settings',
        ));
        $wp_customize->add_setting('fossil_preloader_show', array(
            'capability'     => 'edit_theme_options',
            'type'           => 'theme_mod',
            'default'       =>  1,
            'sanitize_callback' => 'absint',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('fossil_preloader_show', array(
            'label'      => __('Preloader Show?', 'fossil'),
            'section'    => 'fossil_general_section',
            'settings'   => 'fossil_preloader_show',
            'type'       => 'checkbox',
        ));
        $wp_customize->add_setting('fossil_title_image', array(
            'default'     => get_template_directory_uri() . '/assets/img/bread-bg.jpg',
            'sanitize_callback' => 'esc_url_raw',
            'capability'        => 'edit_theme_options',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control(
            $wp_customize,
            'fossil_title_image',
            array(
                'label'    => __('Title Image', 'fossil'),
                'section'  => 'fossil_general_section',
                'settings' => 'fossil_title_image',
            )
        ));

        $wp_customize->add_section('fossil_typograpy_section', array(
            'title' => __('Fossil Typography', 'fossil'),
            'capability'     => 'edit_theme_options',
            'panel'     => 'fossil_theme_settings',
        ));
        $wp_customize->add_setting('fossil_theme_fonts', array(
            'default'       => 'Outfit',
            'capability'     => 'edit_theme_options',
            'type'           => 'theme_mod',
            'sanitize_callback' => 'fossil_sanitize_font',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('fossil_theme_fonts', array(
            'label'      => __('Body Font', 'fossil'),
            'description'     => __('Default body font is Outfit', 'fossil'),
            'section'    => 'fossil_typograpy_section',
            'settings'   => 'fossil_theme_fonts',
            'type'       => 'select',
            'choices'    => fossil_google_fonts(),
        ));
        $wp_customize->add_setting('fossil_font_size', array(
            'default' =>  '16',
            'capability'     => 'edit_theme_options',
            'type'           => 'theme_mod',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('fossil_font_size_control', array(
            'label'      => __('Body font size', 'fossil'),
            'description'     => __('Default body font size is 16px', 'fossil'),
            'section'    => 'fossil_typograpy_section',
            'settings'   => 'fossil_font_size',
            'type'       => 'text',

        ));
        $wp_customize->add_setting('fossil_font_line_height', array(
            'default' =>  '',
            'capability'     => 'edit_theme_options',
            'type'           => 'theme_mod',
            'sanitize_callback' => 'sanitize_text_field',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('fossil_font_line_height', array(
            'label'      => __('Body font line height', 'fossil'),
            'description'     => __('Default body line height is 28px', 'fossil'),
            'section'    => 'fossil_typograpy_section',
            'settings'   => 'fossil_font_line_height',
            'type'       => 'text',

        ));
        $wp_customize->add_setting('fossil_theme_font_head', array(
            'default'       => 'Outfit',
            'capability'     => 'edit_theme_options',
            'type'           => 'theme_mod',
            'sanitize_callback' => 'fossil_sanitize_font',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('fossil_theme_font_head', array(
            'label'      => __('Header Font', 'fossil'),
            'description'     => __('Default header font is Outfit', 'fossil'),
            'section'    => 'fossil_typograpy_section',
            'settings'   => 'fossil_theme_font_head',
            'type'       => 'select',
            'choices'    => fossil_google_fonts(),
        ));
        $wp_customize->add_setting('fossil_font_weight_head', array(
            'default'       => '700',
            'capability'     => 'edit_theme_options',
            'type'           => 'theme_mod',
            'sanitize_callback' => 'fossil_sanitize_select',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('fossil_font_weight_head', array(
            'label'      => __('Site header font weight', 'fossil'),
            'section'    => 'fossil_typograpy_section',
            'settings'   => 'fossil_font_weight_head',
            'type'       => 'select',
            'choices'    => array(
                '400' => __('Normal', 'fossil'),
                '700' => __('Bold', 'fossil')
            ),
        ));
        $wp_customize->add_setting('fossil_header_font_transform', array(
            'default'        => 'none',
            'capability'     => 'edit_theme_options',
            'type'           => 'theme_mod',
            'sanitize_callback' => 'fossil_sanitize_select',
            'transport' => 'refresh',
        ));
        $wp_customize->add_control('fossil_header_font_transform', array(
            'label'      => __('Text transport', 'fossil'),
            'description'     => __('You can set header font uppercase or lowercase.', 'fossil'),
            'section'    => 'fossil_typograpy_section',
            'settings'   => 'fossil_header_font_transform',
            'type'       => 'select',
            'choices'    => array(
                'none' => __('Standard', 'fossil'),
                'uppercase' => __('Uppercase', 'fossil'),
                'capitalize' => __('Capitalize', 'fossil'),
            ),
        ));
        //color section

        $wp_customize->add_section('fossil_color_section', array(
            'title' => __('Theme Color', 'fossil'),
            'capability'     => 'edit_theme_options',
            'panel'     => 'fossil_theme_settings',
        ));
        $wp_customize->add_setting('fossil_primary_color', array(
            'default' => '#41CB5B',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));
        // Add color control 
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'fossil_primary_color',
                array(
                    'label' => __('Theme primary color', 'fossil'),
                    'section' => 'fossil_color_section',
                    // 'active_callback' => 'theme_color_calback',
                )
            )
        );
        // Add setting
        $wp_customize->add_setting('fossil_secondary_color', array(
            'default' => '#8fa4b4',
            'type' => 'theme_mod',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport' => 'refresh',
        ));
        // Add color control 
        $wp_customize->add_control(
            new WP_Customize_Color_Control(
                $wp_customize,
                'fossil_secondary_color',
                array(
                    'label' => __('Theme secondary color', 'fossil'),
                    'section' => 'fossil_color_section',
                    // 'active_callback' => 'theme_color_calback',
                )
            )
        );
    }
endif;
add_action('customize_register', 'fossil_general_customize_register');
