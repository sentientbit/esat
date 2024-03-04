<?php

namespace ESAT\Classes;

// =============================================================================
// Class related to Security features
// @since 1.0.0
// =============================================================================

class Security
{
    /**
    * Function description
    * 
    * @since 1.0.0
    */
}



if (!class_exists('ESAT_Functions')) {
    
    /* Start of class ESAT_Functions */
    class ESAT_Functions {
        private static $instance;
    
        private function __construct() {
            /* Security */
            add_shortcode('wordpress_version_check', 'check_wordpress_version_shortcode');
            
            /* Customization */
            add_filter('admin_footer_text', array($this, 'custom_footer_message'), 10, 2);
        }
        
        public static function get_instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }
    
        // =====================================================================
        // Security
        // =====================================================================
        
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
    
        // =====================================================================
        // Customization
        // =====================================================================
        
        // ---------------------------------------------------------------------
        // Replace footer message on admin panel
        // ---------------------------------------------------------------------
        public function custom_footer_message( $text ) {
            $esat_custom_footer_message = "Salut! Mesajul tău personalizat aici.";
        
            return $esat_custom_footer_message;
        }
        
    }
    /* End of class ESAT_Functions */
    
    ESAT_Functions::get_instance();
    
}
