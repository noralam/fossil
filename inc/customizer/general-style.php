<?php
/*
*
* Inline style for general customizer style
*
*
*/

if (!function_exists('fossil_general_style')) :
    function fossil_general_style()
    {

        $style = '';

        // Header style added
        $fossil_theme_fonts = get_theme_mod('fossil_theme_fonts', 'Outfit');
        $fossil_font_size = get_theme_mod('fossil_font_size', '16');
        $fossil_font_line_height = get_theme_mod('fossil_font_line_height');

        if ($fossil_theme_fonts != 'Outfit') {
            $style .= 'body,body p,body a{font-family:' . $fossil_theme_fonts . ' !important}';
        }
        if ($fossil_font_size != '16') {
            $style .= 'body,body a,body p{font-size:' . $fossil_font_size . 'px !important}';
        }
        if ($fossil_font_line_height) {
            $style .= 'body,body a,body p{line-height:' . $fossil_font_line_height . 'px !important}';
        }
        //header fonts style

        $fossil_theme_font_head = get_theme_mod('fossil_theme_font_head', 'Teko');
        $fossil_font_weight_head = get_theme_mod('fossil_font_weight_head', '700');
        $fossil_header_font_transform = get_theme_mod('fossil_header_font_transform', 'none');
        if ($fossil_theme_font_head != 'Teko') {
            $style .= 'h1,h2,h3,h4,h5,h6,h1 a,h2 a,h3 a,h4 a,h5 a,h6 a{font-family:' . $fossil_theme_font_head . ' !important}';
        }
        if ($fossil_font_weight_head != '700') {
            $style .= 'h1,h2,h3,h4,h5,h6{font-weight:' . $fossil_font_weight_head . ' !important}';
        }
        if ($fossil_header_font_transform != 'none') {
            $style .= 'h1,h2,h3,h4,h5,h6{text-transform:' . $fossil_header_font_transform . ' !important}';
        }
        // color style
        $fossil_primary_color = get_theme_mod('fossil_primary_color', '#41CB5B');
        $fossil_secondary_color = get_theme_mod('fossil_secondary_color', '#8fa4b4');
        if ($fossil_primary_color != '#41CB5B') {
            $style .= ':root{--color-primary:' . $fossil_primary_color . '}';
        }
        if ($fossil_secondary_color != '#8fa4b4') {
            $style .= ':root{--color-secondary:' . $fossil_secondary_color . '}';
        }

        $default_title_img = get_template_directory_uri() . '/assets/img/bread-bg.jpg';
        $fossil_title_image = get_theme_mod('fossil_title_image', $default_title_img);
        $style .= '.breadcroumb-area{background-image:url(' . $fossil_title_image . ')};';


        wp_add_inline_style('fossil-main-style', $style);
    }
endif;
add_action('wp_enqueue_scripts', 'fossil_general_style');
