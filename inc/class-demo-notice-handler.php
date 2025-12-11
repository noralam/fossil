<?php

/**
 * Upgrade Pro Notice Handler Class
 *
 * Handles the display and dismissal of admin notices related to upgrading to Pro version.
 *
 * @package Fossil
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Upgrade Pro Notice Handler Class
 */
class Fossil_Demo_Notice_Handler
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Hook into admin_notices to display our custom notice.
		add_action('admin_notices', array($this, 'display_upgrade_notice'));

		// Register AJAX action for dismissing the notice.
		add_action('wp_ajax_fossil_dismiss_upgrade_notice', array($this, 'dismiss_upgrade_notice'));

		// Enqueue scripts and styles.
		add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
	}

	/**
	 * Check if the notice has been dismissed
	 *
	 * @return boolean True if notice is dismissed, false otherwise.
	 */
	private function is_notice_dismissed()
	{
		return get_option('fossil_upgrade_dismissed_notice_info', false);
	}

	/**
	 * Enqueue necessary scripts and styles
	 */
	public function enqueue_scripts()
	{
		// Only enqueue on admin pages and if notice is not dismissed.
		if (is_admin() && ! $this->is_notice_dismissed()) {
			wp_enqueue_script(
				'fossil-upgrade-notice',
				get_template_directory_uri() . '/assets/js/demo-notice.js',
				array('jquery'),
				'1.0.0',
				true
			);

			wp_localize_script(
				'fossil-upgrade-notice',
				'fossilUpgradeNotice',
				array(
					'ajaxUrl' => admin_url('admin-ajax.php'),
					'nonce'   => wp_create_nonce('fossil_upgrade_notice_nonce'),
				)
			);

			wp_enqueue_style(
				'fossil-upgrade-notice',
				get_template_directory_uri() . '/assets/css/demo-notice.css',
				array(),
				'1.0.0'
			);

			// Add inline styles for compact, attractive notice with white background
			$custom_css = "
				.fossil-upgrade-notice {
					background: #ffffff !important;
					border-left: 4px solid #ff5f6d !important;
					padding: 10px 15px !important;
					margin: 10px 0 !important;
					box-shadow: 0 1px 3px rgba(0,0,0,0.08) !important;
				}
				
				.fossil-upgrade-notice-content {
					display: flex;
					align-items: center;
					gap: 15px;
					flex-wrap: wrap;
				}
				
				.fossil-badge {
					background: linear-gradient(135deg, #ff5f6d 0%, #ff8a5c 100%);
					color: #fff;
					padding: 3px 10px;
					border-radius: 3px;
					font-size: 11px;
					font-weight: 700;
					text-transform: uppercase;
					letter-spacing: 0.5px;
					white-space: nowrap;
				}
				
				.fossil-upgrade-notice-text {
					flex: 1;
					color: #444;
					font-size: 13px;
					line-height: 1.4;
					min-width: 200px;
				}
				
				.fossil-upgrade-notice-text strong {
					color: #1d2327;
					margin-right: 8px;
				}
				
				.fossil-feature {
					display: inline-block;
					margin-right: 12px;
					color: #2e7d32;
					font-size: 12px;
					white-space: nowrap;
				}
				
				.fossil-upgrade-notice-actions {
					display: flex;
					align-items: center;
					gap: 12px;
				}
				
				.fossil-upgrade-pro-btn {
					background: linear-gradient(135deg, #ff5f6d 0%, #c30010 100%) !important;
					color: #ffffff !important;
					border: none !important;
					font-weight: 600 !important;
					box-shadow: 0 2px 4px rgba(195,0,16,0.3) !important;
					transition: all 0.2s ease !important;
					padding: 6px 16px !important;
					font-size: 12px !important;
					border-radius: 3px !important;
					text-decoration: none !important;
					white-space: nowrap;
				}
				
				.fossil-upgrade-pro-btn:hover,
				.fossil-upgrade-pro-btn:focus {
					background: linear-gradient(135deg, #ff4c5d 0%, #a80000 100%) !important;
					color: #ffffff !important;
					transform: translateY(-1px);
					box-shadow: 0 3px 6px rgba(195,0,16,0.4) !important;
				}
				
				.fossil-dismiss-link {
					color: #999;
					text-decoration: none;
					font-size: 12px;
					white-space: nowrap;
				}
				
				.fossil-dismiss-link:hover {
					color: #666;
				}
				
				@media (max-width: 782px) {
					.fossil-upgrade-notice-content {
						flex-direction: column;
						align-items: flex-start;
						gap: 10px;
					}
					.fossil-upgrade-notice-actions {
						width: 100%;
						justify-content: space-between;
					}
				}
			";
			wp_add_inline_style('fossil-upgrade-notice', $custom_css);
		}
	}

	/**
	 * Display the upgrade notice
	 */
	public function display_upgrade_notice()
	{
		// Don't show the notice if it's been dismissed.
		if ($this->is_notice_dismissed()) {
			return;
		}

		// Get the current screen.
		$screen = get_current_screen();

		// Only show on dashboard, themes, and plugins pages.
		$allowed_screens = array('dashboard', 'themes', 'plugins');
		if (! $screen || ! in_array($screen->id, $allowed_screens, true)) {
			return;
		}

		// Pro version link
		$pro_link = 'https://wpthemespace.com/product/fossil-pro-best-wordpress-theme-for-ev-charging-and-gas-stations/?add-to-cart=13340';
?>
		<div class="notice fossil-upgrade-notice is-dismissible">
			<div class="fossil-upgrade-notice-content">
				<span class="fossil-badge">🔥 <?php esc_html_e('LIMITED OFFER', 'fossil'); ?></span>
				<div class="fossil-upgrade-notice-text">
					<strong><?php esc_html_e('Unlock Fossil Pro:', 'fossil'); ?></strong>
					<span class="fossil-feature">✓ <?php esc_html_e('One click Setup', 'fossil'); ?></span>
					<span class="fossil-feature">✓ <?php esc_html_e('More Security', 'fossil'); ?></span>
					<span class="fossil-feature">✓ <?php esc_html_e('Priority Support', 'fossil'); ?></span>
					<span class="fossil-feature">✓ <?php esc_html_e('Lifetime Updates', 'fossil'); ?></span>
					<span class="fossil-feature">✓ <?php esc_html_e('Responsive Design', 'fossil'); ?></span>
					<span class="fossil-feature">✓ <?php esc_html_e('Easy to edit', 'fossil'); ?></span>
				</div>
				<div class="fossil-upgrade-notice-actions">
					<a href="<?php echo esc_url($pro_link); ?>" class="button fossil-upgrade-pro-btn" target="_blank">
						<?php esc_html_e('Get Pro Now →', 'fossil'); ?>
					</a>
					<a href="#" class="fossil-dismiss-link"><?php esc_html_e('Dismiss', 'fossil'); ?></a>
				</div>
			</div>
		</div>
<?php
	}

	/**
	 * AJAX handler to dismiss the notice
	 */
	public function dismiss_upgrade_notice()
	{
		// Verify nonce.
		if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'fossil_upgrade_notice_nonce')) {
			wp_send_json_error('Invalid nonce');
			exit;
		}

		// Update option to mark notice as dismissed.
		update_option('fossil_upgrade_dismissed_notice_info', true);

		wp_send_json_success('Notice dismissed');
	}
}

// Initialize the class.
new Fossil_Demo_Notice_Handler();
