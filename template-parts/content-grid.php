<?php

/**
 * Post Grid Template
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fossil
 */
$fossil_blog_layout = get_theme_mod('fossil_blog_layout', 'fullwidth');

if (is_active_sidebar('sidebar-1') && $fossil_blog_layout != 'fullwidth') {
    $fossile_grid_column = 'col-lg-6';
} else {
    $fossile_grid_column = 'col-lg-4 col-md-6 col-sm-12';
}


$fossil_redmore_text = get_theme_mod('fossil_redmore_text', esc_html__('Read More', 'fossil'));
$fossil_post_meta = get_theme_mod('fossil_post_meta', 1);

?>
<div class="<?php echo esc_attr($fossile_grid_column); ?> grid-item">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="single-blog-item mt-40">
            <div class="blog-bg">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail(); ?>
                </a>
            </div>
            <div class="blog-content">
                <?php
                if ($fossil_post_meta) {
                    echo fossil_post_meta();
                }
                ?>
                <h5>
                    <a href="<?php the_permalink(); ?>">
                        <?php the_title(); ?>
                    </a>
                </h5>
                <?php the_excerpt(); ?>
                <a href="<?php the_permalink(); ?>" class="read-more">
                    <?php echo esc_html($fossil_redmore_text); ?>
                </a>
            </div>
        </div>
    </article>
</div>