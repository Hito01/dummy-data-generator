<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @since 1.0.0
 * @package    Dummy_Data_Generator
 * @subpackage Dummy_Data_Generator/admin
 * @author     Nicolas Collard <niko@hito.be>
 */
class Dummy_Data_Generator_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Faker instance.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Faker\Generator    $faker    The faker instance.
	 */
	private $faker;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
    $this->faker = Faker\Factory::create();
    $this->faker->addProvider(new EmanueleMinotto\Faker\PlaceholdItProvider($this->faker));

	}

  /**
   * Register plugin in Wordpress menu.
   *
   * @since 1.0.0
   */
  public function register_menu() {
    add_menu_page( $title = __('Data generator', 'dummy-data-generator'), $title, 'ddg_use', 'ddg', array($this, 'ddg_page'), 'dashicons-networking' );
  }

  /**
   * Triggered when plugin is clicked in the menu.
   *
   * @since 1.0.0
   */
  public function ddg_page() {
    if(!empty($_POST['post_type']) && !empty($_POST['quantity'])){
      $done = $this->generate($_POST['post_type'], $_POST['quantity']);
      if($done) {
        $success_message = __('Generation successfully finished.', 'dummy-data-generator');
      }
      else {
        $error_message = __('An error occurred during the generation.', 'dummy-data-generator');
      }
    }

    require __DIR__ . '/views/dummy-data-generator-admin-display.php';
  }

  /**
   * Execute dummy data generation for the post_type and the quantity specified.
   *
   * @since 1.0.0
   *
   * @param   string  $post_type
   * @param   int     $quantity
   *
   * @return  bool
   */
  private function generate($post_type, $quantity) {
    $acf_ids = $this->post_type_acfs_ids($post_type);
    $fields = $this->acfs_fields($acf_ids);

    for($i = 0; $i < $quantity; $i++) {

      $dummy_post = array(
        'post_content' => 'dummy data generator',
        'post_title' => $this->faker->text(30),
        'post_status' => 'publish',
        'post_type' => $post_type
      );

      $post_id = wp_insert_post($dummy_post);
      if(is_wp_error($post_id)){
        return false;
      }

      if(!empty($fields)) {
        foreach($fields as $field) {
          $this->insert_acf_data($post_id, $field);
        }
      }

    }
    return true;
  }

  /**
   * Check if the post_type has at least one linked ACF and return the ids.
   *
   * @since 1.0.0
   *
   * @return  array
   */
  private function post_type_acfs_ids($post_type) {
    $acfs = get_posts(array(
      'numberposts' => -1,
      'post_type' => 'acf-field-group'
    ));
    $ids = array();

    foreach($acfs as $acf) {
      $acf_config = unserialize($acf->post_content);
      $added = false;
      foreach($acf_config['location'] as $or_condition) {

        foreach($or_condition as $and_condition) {
          if($and_condition['param'] == 'post_type' && $and_condition['operator'] == '==' && $and_condition['value'] == $post_type){
            $ids[] = $acf->ID;
            $added = true;
            break;
          }
        }

        if($added) {
          break;
        }
      }
    }
    return $ids;
  }

  /**
   * Get all fields from the ACFs with the IDs passed in parameters.
   *
   * @since 1.0.0
   *
   * @param   array   $acf_ids
   *
   * @return  array
   */
  private function acfs_fields($acf_ids) {
    return get_posts(array(
      'numberposts' => -1,
      'post_type' => 'acf-field',
      'post_parent__in' => $acf_ids
    ));
  }

  /**
   * Check field type and call corresponding method to insert data.
   *
   * @since 1.0.0
   *
   * @param   int       $post_id
   * @param   WP_Post   $field
   *
   */
  private function insert_acf_data($post_id, $field) {
    $structure = unserialize($field->post_content);
    $method = "insert_acf_{$structure['type']}_data";
    if(method_exists($this, $method)){
      $this->$method($post_id, $field->post_excerpt, $structure);
    }
  }

  /**
   * Generate data for text field.
   *
   * @since 1.0.0
   *
   * @param   int       $post_id
   * @param   string    $name
   * @param   array     $structure
   *
   */
  private function insert_acf_text_data($post_id, $name, $structure) {
    $text = $this->faker->text(!empty($structure['maxlength']) ? $structure['maxlength'] : 50);
    update_field($name, $text, $post_id);
  }

  /**
   * Generate data for textarea field.
   *
   * @since 1.0.0
   *
   * @param   int       $post_id
   * @param   string    $name
   * @param   array     $structure
   *
   */
  private function insert_acf_textarea_data($post_id, $name, $structure) {
    update_field($name, $this->faker->text(!empty($structure['maxlength']) ? $structure['maxlength'] : 200), $post_id);
  }

  /**
   * Generate data for number field.
   *
   * @since 1.0.0
   *
   * @param   int       $post_id
   * @param   string    $name
   * @param   array     $structure
   *
   */
  private function insert_acf_number_data($post_id, $name, $structure) {
    $number = $this->faker->numberBetween(
      !empty($structure['min']) ? $structure['min'] : 1 ,
      !empty($structure['max']) ? $structure['max'] : 1000
    );
    update_field($name, $number, $post_id);
  }

  /**
   * Generate data for email field.
   *
   * @since 1.0.0
   *
   * @param   int       $post_id
   * @param   string    $name
   * @param   array     $structure
   *
   */
  private function insert_acf_email_data($post_id, $name, $structure) {
    update_field($name, $this->faker->email, $post_id);
  }

  /**
   * Generate data for url field.
   *
   * @since 1.0.0
   *
   * @param   int       $post_id
   * @param   string    $name
   * @param   array     $structure
   *
   */
  private function insert_acf_url_data($post_id, $name, $structure) {
    update_field($name, $this->faker->url, $post_id);
  }

  /**
   * Generate data for password field.
   *
   * @since 1.0.0
   *
   * @param   int       $post_id
   * @param   string    $name
   * @param   array     $structure
   *
   */
  private function insert_acf_password_data($post_id, $name, $structure) {
    update_field($name, $this->faker->password, $post_id);
  }

  /**
   * Generate data for image field.
   *
   * @since 1.0.0
   *
   * @param   int       $post_id
   * @param   string    $name
   * @param   array     $structure
   *
   */
  private function insert_acf_image_data($post_id, $name, $structure) {
    $url = $this->faker->imageUrl(array(500, 500), 'jpg');
    $tmp = download_url($url);
    $file_array = array();
    $file_array['name'] = 'image.jpg';
    $file_array['tmp_name'] = $tmp;
    $image_id = media_handle_sideload( $file_array, $post_id );
    update_field($name, $image_id, $post_id);
  }

}
