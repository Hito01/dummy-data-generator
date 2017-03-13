<div id="dummy-data-generator-admin" class="wrap">
  <h1><?php _e('Dummy Data Generator', 'dummy-data-generator'); ?></h1>
  <?php if(!empty($success_message)): ?>
  <div class="notice notice-success is-dismissible">
    <p><?php echo $success_message; ?></p>
  </div>
  <?php endif; ?>
  <?php if(!empty($error_message)): ?>
  <div class="notice notice-error is-dismissible">
    <p><?php echo $error_message; ?></p>
  </div>
  <?php endif; ?>

	<form method="post" novalidate="novalidate">
		<table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="post_type"><?php _e('1. Choose a post type') ?>: </label></th>
          <td>
            <select id="post_type" name="post_type" aria-describedby="post-type-description">
            <option value=""><?php _e('Select a post type'); ?></option>
            <?php foreach(get_post_types(array('public' => true), 'objects') as $post_type) : ?>
              <option value="<?php echo $post_type->name ?>"><?php echo $post_type->labels->singular_name ?></option>
            <?php endforeach; ?>
            </select>
          </td>
        </tr>
        <tr>
          <th scope="row"><label for="quantity"><?php _e('2. Enter the desired quantity'); ?></label></th>
          <td><input name="quantity" type="number" id="quantity" value="1" class="small-text"></td>
        </tr>
        <tr>
          <th scope="row"><label for="submit"><?php _e('3. Click the button and let the magic happens'); ?></label></th>
          <td><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Generate !'); ?>"></td>
        </tr>
      </tbody>
    </table>

    <p><? _e('The plugin will create the desired quantity of selected post type. If some ACFs are linked to the post type, it will fill all the fields.', 'dummy-data-generator'); ?></p>

  </form>
</div>
