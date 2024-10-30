<!-- .subsubsub -->
<br class="clear" />
<form enctype="multipart/form-data" method="post">
  <table class="form-table">
    <tbody>
      <tr id="general-settings">
        <td colspan="2" style="padding:0;"><h3>
            <div class="dashicons dashicons-admin-settings"></div>
            &nbsp;
            <?php _e( 'General Settings', MIRACLE_PREFIX ); ?>
          </h3>
          <p class="description">
            <?php _e( 'Manage import options across Items Importer from this screen.', MIRACLE_PREFIX ); ?>
          </p></td>
      </tr>
      <tr>
        <th> <label for="miracle_user">
            <?php _e( 'Miracle Username', MIRACLE_PREFIX ); ?>
          </label>
        </th>
        <td><input type="text" name="miracle_username" value="<?php echo $miracle_username;?>" class="regular-text" required="required">
          <p class="description">
            <?php _e( 'Add username here to get your items from API.', MIRACLE_PREFIX); ?>
          </p>
        </td>
      </tr>
      <tr>
        <th> <label for="miracle_password">
            <?php _e( 'Password', MIRACLE_PREFIX ); ?>
          </label>
        </th>
        <td><input type="password" name="miracle_password" value="<?php echo $miracle_password;?>" class="regular-text" required="required">
        </td>
      </tr>
      <tr>
        <th> <label for="miracle_name">
            <?php _e( 'Name', MIRACLE_PREFIX ); ?>
          </label>
        </th>
        <td><input type="text" name="miracle_name" value="<?php echo $miracle_name;?>" class="regular-text" required="required">
        </td>
      </tr>
      <tr>
        <th> <label for="miracle_email">
            <?php _e( 'Email', MIRACLE_PREFIX ); ?>
          </label>
        </th>
        <td><input type="text" name="miracle_email" value="<?php echo $miracle_email;?>" class="regular-text" required="required">
        </td>
      </tr>
      <tr>
        <th> <label for="miracle_company_name">
            <?php _e( 'Company', MIRACLE_PREFIX ); ?>
          </label>
        </th>
        <td><input type="text" name="miracle_company_name" value="<?php echo $miracle_company_name;?>" class="regular-text" required="required">
        </td>
      </tr>
      <tr>
        <th> <label for="miracle_phone_number">
            <?php _e( 'Phone Number', MIRACLE_PREFIX ); ?>
          </label>
        </th>
        <td><input type="text" name="miracle_phone_number" value="<?php echo $miracle_phone_number;?>" class="regular-text" required="required">
        </td>
      </tr>
    </tbody>
  </table>
  <!-- .form-table -->
  <p class="submit">
    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e( 'Save Changes', MIRACLE_PREFIX ); ?>" />
  </p>
  <input type="hidden" name="action" value="save-settings" />
</form>