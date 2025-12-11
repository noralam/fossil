<?php

/**
 * Fossil Header Top Part
 *
 * @package Fossil
 */

function fossil_header_top_part_output($topbg = null)
{
    $topbg = $topbg ?? 'dark-bg';
    $fossil_topheader_show = get_theme_mod('fossil_topheader_show', '1');
    $fossil_topheader_email = get_theme_mod('fossil_topheader_email', __('info@example.com', 'fossil'));
    $fossil_topheader_address = get_theme_mod('fossil_topheader_address', __('66, Broklyn St, New York, USA', 'fossil'));
    $fossil_topheader_slogan = get_theme_mod('fossil_topheader_slogan', __('Turning big ideas into great services!', 'fossil'));
    $fossil_header_social = get_theme_mod('fossil_header_social', 1);
    $social_links = [
        'facebook' => [
            'url'   => get_theme_mod('fossil_hsocial_facebook'),
            'icon'  => 'fab fa-facebook-f',
            'label' => esc_html__('Facebook', 'fossil')
        ],
        'instagram' => [
            'url'   => get_theme_mod('fossil_hsocial_instagram'),
            'icon'  => 'fab fa-instagram',
            'label' => esc_html__('Instagram', 'fossil')
        ],
        'twitter' => [
            'url'   => get_theme_mod('fossil_hsocial_twitter'),
            'icon'  => 'fab fa-twitter',
            'label' => esc_html__('Twitter', 'fossil')
        ],
        'skype' => [
            'url'   => get_theme_mod('fossil_hsocial_skype'),
            'icon'  => 'fab fa-skype',
            'label' => esc_html__('Skype', 'fossil')
        ]
    ];

    if (empty($fossil_topheader_show)) {
        return;
    }

?>
    <div class="header-top <?php echo esc_attr($topbg); ?>">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="contact-info">
                        <div class="contact-info-item">
                            <i class="far fa-envelope"></i> <?php echo esc_html($fossil_topheader_email); ?>
                        </div>
                        <div class="contact-info-item">
                            <i class="fas fa-map-marker-alt"></i><?php echo esc_html($fossil_topheader_address); ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 text-end">
                    <div class="site-info">
                        <?php echo esc_html($fossil_topheader_slogan); ?>
                        <?php if ($fossil_header_social): ?>
                            <div class="social-area">
                                <?php
                                foreach ($social_links as $social) {
                                    if (!empty($social['url'])) {
                                        printf(
                                            '<a href="%1$s" aria-label="%2$s"><i class="%3$s"></i></a>',
                                            esc_url($social['url']),
                                            esc_attr($social['label']),
                                            esc_attr($social['icon'])
                                        );
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
add_action('fossil_header_top_part', 'fossil_header_top_part_output');
/**
 * Fossil Header Top Part
 *
 * @package Fossil
 */

function fossil_logo_part_output()
{
?>
    <div class="site-branding">
        <div class="logo">
            <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    echo '<h1 class="site-title">' . esc_html(get_bloginfo('name')) . '</h1>';
                }
                ?>
            </a>
            <?php
            $fossil_show_tagline = get_theme_mod('fossil_show_tagline');
            $fossil_description = get_bloginfo('description', 'display');
            if (!empty($fossil_show_tagline) && ($fossil_description || is_customize_preview())) :
            ?>
                <p class="site-description">
                    <?php echo esc_html($fossil_description); ?>
                </p>
            <?php endif; ?>
        </div>
    </div><!-- .site-branding -->

<?php
}
add_action('fossil_logo_part', 'fossil_logo_part_output');

/**
 * Fossil Header Top Part
 *
 * @package Fossil
 */

function fossil_main_menu_output()
{
?>
    <div class="main-menu">
        <nav id="site-navigation" class="main-navigation navbar navbar-expand-lg">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'menu_id'        => 'fossil-eye-menu',
                'menu_class'        => 'fossil-eye-menu',
                'fallback_cb'     => '__return_false',
            ));
            ?>
        </nav><!-- #site-navigation -->
    </div><!-- .site-branding -->

<?php
}
add_action('fossil_main_menu', 'fossil_main_menu_output');

/**
 * Fossil Header Main Part
 *
 * @package Fossil
 */

function fossil_header_main_part_output()
{

    $fossil_show_search_icon = get_theme_mod('fossil_show_search_icon', 1);
    $fossil_show_header_button = get_theme_mod('fossil_show_header_button',1);
    $fossil_header_button_text = get_theme_mod('fossil_header_button_text', __('Get Quote', 'fossil'));
    $fossil_header_button_url = get_theme_mod('fossil_header_button_url', '#');

    if (empty($fossil_show_search_icon) && empty($fossil_show_header_button)) {
        $fossil_menuoutput_column = 'col-lg-9';
    } else {
        $fossil_menuoutput_column = 'col-lg-6';
    }
?>
    <div class="header-area">
        <div class="sticky-area">
            <div class="navigation">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-3">
                            <div class="logo-outside">
                                <?php do_action('fossil_logo_part'); ?>
                                <?php
                                if (has_nav_menu('main-menu')) {
                                    do_action('fossil_mobile_menu');
                                }
                                ?>
                            </div>
                        </div>
                        <div class="<?php echo esc_attr($fossil_menuoutput_column); ?>">
                            <?php do_action('fossil_main_menu'); ?>
                        </div>
                        <?php if ($fossil_show_search_icon || $fossil_show_header_button) : ?>
                            <div class="col-lg-3">
                                <div class="header-right-content">
                                    <?php if ($fossil_show_search_icon) : ?>
                                        <div class="search-box">
                                            <a class="search-btn"><i class="fas fa-search"></i></a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($fossil_show_header_button) : ?>
                                        <a href="<?php echo esc_url($fossil_header_button_url); ?>" class="main-btn"><?php echo esc_html($fossil_header_button_text); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
    do_action('fossil_header_search_popup');
}
add_action('fossil_header_main_part', 'fossil_header_main_part_output');


