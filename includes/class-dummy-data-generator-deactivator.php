<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Dummy_Data_Generator
 * @subpackage Dummy_Data_Generator/includes
 * @author     Nicolas Collard <niko@hito.be>
 */
class Dummy_Data_Generator_Deactivator {

	/**
	 * Triggered on plugin deactivation.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

    global $wp_roles;
    $wp_roles->use_db = true;
    $administrator = $wp_roles->role_objects['administrator'];
    if ($administrator->has_cap('ddg_use')) {
      $administrator->remove_cap('ddg_use');
    }

	}

}
