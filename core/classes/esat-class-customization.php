<?php

namespace ESAT\Classes;

// =============================================================================
// Class related to ESAT_Customization features
// @since 1.0.0
// =============================================================================

if (!class_exists('ESAT_Customization')) {
    
    class ESAT_Customization
    {
        private static $instance;

        private function __construct() {
            add_action('wp_body_open', array($this, 'esat_display_preloader'));
            add_action('admin_footer', array($this, 'esat_scroll_to_top_button'));
            add_filter('admin_footer_text', array($this, 'esat_footer_message'), 10, 2);
        }

        public static function get_instance() {
            if (!isset(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Add preloader
         * 
         * @since 1.0.3
         */
        public function esat_display_preloader() {
            echo '<div class="eset-preloader"><div class="eset-spinner"></div></div>';
        }
        
        /**
         * Scroll to top
         * 
         * @since 1.0.3
         */
        public function esat_scroll_to_top_button() {
            echo '<button id="esat-scroll-to-top" class="button button-primary" style="display:none;">Scroll to Top</button>';
        }

        /**
         * Replace footer message on admin panel
         * 
         * @since 1.0.0
         */
        public function esat_footer_message($text) {
            $admin_footer_message = "Hi! Your custom message here.";
            return $admin_footer_message;
        }
    }
    
    // Adaugă filtrul la hook-ul 'admin_footer_text'
    ESAT_Customization::get_instance();
}

