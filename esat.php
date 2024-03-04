<?php
/*
* Plugin Name: ESAT - Essential Settings And Tools
* Plugin URI: https://sentientbit.com/esat
* Description: ESAT plugin revolutionizes your website management, effortlessly streamlining your tasks and saving you valuable time. With ESAT, unlocking advanced functionalities and enhancements is a breeze, eliminating the need for multiple plugins. Elevate your website to new heights of efficiency and effectiveness with ESAT by your side. If you need all ESAT Pro features, including dedicated support, we encourage you to <a href="https://sentientbit.com/plugins/esat" rel="nofollow" target="_blank">purchase ESAT Pro</a>.
* Version: 1.0.2
* Author: Sentient Bit team
* Author URI: https://sentientbit.com
* Text Domain: esat
* Requires at least: 6.0
* Requires PHP: 7.4
* License: GPLv2
* License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// =============================================================================
// Don't load directly
// =============================================================================
if (!defined('ABSPATH')) {
    die('
        <div style="height: 100%; display: flex; justify-content: center; align-items: center;">
            <h1 style="font-size: 100px;">You cannot be here!<h1>
        </div>
    ');
}

// =============================================================================
// Plugin constants
// =============================================================================
if (!defined('ESAT_VERSION')) define('ESAT_VERSION', '1.0.2');
if (!defined('ESAT_DATABASE_VERSION')) define('ESAT_DATABASE_VERSION', '1.0.0');

define('ESAT_FOLDER', 'essential-settings-and-tools');
define('ESAT_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('ESAT_DIR', plugin_dir_path(__FILE__));
define('ESAT_BASENAME', plugin_basename(__FILE__));

if (!class_exists('ESAT_start')) {

	class ESAT_start
	{
		private static $instance;

		private function __construct()
		{
			register_activation_hook(__FILE__, array('ESAT_start', 'esat_check_versions'));
			//add_action( 'activate_plugin', 'esat_api_connector_checks', 10, 2 );
			add_action('wp_dashboard_setup', array($this, 'esat_dashboard_widget'));
			
			register_deactivation_hook(__FILE__, array('ESAT_start', 'esat_deactivation_feedback_plugin'));
		}

		public static function get_instance()
		{
			if (!isset(self::$instance)) {
				self::$instance = new self();
			}
			return self::$instance;
		}
		
		// =====================================================================
		// Main file
		// =====================================================================
		public function include_esat_start()
		{
			require_once ESAT_DIR . '/core/esat-core.php';
		}
	// =============================================================================
	// Feedback notice
	// @since 1.0.0
	// =============================================================================
	public static function esat_display_feedback_notice()
	{
        if (basename($_SERVER['SCRIPT_FILENAME']) !== 'index.php') {
            require_once ESAT_DIR . '/core/templates/esat-notice-feedback.php';
        }
	}
		// -----------------------------------------------------------------------------
		// Perform checks before the plugin activation
		// -----------------------------------------------------------------------------
		public static function esat_check_min_php_version()
		{
			$min_php_version = '7.4';

			if (version_compare(PHP_VERSION, $min_php_version, '<')) {
				$error_message = sprintf(__('ESAT requires PHP version %1$s or greater. Older versions of PHP are no longer supported. Your current version of PHP is %2$s.', 'esat'), $min_php_version, PHP_VERSION);
				echo '<div class="error" style="padding: 300px;"><p>' . $error_message . '</p></div>';
				return false;
			}

			return true;
		}

		// -----------------------------------------------------------------------------
		// Check for the minimum WordPress version (@return bool)
		// -----------------------------------------------------------------------------
		public static function esat_check_min_wp_version()
		{
			$min_wp_version = '6.0';

			if (version_compare(get_bloginfo('version'), $min_wp_version, '<')) {
				return false;
			}

			return true;
		}

		// =============================================================================
		// Activation
		// =============================================================================
		public static function esat_check_versions()
		{
			if (!self::esat_check_min_php_version() || !self::esat_check_min_wp_version()) {
				wp_redirect(add_query_arg(array('esat_error' => '1'), admin_url('plugins.php')));
				exit;
			}
		}

		// =============================================================================
		// Plugin dashboard gadget
		// =============================================================================
		public static function esat_dashboard_widget()
		{
			wp_add_dashboard_widget(
				'esat_dashboard_widget',
				'ESAT - Essential Settings and Tools Overview',
				array('ESAT_start', 'esat_dashboard_widget_callback')
			);
		}

		public static function esat_dashboard_widget_callback()
		{
			include ESAT_DIR . '/core/templates/esat-dashboard-gadget.php';
		}



		

		// =============================================================================
		// Update
		// =============================================================================
		public function esat_update_plugin()
		{
			// Verificați actualizările folosind API-ul
			$api_url = 'https://sentientbit.com/api/essential-settings-and-tools-api.php';

			$response = wp_remote_get($api_url . '?version=' . ESAT_VERSION);
			if (!is_wp_error($response)) {
				$body = wp_remote_retrieve_body($response);
				$data = json_decode($body, true);

				if (version_compare($current_version, $data['version'], '<')) {
					// Descărcați și instalați actualizarea
					$download_url = $data['download_url'];
					$downloaded = download_url($download_url);

					if (!is_wp_error($downloaded)) {
						$temp_file = $downloaded;
						$plugin_slug = 'nume-plugin'; // Înlocuiți cu numele real al pluginului
						$plugin_dir = WP_PLUGIN_DIR . '/' . $plugin_slug;
						$plugin_file = $plugin_dir . '/plugin-file.php'; // Înlocuiți cu fișierul real al pluginului

						WP_Filesystem();
						$result = unzip_file($temp_file, $plugin_dir);

						if (!is_wp_error($result)) {
							// Actualizare reușită
							echo 'Pluginul a fost actualizat cu succes!';
						} else {
							echo 'Eroare la dezarhivarea fișierului: ' . $result->get_error_message();
						}
					} else {
						echo 'Eroare la descărcarea fișierului: ' . $downloaded->get_error_message();
					}
				} else {
					echo 'Pluginul este actualizat la cea mai recentă versiune.';
				}
			} else {
				echo 'Eroare la conectarea la API: ' . $response->get_error_message();
			}
		}

		// =============================================================================
		// Deactivation
		// =============================================================================
		public static function esat_deactivation_feedback_plugin()
		{
			wp_redirect(ESAT_URL . 'templates/esat-before-you-go.php');
			exit;
		}
	}

	ESAT_start::get_instance()->include_esat_start();
}
