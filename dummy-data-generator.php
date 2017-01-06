<?php

/**
 * Plugin Name:       Dummy Data Generator
 * Plugin URI:        https://github.com/Hito01/dummy-data-generator
 * Description:       Use <a href="https://github.com/fzaninotto/Faker">Faker</a> PHP library to generate dummy data. Support <a href="https://www.advancedcustomfields.com/">ACF</a> plugin.
 * Version:           1.0.0
 * Author:            Nicolas Collard
 * Author URI:        http://hito.be
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dummy-data-generator
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) { die; }

define('DDG_VERSION', '1.0.0');

function activate_dummy_data_generator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dummy-data-generator-activator.php';
	Dummy_Data_Generator_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_dummy_data_generator' );

function deactivate_dummy_data_generator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dummy-data-generator-deactivator.php';
	Dummy_Data_Generator_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_dummy_data_generator' );

require plugin_dir_path( __FILE__ ) . 'includes/class-dummy-data-generator.php';
$plugin = new Dummy_Data_Generator();
$plugin->run();