function fossile_header_search_popup_output()
{
    $fossil_show_search_icon = get_theme_mod('fossil_show_search_icon',1);
    $fossil_header_search_label = get_theme_mod('fossil_header_search_label', __('Search for anything.', 'fossil'));
?>
    <?php if ($fossil_show_search_icon) : ?>
        <!-- Search Dropdown Area -->
        <div class="search-popup">
            <span class="search-back-drop"></span>

            <div class="search-inner">
                <div class="auto-container">
                    <div class="upper-text">
                        <div class="text"><?php echo esc_html($fossil_header_search_label); ?></div>
                        <button class="close-search"><span class="fas fa-times"></span></button>
                    </div>
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php
}
add_action('fossil_header_search_popup', 'fossile_header_search_popup_output');


function fossil_header_top_style_two_output()
{
    $fossil_header_stwo_time_text = get_theme_mod('fossil_header_stwo_time_text');
    $fossil_header_stwo_place_text = get_theme_mod('fossil_header_stwo_place_text');
    $fossil_header_stwo_info_text = get_theme_mod('fossil_header_stwo_info_text');

?>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <div class="logo-outside">
                    <?php do_action('fossil_logo_part'); ?>
                    <?php do_action('fossil_mobile_menu'); ?>
                </div>

            </div>

            <div class="col-lg-9 text-end">
                <div id="header-aside">
                    <div class="aside-content">
                        <div class="inner">
                            <?php if ($fossil_header_stwo_time_text): ?>
                                <div class="info-one">
                                    <div class="info-wrap">
                                        <div class="info-i"><span><i class="far fa-clock"></i></span></div>
                                        <div class="info-c">
                                            <?php echo fossil_sanitize_lite_html($fossil_header_stwo_time_text); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fossil_header_stwo_place_text): ?>
                                <div class="info-two">
                                    <div class="info-wrap">
                                        <div class="info-i"><span><i class="fas fa-map-marker-alt"></i></span></div>
                                        <div class="info-c">
                                            <?php echo fossil_sanitize_lite_html($fossil_header_stwo_place_text); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($fossil_header_stwo_info_text): ?>
                                <div class="info-three">
                                    <div class="info-wrap">
                                        <div class="info-i"><span><i class="fas fa-mobile-alt"></i></span></div>
                                        <div class="info-c">
                                            <?php echo fossil_sanitize_lite_html($fossil_header_stwo_info_text); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
}
add_action('fossil_header_top_style_two', 'fossil_header_top_style_two_output');

function fossil_header_menubar_style_two_output()
{
    $fossil_show_search_icon = get_theme_mod('fossil_show_search_icon');
    $fossil_show_header_button = get_theme_mod('fossil_show_header_button');
    $fossil_header_button_text = get_theme_mod('fossil_header_button_text', __('Get Quote', 'fossil'));
    $fossil_header_button_url = get_theme_mod('fossil_header_button_url', '#');

?>

    <div class="fossil-hstyle2 navigation">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-9">
                    <?php do_action('fossil_main_menu'); ?>
                </div>
                <div class="col-lg-3 text-end">
                    <div class="header-right-content">
                        <?php if ($fossil_show_search_icon) : ?>
                            <div class="search-box">
                                <a class="search-btn"><i class="fas fa-search"></i></a>
                            </div>
                        <?php endif; ?>
                        <?php if ($fossil_show_header_button) : ?>
                            <a href="<?php echo esc_url($fossil_header_button_url); ?>" class="header-btn main-btn"><?php echo esc_html($fossil_header_button_text); ?></a>
                        <?php endif; ?>

                    </div>
                </div>
                <!-- Responsive Logo-->
                <div class="col-md-4 col-sm-4">
                    <div class="responsive-logo">
                        <?php do_action('fossil_logo_part'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php
    do_action('fossil_header_search_popup');
}
add_action('fossil_header_menubar_style_two', 'fossil_header_menubar_style_two_output');


// Blog Rich mobile menu
function fossil_mobile_menu_output()
{
?>
    <div class="mobile-menu-bar">
        <div class="container">
            <nav id="mobile-navigation" class="mobile-navigation">
                <button id="mmenu-btn" class="menu-btn" aria-expanded="false">
                    <span class="mopen"><i class="fas fa-bars"></i></span>
                    <span class="mclose"><i class="fas fa-times"></i></span>
                </button>
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'main-menu',
                    'menu_id'        => 'wsm-menu',
                    'menu_class'        => 'wsm-menu',
                    'fallback_cb'     => '__return_false',
                ));
                ?>
            </nav><!-- #site-navigation -->
        </div>
    </div>

<?php
}
add_action('fossil_mobile_menu', 'fossil_mobile_menu_output');
