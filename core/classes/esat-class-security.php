<?php

namespace ESAT\Classes;

// =============================================================================
// Class related to Security features
// @since 1.0.0
// =============================================================================

if (!class_exists('ESAT_Security')) {
    
    class ESAT_Security {
        private static $instance;
    
        private function __construct() {
            add_shortcode('wordpress_version_check', 'check_wordpress_version_shortcode');
        }
        
        public static function get_instance() {
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
        
        // ---------------------------------------------------------------------
        // Check if installed WP version is the latest
        // ---------------------------------------------------------------------
        public function check_wordpress_version_shortcode() {
            // Obține versiunea curentă a WordPress-ului
            $current_version = get_bloginfo('version');
        
            // URL-ul către API-ul oficial al WordPress pentru a obține cea mai recentă versiune
            $api_url = 'https://api.wordpress.org/core/version-check/1.7/';
        
            // Obțineți datele JSON de la URL-ul API-ului
            $response = wp_remote_get($api_url);
        
            // Verificați dacă solicitarea API a avut succes
            if (!is_wp_error($response) && $response['response']['code'] == 200) {
                // Decodează datele JSON într-un obiect PHP
                $data = json_decode($response['body']);
        
                // Verifică dacă există o versiune nouă disponibilă
                if (isset($data->offers[0]->current)) {
                    $latest_version = $data->offers[0]->current;
        
                    // Comparați versiunile
                    if (version_compare($latest_version, $current_version, '>')) {
                        return 'Versiunea curentă a WordPress-ului este depășită. Versiunea cea mai recentă este: ' . $latest_version;
                    } else {
                        return 'Versiunea WordPress-ului este actualizată.';
                    }
                } else {
                    return 'Nu s-a putut obține versiunea cea mai recentă.';
                }
            } else {
                return 'Eroare la solicitarea API-ului.';
            }
        } 
    }
    
    ESAT_Security::get_instance();
    
}
