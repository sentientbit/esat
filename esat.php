<?php

/*
 * Plugin Name: ESAT - Essential Settings And Tools
 * Plugin URI: https://sentientbit.com/plugins/esat
 * Description: ESAT plugin revolutionizes your website management, effortlessly streamlining your tasks and saving you valuable time. Unlocking advanced functionalities and enhancements is a breeze, eliminating the need for multiple plugins, elevateing your website to new heights of efficiency and effectiveness with ESAT by your side. If you need all ESAT Pro features, including dedicated support, we encourage you to purchase <a href="sentientbit.com/plugins/esat-pro">ESAT Pro</a>.
 * Version: 1.0.3
 * Author: Sentient Bit
 * Author URI: https://sentientbit.com
 * Text Domain: esat
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Don't load directly
 */
if (!defined('ABSPATH')) {
	die('
        <div style="height: 100%; display: flex; justify-content: center; align-items: center;">
            <h1 style="font-size: 100px;">You cannot be here!<h1>
        </div>
    ');
}

/**
 * Plugin constants
 */
if (!defined('ESAT_VERSION')) define('ESAT_VERSION', '1.0.0');
if (!defined('ESAT_DATABASE_VERSION')) define('ESAT_DATABASE_VERSION', '1.0.0');

define('ESAT_FOLDER', 'essential-settings-and-tools');
define('ESAT_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('ESAT_DIR', plugin_dir_path(__FILE__));
define('ESAT_BASENAME', plugin_basename(__FILE__));

/**
 * Includes
 * @since 1.0.0
 */
// Code that runs on plugin activation
//register_activation_hook(__FILE__, 'esat_activation');

// Code that runs on plugin deactivation
//register_deactivation_hook(__FILE__, 'esat_deactivation');

// Functions for setting up admin menu, admin pages, settings and other functionalities
require_once ESAT_DIR . '/core/esat-core.php';

// Load vendor libraries
require_once ESAT_DIR . 'core/vendor/autoload.php';
