<?php

namespace ESAT\Classes;

/**
 * Plugin ESAT_Core
 *
 * @since 1.0.0
 */

if (!class_exists('ESAT_Core')) {

class ESAT_Core {
    private static $instance;

    private function __construct() {
        //self::load_esat_languages();
        /* bootstrap, css & js core */
        add_action('admin_enqueue_scripts', array($this, 'esat_enqueue_styles'));
        /* menu */
        add_action('admin_menu', array($this, 'esat_menu'));
        /* plugins page links */
        add_filter('plugin_action_links_' . ESAT_BASENAME, array($this, 'esat_settings_plugin_action_links'));
        add_filter('plugin_action_links_' . ESAT_BASENAME, array($this, 'esat_get_pro_plugin_action_links'));
        add_filter('plugin_row_meta', array($this, 'esat_about_plugin'), 10, 2);
        add_filter('plugin_row_meta', array($this, 'esat_docs_faqs_plugin'), 10, 2);
        add_action('admin_notices', array('ESAT_start', 'esat_display_feedback_notice'));
        add_action('admin_head', array($this, 'remove_other_plugins_notifications'));
        add_action('admin_enqueue_scripts', array($this, 'esat_bootstrap_style'));
        self::include_esat_files();
    }

    public static function get_instance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // =========================================================================
    // Plugin translation
    // @since 1.0.0
    // =========================================================================
    
    /*function load_esat_languages() {
        if (file_exists(ESAT_DIR . '/languages/esat-' . get_locale() . '.mo')) {
            load_plugin_textdomain('esat', false, ESAT_DIR . '/languages/');
        }
    }
    */



    // =========================================================================
    // Plugin initialization
    // =========================================================================

    public function include_esat_files() {
        if (file_exists(ESAT_DIR . '/core/esat-autoload.php')) {
            require_once ESAT_DIR . '/core/esat-autoload.php';
        }
        
        if (file_exists(ESAT_DIR . '/core/esat-functions.php')) {
            require_once ESAT_DIR . '/core/esat-functions.php';
        }
    }


    public function esat_bootstrap_style() {
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


    
    public static function esat_enqueue_styles() {
        // Bootstrap
        // -----------------------------------------------------------------------------
        
        
        // Font awesome
        // -----------------------------------------------------------------------------
        //wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', array(), '6.0.0-beta3');
        
        // Custom css an js
        // -----------------------------------------------------------------------------
        wp_enqueue_style('esat-admin-style', ESAT_URL . '/assets/admin/css/esat-admin-style.css', __FILE__);
        wp_enqueue_style('east-admin-components', ESAT_URL . '/assets/admin/css/esat-admin-components.css', __FILE__);
        
        wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-1.12.0.min.js', array(), null, false);
        wp_enqueue_script('esat-admin-script', ESAT_URL . '/assets/admin/js/esat-admin-components.js', __FILE__, array('jquery'), null, true);
    }


// =============================================================================
// Plugin menu
// =============================================================================
    public function esat_menu() {
        add_menu_page(
            'ESAT',
            'ESAT',
            'manage_options',
            'esat',
            array($this, 'esat_overview_submenu'),
            'data:image/svg+xml;base64,' . base64_encode( file_get_contents( ESAT_DIR . '/assets/admin/images/esat-icon.svg' ) ),
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
            <div class="esat-header-wrap">
                <div class="esat-header-wrap-title">
                    <img id="" src="' . ESAT_URL . 'assets/admin/images/esat-logo-h40.webp' . '" alt="ESAT - essential settings and tools icon">
                </div>
                
                <button type="button" class="esat-btn-get-pro">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-stars" viewBox="0 0 16 16"><path d="M7.657 6.247c.11-.33.576-.33.686 0l.645 1.937a2.89 2.89 0 0 0 1.829 1.828l1.936.645c.33.11.33.576 0 .686l-1.937.645a2.89 2.89 0 0 0-1.828 1.829l-.645 1.936a.361.361 0 0 1-.686 0l-.645-1.937a2.89 2.89 0 0 0-1.828-1.828l-1.937-.645a.361.361 0 0 1 0-.686l1.937-.645a2.89 2.89 0 0 0 1.828-1.828zM3.794 1.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387A1.73 1.73 0 0 0 4.593 5.69l-.387 1.162a.217.217 0 0 1-.412 0L3.407 5.69A1.73 1.73 0 0 0 2.31 4.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387A1.73 1.73 0 0 0 3.407 2.31zM10.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.16 1.16 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.16 1.16 0 0 0-.732-.732L9.1 2.137a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732z"/>
                    </svg><span class="ms-2">Unlock Features with ESAT PRO</span>
                </button>
            </div>
        ';
    }

    public function esat_overview_submenu() {
        $pageTitle = 'Overview';
        $this->esat_admin_header($pageTitle);
        if (file_exists(ESAT_DIR . '/core/templates/esat-page-overview.php')) {
            require_once ESAT_DIR . '/core/templates/esat-page-overview.php';
        }
    }
    
    public function esat_settings_submenu() {
        $pageTitle = 'Settings';
        $this->esat_admin_header($pageTitle);
        if (file_exists(ESAT_DIR . '/core/templates/esat-page-settings.php')) {
            require_once ESAT_DIR . '/core/templates/esat-page-settings.php';
        }
    }
    
    public function esat_about_us_submenu() {
        $pageTitle = 'About us';
        $this->esat_admin_header($pageTitle);
        if (file_exists(ESAT_DIR . '/core/templates/esat-page-about-us.php')) {
            require_once ESAT_DIR . '/core/templates/esat-page-about-us.php';
        }
    }
    
    public function esat_upgrade_to_pro_submenu() {
        $pageTitle = 'Upgrade to Pro';
        $this->esat_admin_header($pageTitle);
        if (file_exists(ESAT_DIR . '/core/templates/esat-page-upgrade-to-pro.php')) {
            require_once ESAT_DIR . '/core/templates/esat-page-upgrade-to-pro.php';
        }
    }

// =============================================================================
// Plugin dashboard gadget
// =============================================================================

    
// =============================================================================
// Plugin options and links on plugins page
// =============================================================================
    public function esat_settings_plugin_action_links($links) {
        $settings_url = admin_url('admin.php?page=Settings');
        $settings_link = '<a href="' . esc_url($settings_url) . '">Settings</a>';
        array_unshift($links, $settings_link);
        return $links;
    }
    
    public function esat_get_pro_plugin_action_links($links) {
        $settings_url = ('https://sentientbit.com/plugins/esat/get-pro');
        $links[] = '<a rel="nofollow" href="' . esc_url($settings_url) . '" class="esat-plugins-gopro" target="_blank">Get ESAT Pro</a>';
        return $links;
    }
    
    public function esat_about_plugin($links, $file) {
        if ($file == ESAT_BASENAME) {
            $links[] = '<a rel="nofollow" href="https://sentientbit.com/plugins/esat" target="_blank">View details</a>';
        }
        return $links;
    }
    
    public function esat_docs_faqs_plugin($links, $file) {
        if ($file == ESAT_BASENAME) {
            $links[] = '<a rel="nofollow" href="https://sentientbit.com/plugins/esat/faq" target="_blank">Docs & FAQs</a>';
        }
        return $links;
    }
    
    // Funcția pentru eliminarea notificărilor de la alte pluginuri
    public function remove_other_plugins_notifications() {
        $current_screen = get_current_screen();
        if ($current_screen && $current_screen->base === 'esat') {
            // Eliminăm notificările de la alte pluginuri folosind CSS sau JavaScript
            echo '<style>.update-nag, .notice, .error { display: none !important; }</style>';
        }
    }
}

ESAT_Core::get_instance();








// =============================================================================
// Plugin update
// =============================================================================
/*function esat_check_for_updates() {
    $update_url = 'https://sentientbit.com/wp-content/plugins/wp-sat/version.txt'; // URL-ul unde este disponibilă versiunea curentă a pluginului
    
    // Obține conținutul fișierului de versiune folosind wp_remote_get
    $response = wp_remote_get($update_url);
    
    // Verifică dacă răspunsul este primit cu succes
    if (!is_wp_error($response) && $response['response']['code'] === 200) {
        $latest_version = trim($response['body']);
        $installed_version = ESAT_VERSION; // Versiunea instalată a pluginului
        
        // Compară versiunile
        if (version_compare($latest_version, $installed_version, '>')) {
            // Dacă versiunea disponibilă este mai mare, afișează opțiunea de actualizare în panoul de administrare
            add_action('admin_notices', 'esat_display_update_notice');
        }
    }
}

// Funcție pentru afișarea notificării de actualizare
function esat_display_update_notice() {
    ?>
    <div class="notice notice-info is-dismissible">
        <p><?php _e('O nouă versiune a WP SAT este disponibilă! Vă rugăm să actualizați acum.', 'esat'); ?></p>
        <p><a href="<?php echo admin_url('update-core.php'); ?>" class="button button-primary"><?php _e('Actualizează acum', 'esat'); ?></a></p>
    </div>
    <?php
}

// Verificare actualizări la inițializarea adminului
add_action('admin_init', 'esat_check_for_updates');*/

// =============================================================================
// Plugin uninstall
// =============================================================================

}


use  WP_Admin_Bar ;
use  ArrayObject ;
use  NumberFormatter ;


    function modify_admin_bar_menu( $wp_admin_bar )
    {
        $options = get_option( ASENHA_SLUG_U, array() );
        // Hide WP Logo Menu
        
        if ( array_key_exists( 'hide_ab_wp_logo_menu', $options ) ) {
            remove_action( 'admin_bar_menu', 'wp_admin_bar_wp_menu', 10 );
            // priority needs to match default value. Use QM to reference.
        }
        
        // Hide Customize Menu
        
        if ( array_key_exists( 'hide_ab_customize_menu', $options ) && $options['hide_ab_customize_menu'] ) {
            remove_action( 'admin_bar_menu', 'wp_admin_bar_customize_menu', 40 );
            // priority needs to match default value. Use QM to reference.
        }
        
        // Hide Updates Counter/Link
        
        if ( array_key_exists( 'hide_ab_updates_menu', $options ) && $options['hide_ab_updates_menu'] ) {
            remove_action( 'admin_bar_menu', 'wp_admin_bar_updates_menu', 50 );
            // priority needs to match default value. Use QM to reference.
        }
        
        // Hide Comments Counter/Link
        
        if ( array_key_exists( 'hide_ab_comments_menu', $options ) && $options['hide_ab_comments_menu'] ) {
            remove_action( 'admin_bar_menu', 'wp_admin_bar_comments_menu', 60 );
            // priority needs to match default value. Use QM to reference.
        }
        
        // Hide New Content Menu
        
        if ( array_key_exists( 'hide_ab_new_content_menu', $options ) && $options['hide_ab_new_content_menu'] ) {
            remove_action( 'admin_bar_menu', 'wp_admin_bar_new_content_menu', 70 );
            // priority needs to match default value. Use QM to reference.
        }
        
        // Hide 'Howdy' text
        
        if ( array_key_exists( 'hide_ab_howdy', $options ) && $options['hide_ab_howdy'] ) {
            // Remove the whole my account sectino and later rebuild it
            remove_action( 'admin_bar_menu', 'wp_admin_bar_my_account_item', 7 );
            $current_user = wp_get_current_user();
            $user_id = get_current_user_id();
            $profile_url = get_edit_profile_url( $user_id );
            $avatar = get_avatar( $user_id, 26 );
            // size 26x26 pixels
            $display_name = $current_user->display_name;
            $class = ( $avatar ? 'with-avatar' : 'no-avatar' );
            $wp_admin_bar->add_menu( array(
                'id'     => 'my-account',
                'parent' => 'top-secondary',
                'title'  => $display_name . $avatar,
                'href'   => $profile_url,
                'meta'   => array(
                'class' => $class,
            ),
            ) );
        }
    
    }

    add_action( 'admin_bar_menu', 'modify_admin_bar_menu', 999 );




























/*function esat_enqueue_admin_styles() {
    /*$screen = get_current_screen();
    if ($screen && $screen->id === 'esat-dashboard') {*/
    /*if (is_admin()) {
        wp_enqueue_style('bootstrap-css-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
        wp_enqueue_style('east-admin-components', ESAT_URL . '/assets/admin/css/esat-admin-components.css', __FILE__);
    }
}
add_action('admin_enqueue_scripts', 'esat_enqueue_admin_styles');

function esat_enqueue_admin_public_styles() {
    wp_enqueue_style('esat-admin-style', ESAT_URL . '/assets/admin/css/esat-admin-style.css', __FILE__);
}
add_action('admin_enqueue_scripts', 'esat_enqueue_admin_public_styles');

function esat_enqueue_admin_scripts() {
    if (is_admin()) {
        wp_enqueue_script('bootstrap-js-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.0.2', true);
        //wp_enqueue_script('jquery', 'https://code.jquery.com/jquery-1.12.0.min.js', array(), null, false);
        //wp_enqueue_script('esat-admin-script', ESAT_URL . '/assets/admin/js/esat-admin-components.js', __FILE__, array('jquery'), null, true);
    }
}
add_action('admin_enqueue_scripts', 'esat_enqueue_admin_scripts');

function add_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css', array(), '6.0.0-beta3');
}
add_action('wp_enqueue_scripts', 'add_font_awesome');*/
