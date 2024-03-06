<?php

// =============================================================================
// Class ESAT_Core
// @since 1.0.0
// =============================================================================

namespace ESAT\Classes;

if (!class_exists('ESAT_Core')) {

    class ESAT_Core
    {
        private static $instance;

        private function __construct()
        {
            // Languages
            /* self::load_esat_languages(); */
            // Includes
            self::include_esat_files();
            // Bootstrap, css & js
            add_action('admin_enqueue_scripts', array($this, 'esat_bootstrap_style'));
            add_action('admin_enqueue_scripts', array($this, 'esat_enqueue_styles'));
            // Menu
            add_action('admin_menu', array($this, 'esat_menu'));
            // Dashboar gadget
            add_action('wp_dashboard_setup', array($this, 'esat_dashboard_widget'));
            // Notices
            add_action('admin_notices', array('ESAT_start', 'esat_display_feedback_notice'));
            add_action('admin_head', array($this, 'remove_other_plugins_notifications'));
            // Plugins page links
            add_filter('plugin_action_links_' . ESAT_BASENAME, array($this, 'esat_settings_plugin_action_links'));
            add_filter('plugin_action_links_' . ESAT_BASENAME, array($this, 'esat_get_pro_plugin_action_links'));
            add_filter('plugin_row_meta', array($this, 'esat_docs_faqs_plugin'), 10, 2);
            // Update
            //add_action('admin_init', array($this, 'esat_check_for_updates'));
            //add_action('wp', array($this, 'check_github_update'));
            //add_action( 'after_plugin_row', array( $this, 'display_update_notification_in_plugins_page' ), 10, 3 );
            //add_action('wp', array($this, 'update_github_plugin'));
            
        }

        public static function get_instance()
        {
            if (!isset(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        
        /**
         * Perform checks before the plugin activation
         * 
         * @since 1.0.0
         */
        function esat_check_min_php_version() {
            $min_php_version = '7.4';
        
            if (version_compare(PHP_VERSION, $min_php_version, '<')) {
            	$error_message = sprintf(__('ESAT requires PHP version %1$s or greater. Older versions of PHP are no longer supported. Your current version of PHP is %2$s.', 'esat'), $min_php_version, PHP_VERSION);
            	echo '<div class="error" style="padding: 300px;"><p>' . $error_message . '</p></div>';
            	return false;
            }
            
            return true;
        }
        
        /**
         * Check for the minimum WordPress version (@return bool)
         * 
         * @since 1.0.2
         */
        function esat_check_min_wp_version() {
            $min_wp_version = '6.0';
            
            if (version_compare(get_bloginfo('version'), $min_wp_version, '<')) {
            	return false;
            }
            
            return true;
        }
        
        /**
        * Activation
        * 
        * @since 1.0.2
        */
        /*function esat_check_versions() {
            if (!self::esat_check_min_php_version() || !self::esat_check_min_wp_version()) {
            	wp_redirect(add_query_arg(array('esat_error' => '1'), admin_url('plugins.php')));
            	exit;
            }
        }*/
        
        /**
         * Languages
         * 
         * @since 1.0.0
         */
        /*function load_esat_languages() {
            if (file_exists(ESAT_DIR . '/languages/esat-' . get_locale() . '.mo')) {
                load_plugin_textdomain('esat', false, ESAT_DIR . '/languages/');
            }
        }*/
        
        /**
         * Includes
         * 
         * @since 1.0.0
         */
        public function include_esat_files()
        {
            /*if (file_exists(ESAT_DIR . '/core/esat-autoload.php')) {
            require_once ESAT_DIR . '/core/esat-autoload.php';
        }*/
        }

        /**
         * Bootstrap style
         * 
         * @since 1.0.0
         */
        public function esat_bootstrap_style()
        {
            $screen = get_current_screen();
            $allowed_pages = array(
                'toplevel_page_esat',
                'esat_page_esat-settings',
                'esat_page_esat-about-us',
                'esat_page_esat-upgrade-to-pro'
            );

            if (in_array($screen->id, $allowed_pages)) {
                wp_enqueue_style('bootstrap-css-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
                wp_enqueue_script('bootstrap-js-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.0.2', true);
            }
        }

        /**
         * Styles and scripts
         * 
         * @since 1.0.1
         */
        public static function esat_enqueue_styles()
        {
            // Font awesome
            // -----------------------------------------------------------------------------
            //wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', array(), '6.0.0-beta3');

            /**
             * Admin css and js
             */
            wp_enqueue_style('esat-admin-style', ESAT_URL . '/assets/admin/css/esat-admin-style.css', __FILE__);
            wp_enqueue_style('east-admin-components', ESAT_URL . '/assets/admin/css/esat-admin-components.css', __FILE__);
            wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-1.12.0.min.js', array(), null, false);
            wp_enqueue_script('esat-admin-components', ESAT_URL . '/assets/admin/js/esat-admin-components.js', __FILE__, array('jquery'), null, true);

            /**
             * Public css and js
             */
            //wp_enqueue_style('esat-public-style', ESAT_URL . '/assets/public/css/esat-public-style.css', __FILE__);
            wp_enqueue_style('east-public-components', ESAT_URL . '/assets/public/css/esat-public-components.css', __FILE__);
            wp_enqueue_script('esat-public-components', ESAT_URL . '/assets/public/js/esat-public-components.js', __FILE__, array('jquery'), null, true);
        }

        /**
         * Menu
         * 
         * @since 1.0.0
         */
        public function esat_menu()
        {
            add_menu_page(
                'ESAT',
                'ESAT',
                'manage_options',
                'esat',
                array($this, 'esat_overview_submenu'),
                'data:image/svg+xml;base64,' . base64_encode(file_get_contents(ESAT_DIR . '/assets/admin/images/esat-icon.svg')),
                99
            );
            add_submenu_page(
                'esat',
                'Overview',
                'Overview',
                'manage_options',
                'esat',
                'esat_overview_submenu'
            );
            add_submenu_page(
                'esat',
                'Settings',
                'Settings',
                'manage_options',
                'esat-settings',
                array($this, 'esat_settings_submenu')
            );
            add_submenu_page(
                'esat',
                'About us',
                'About us',
                'manage_options',
                'esat-about-us',
                array($this, 'esat_about_us_submenu')
            );
            add_submenu_page(
                'esat',
                'Upgrade to Pro',
                'Upgrade to Pro',
                'manage_options',
                'esat-upgrade-to-pro',
                array($this, 'esat_upgrade_to_pro_submenu')
            );
        }

        public function esat_admin_header($pageTitle)
        {
            echo '
            <div class="esat-header-container">
                <div class="esat-header-container-title">
                    <img id="" src="' . ESAT_URL . 'assets/admin/images/esat-logo-h40.webp' . '" alt="ESAT - essential settings and tools icon">
                </div>
                
                <button type="button" class="esat-btn-get-pro">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16"><path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                    </svg><span class="ms-2">Unlock Features with ESAT PRO</span>
                </button>
            </div>
        ';
        }

        public function esat_overview_submenu()
        {
            ?><div class="esat-container"><?php
            $pageTitle = 'Overview';
            $this->esat_admin_header($pageTitle);
            if (file_exists(ESAT_DIR . '/core/templates/esat-page-overview.php')) {
                require_once ESAT_DIR . '/core/templates/esat-page-overview.php';
            }
            ?></div><?php
        }

        public function esat_settings_submenu()
        {
            ?><div class="esat-container"><?php
            $pageTitle = 'Settings';
            $this->esat_admin_header($pageTitle);
            if (file_exists(ESAT_DIR . '/core/templates/esat-page-settings.php')) {
                require_once ESAT_DIR . '/core/templates/esat-page-settings.php';
            }
            ?></div><?php
        }

        public function esat_about_us_submenu()
        {
            ?><div class="esat-container"><?php
            $pageTitle = 'About us';
            $this->esat_admin_header($pageTitle);
            if (file_exists(ESAT_DIR . '/core/templates/esat-page-about-us.php')) {
                require_once ESAT_DIR . '/core/templates/esat-page-about-us.php';
            }
            ?></div><?php
        }

        public function esat_upgrade_to_pro_submenu()
        {
            ?><div class="esat-container"><?php
            $pageTitle = 'Upgrade to Pro';
            $this->esat_admin_header($pageTitle);
            if (file_exists(ESAT_DIR . '/core/templates/esat-page-upgrade-to-pro.php')) {
                require_once ESAT_DIR . '/core/templates/esat-page-upgrade-to-pro.php';
            }
            ?></div><?php
        }

        /**
         * Dashboard widget
         * 
         * @since 1.0.1
         */
        public function esat_dashboard_widget() {
            wp_add_dashboard_widget(
            	'esat_dashboard_widget',
            	'ESAT - Essential Settings and Tools Overview',
            	'esat_dashboard_widget_callback'
            );
        }
        
        public function esat_dashboard_widget_callback() {
            include ESAT_DIR . '/core/templates/esat-dashboard-widget.php';
        }
        
        /**
         * Feedback notice
         * @since 1.0.0
         */
        public function esat_display_feedback_notice()
        {
            if (basename($_SERVER['SCRIPT_FILENAME']) !== 'index.php') {
            	require_once ESAT_DIR . '/core/templates/esat-notice-feedback.php';
            }
        }
        
        /**
         * Remove all notices on plugin page
         * 
         * @since 1.0.0
         */
        public function remove_other_plugins_notifications()
        {
            $current_screen = get_current_screen();
            if ($current_screen && $current_screen->base === 'esat') {
                // Eliminăm notificările de la alte pluginuri folosind CSS sau JavaScript
                echo '<style>.update-nag, .notice, .error { display: none !important; }</style>';
            }
        }

        /**
         * Options and links on plugins page
         * 
         * @since 1.0.0
         */
        public function esat_settings_plugin_action_links($links)
        {
            $settings_url = admin_url('admin.php?page=esat-settings');
            $settings_link = '<a href="' . esc_url($settings_url) . '">Settings</a>';
            array_unshift($links, $settings_link);
            return $links;
        }

        public function esat_get_pro_plugin_action_links($links)
        {
            $settings_url = ('https://sentientbit.com/plugins/esat/get-pro');
            $links[] = '<a rel="nofollow" href="' . esc_url($settings_url) . '" class="esat-plugins-gopro" target="_blank">Get ESAT Pro</a>';
            return $links;
        }

        public function esat_docs_faqs_plugin($links, $file)
        {
            if ($file == ESAT_BASENAME) {
                $links[] = '<a rel="nofollow" href="https://sentientbit.com/plugins/esat/#faq" target="_blank">FAQs</a>';
            }
            return $links;
        }

        /**
         * Update
         * 
         * @since 1.0.2
         */
        /*public function esat_check_for_updates()
        {
            if (!function_exists('wp_remote_get') || !function_exists('wp_update_plugin')) {
                return;
            }

            $current_version = ESAT_VERSION;
            $github_api_url = 'https://api.github.com/repos/sentientbit/esat/releases/latest';

            $response = wp_remote_get($github_api_url);

            if (is_wp_error($response)) {
                return;
            }

            $release_data = json_decode(wp_remote_retrieve_body($response), true);

            if (version_compare($release_data['tag_name'], $current_version, '>')) {
                $download_url = $release_data['zipball_url'];
                $plugin_slug = 'esat/esat.php';
                $plugin_zip = download_url($download_url);

                if (!is_wp_error($plugin_zip)) {
                    $plugin = array(
                        'plugin' => $plugin_slug,
                        'new_version' => $release_data['tag_name'],
                        'package' => $plugin_zip,
                    );

                    wp_update_plugin($plugin);
                }
            }
        }*/
        /*public function check_github_update() {
            
            // URL-ul către API-ul GitHub pentru obținerea informațiilor despre ultima versiune
            $current_version = ESAT_VERSION;
            $github_api_url = 'https://api.github.com/repos/sentientbit/esat/releases/latest';
            
            // Facem o solicitare GET către API-ul GitHub
            $response = wp_remote_get( $github_api_url );
            
            // Verificăm dacă solicitarea a fost cu succes și procesăm răspunsul
            if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
                
                $body = wp_remote_retrieve_body( $response );
                $data = json_decode( $body );
                
                // Obținem ultima versiune disponibilă din răspunsul API-ului GitHub
                $latest_version = $data->tag_name;
                
                // Comparăm versiunea curentă a plugin-ului cu ultima versiune disponibilă
                if ( version_compare( '$current_version', $latest_version, '<' ) ) {
                    
                }
            }
        }*/
        
        /*public function display_update_notification_in_plugins_page() {
            echo "<tr class='plugin-update-tr active' id='esat-update' data-slug='esat' data-plugin='esat/esat.php' style='border-top: none;'>
                <td colspan='4' class='plugin-update colspanchange'>
                    <div class='update-message notice inline notice-warning notice-alt'>
                        <p>There is a new version of ESAT available. <a href='https://wft.sentientbit.com/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=esat&amp;section=changelog&amp;TB_iframe=true&amp;width=772&amp;height=851' class='thickbox open-plugin-details-modal' aria-label='View Esat version " . $latest_version . " details'>View version " . $latest_version . " details</a> or <a href='https://sentientbit.com/wp-admin/update.php?action=upgrade-plugin&amp;plugin=essential-settings-and-tools%2Fesat.php&amp;_wpnonce=2a07633cc9' class='update-link' aria-label='Update ESAT now'>update now</a>.
                        </p>
                    </div>
                </td>
            </tr>";
        }*/
        
        /*public function update_github_plugin() {
            $plugin_slug = 'esat';
            $plugin_path = sprintf( 'https://api.github.com/repos/sentientbit/esat/releases/latest', $plugin_slug );
            $upgrader = new Plugin_Upgrader( new WP_Upgrader_Skin() );
            $upgrader->install( $plugin_path );
        }*/
        
        /*public function display_update_notification_in_plugins_page2() {
            echo '<tr class="update"><td colspan="5"><strong>O actualizare este disponibilă pentru ESAT. <a href="#">Actualizează acum</a></strong></td></tr>';
        }*/

        /*public function display_update_notification_in_plugins_page3( $plugin_file, $plugin_data, $status ) {
            // Verificăm dacă plugin-ul are o actualizare disponibilă
            if ( $status['update'] && version_compare( $status['update'], $plugin_data['Version'], '>' ) ) {
                // Afișăm notificarea
                echo '<tr class="update" style="background-color: #f7f7f7;"><td colspan="4">';
                echo '<strong>O actualizare este disponibilă pentru acest plugin. <a href="#">Actualizează acum</a></strong>';
                echo '</td></tr>';
            }
        }*/
        
        
        /**
        * Deactivation
        * 
        * @since 1.0.2
        */
        /*function esat_deactivation() {
            wp_redirect(ESAT_URL . 'templates/esat-before-you-go.php');
            exit;
        }*/

        /**
         * Uninstall
         * 
         * @since 1.0.3
         */
         /*function esat_delete() {
        }*/
    }

    ESAT_Core::get_instance();
}


        
        
