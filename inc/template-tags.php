<?php

/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Fossil
 */

if (! function_exists('fossil_posted_on')) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function fossil_posted_on()
	{
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date(DATE_W3C)),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date(DATE_W3C)),
			esc_html(get_the_modified_date())
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x('Posted on %s', 'post date', 'fossil'),
			'<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if (! function_exists('fossil_posted_by')) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function fossil_posted_by()
	{
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x('by %s', 'post author', 'fossil'),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

/**
 * Display post meta (author and date) with archive links
 *
 * @package Fossil
 */

if (!function_exists('fossil_post_meta')):
	function fossil_post_meta()
	{
		// Get author ID and name
		$author_id = get_the_author_meta('ID');
		$author_name = get_the_author();

		// Get post date information
		$year = get_post_time('Y');
		$month = get_post_time('m');
		$day = get_post_time('j');

		// Start output buffering
		ob_start();
?>
		<p class="blog-meta">
			<i class="far fa-user-circle"></i>
			<a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>"
				class="author-name"
				title="<?php echo esc_attr(sprintf(__('View all posts by %s', 'fossil'), $author_name)); ?>">
				<?php echo esc_html($author_name); ?>
			</a> |
			<i class="far fa-calendar-check"></i>
			<a href="<?php echo esc_url(get_day_link($year, $month, $day)); ?>"
				title="<?php echo esc_attr(get_the_date()); ?>">
				<?php echo esc_html(get_the_date('d M')); ?>
			</a>
		</p>
	<?php
		return ob_get_clean();
	}
endif;
if (!function_exists('fossil_single_blog_meta')):
	function fossil_single_blog_meta()
	{
		// Author information with secure linking
		$author_id = get_the_author_meta('ID');
		$author_name = get_the_author();
		$author_link = get_author_posts_url($author_id);

		// Get post date information
		$post_date = get_the_date();
		$year = get_the_date('Y');
		$month = get_the_date('m');
		$date_archive_link = get_month_link(
			$year,
			$month
		);

		// Comments count with proper escaping
		$comments_count = get_comments_number();
		$comments_link = get_comments_link();

		// Output meta information with security and accessibility
	?>
		<div class="blog-meta">
			<span>
				<i class="far fa-user" aria-hidden="true"></i>
				<a href="<?php echo esc_url($author_link); ?>" rel="author">
					<?php echo esc_html($author_name); ?>
				</a>
			</span>
			<span>
				<i class="far fa-calendar" aria-hidden="true"></i>
				<a href="<?php echo esc_url($date_archive_link); ?>" rel="bookmark">
					<time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
						<?php echo esc_html($post_date); ?>
					</time>
				</a>
			</span>
			<span>
				<i class="far fa-comments" aria-hidden="true"></i>
				<a href="<?php echo esc_url($comments_link); ?>">

					<?php
					/* translators: %s: number of comments */
					printf(
						esc_html(
							_n(
								'%s Comment', // Singular text
								'%s Comments', // Plural text
								$comments_count, // Number of comments
								'fossil'
							)
						),
						number_format_i18n($comments_count)
					);
					?>



				</a>
			</span>
		</div>
		<?php
	}
endif;


if (! function_exists('fossil_entry_footer')) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function fossil_entry_footer()
	{
		// Hide category and tag text for pages.
		if ('post' === get_post_type()) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list(esc_html__(', ', 'fossil'));
			if ($categories_list) {
				/* translators: 1: list of categories. */
				printf('<span class="cat-links">' . esc_html__('Posted in %1$s', 'fossil') . '</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'fossil'));
			if ($tags_list) {
				/* translators: 1: list of tags. */
				printf('<span class="tags-links">' . esc_html__('Tagged %1$s', 'fossil') . '</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if (! is_single() && ! post_password_required() && (comments_open() || get_comments_number())) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'fossil'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__('Edit <span class="screen-reader-text">%s</span>', 'fossil'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post(get_the_title())
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if (! function_exists('fossil_post_thumbnail')) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function fossil_post_thumbnail()
	{
		if (post_password_required() || is_attachment() || ! has_post_thumbnail()) {
			return;
		}

		if (is_singular()) :
		?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail(
					'post-thumbnail',
					array(
						'alt' => the_title_attribute(
							array(
								'echo' => false,
							)
						),
					)
				);
				?>
			</a>

<?php
		endif; // End is_singular().
	}
endif;

if (! function_exists('wp_body_open')) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open()
	{
		do_action('wp_body_open');
	}
endif;

function custom_avatar_size($args)
{
	$args['avatar_size'] = 90; // Set your desired avatar size here, e.g., 80 pixels.
	return $args;
}
add_filter('wp_list_comments_args', 'custom_avatar_size');
