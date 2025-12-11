<?php

/**
 * Fossil Theme Breadcrumb Function
 *
 * @package Fossil
 */

if (!function_exists('fossil_breadcrumb')):
	/**
	 * Display theme breadcrumb
	 *
	 * @return void
	 */
	function fossil_breadcrumb()
	{
		// Exit if on front page
		if (is_front_page()) {
			return;
		}
?>
		<div class="breadcroumb-title">
			<h1 class="page-title">
				<?php
				if (is_home()) {
					single_post_title();
				} elseif (is_archive()) {
					the_archive_title();
				} elseif (is_search()) {
					/* translators: %s: search query */
					printf(esc_html__('Search Results for : %s', 'fossil'), get_search_query());
				} elseif (is_404()) {
					esc_html_e('404 Not Found', 'fossil');
				} else {
					the_title();
				}
				?>
			</h1>
			<h6>
				<a href="<?php echo esc_url(home_url('/')); ?>">
					<?php esc_html_e('Home', 'fossil'); ?>
				</a>
				<?php
				// Don't show just "/ Home" on homepage
				if (!is_front_page()) {
					echo ' / ';

					if (is_home()) {
						// Blog page
						echo esc_html__('Blog', 'fossil');
					} elseif (is_single()) {
						// Single post
						$categories = get_the_category();
						if (!empty($categories)) {
							echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">'
								. esc_html($categories[0]->name) . '</a> / ';
						}
						echo esc_html(wp_trim_words(get_the_title(), 3, '...'));
					} elseif (is_page()) {
						// Pages
						$ancestors = get_post_ancestors(get_the_ID());
						if ($ancestors) {
							$ancestors = array_reverse($ancestors);
							foreach ($ancestors as $ancestor) {
								echo '<a href="' . esc_url(get_permalink($ancestor)) . '">'
									. esc_html(get_the_title($ancestor)) . '</a> / ';
							}
						}
						the_title();
					} elseif (is_category()) {
						// Category archive
						single_cat_title();
					} elseif (is_tag()) {
						// Tag archive
						single_tag_title();
					} elseif (is_author()) {
						// Author archive
						the_author();
					} elseif (is_year() || is_month() || is_day()) {
						// Date archives
						if (is_year()) {
							echo get_the_date('Y');
						} elseif (is_month()) {
							echo get_the_date('F Y');
						} else {
							echo get_the_date('F j, Y');
						}
					} elseif (is_post_type_archive()) {
						// Custom post type archives
						post_type_archive_title();
					} elseif (is_tax()) {
						// Custom taxonomy archives
						single_term_title();
					} elseif (is_search()) {
						// Search results
						esc_html_e('Search Results', 'fossil');
					} elseif (is_404()) {
						// 404 page
						esc_html_e('404 Not Found', 'fossil');
					}
				}
				?>
			</h6>
		</div>
<?php
	}
endif;
