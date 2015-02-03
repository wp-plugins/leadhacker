<?php
add_action( 'admin_menu', 'leadhacker_admin_menu' );
  
leadhacker_admin_warnings();

function leadhacker_nonce_field($action = -1) { return wp_nonce_field($action); }
$leadhacker_nonce = 'leadhacker-update-code';

function leadhacker_plugin_action_links( $links, $file ) {
  if ( $file == plugin_basename( dirname(__FILE__).'/leadhacker.php' ) ) {
    $links[] = '<a href="admin.php?page=leadhacker-config">'.__('Settings').'</a>';
  }

  return $links;
}

add_filter( 'plugin_action_links', 'leadhacker_plugin_action_links', 10, 2 );

function leadhacker_conf() {
  global $leadhacker_nonce;

  if ( isset($_POST['submit']) ) {
    if ( function_exists('current_user_can') && !current_user_can('manage_options') )
      die(__('Cheatin&#8217; uh?'));

    check_admin_referer( $leadhacker_nonce );
    $project_code = htmlentities(stripslashes($_POST['project_code']));

    if ( empty($project_code) ) {
      $ms = 'new_code_empty';
      delete_option('leadhacker_project_code');
    } else {
      update_option('leadhacker_project_code', $project_code);
      $ms = 'new_code_saved';
    }

  }

  $messages = array(
    'new_code_empty' => 'Your project code has been cleared. Please enter a new project code to use Leadhacker on this site.',
    'new_code_saved' => 'Your project code has been saved. Enjoy using Leadhacker!',
    'code_empty' => 'Please enter your project code.'
  );
?>
<?php if ( !empty($_POST['submit'] ) ) : ?>
<div id="message" class="updated fade">
  <p>
    <?php _e('<strong>Configuration saved.</strong><br \>'.$messages[$ms]) ?>
  </p>
</div>
<?php endif; ?>
<div class="wrap">
  <h2><?php _e('Leadhacker Configuration'); ?></h2>
  <div class="narrow">
    <form action="" method="post" id="leadhacker-conf">
      <h3>About Leadhacker</h3>
      <p>Simple, fast, and powerful. <a href="http://www.leadhacker.ru" target="_blank">Leadhacker</a> is a dramatically easier way for you to improve your website through instant phone call back.  Convert your website visitors into customers and earn more revenue today!</p>
      <h3>Register now</h3>
      <p>Create an account at <a href="http://www.leadhacker.ru" target="_blank">leadhacker.ru</a> and start recieving more calls from web site today! After creating an account you can come back to this configuration page and set up your WordPress website to use Leadhacker.</p>
      <h3>Leadhacker project code</h3>
      <p>You can find your project code on your web sites's setup page. Go to <a href="https://www.leadhacker.ru/sites">leadhacker.ru/sites</a>, make sure you've selected the right web site and click on &lt;Setup&gt;, then Copy it to Clipboard. You can then paste the code in the box below. Your project code should start with "&lt;script" and end with "&lt;/script&gt;".</p>
      <label for="project_code" style="font-weight:bold;">Paste your project code</label>
      <textarea id="project_code" name="project_code" cols="60" rows="5" value="<?php echo get_option('leadhacker_project_code'); ?>" style="font-family: 'Courier New', Courier, mono; font-size: 1.5em;"></textarea>
      <?php leadhacker_nonce_field($leadhacker_nonce) ?>
      <p class="submit"><input type="submit" name="submit" value="<?php _e('Update configuration &raquo;'); ?>" /></p>
    </form>
  </div>
</div>
<?php
}

function leadhacker_admin_warnings() {
  if ( !get_option('leadhacker_project_code') && !isset($_POST['submit']) ) {
    function leadhacker_warning() {
      echo "
      <div id='leadhacker-warning' class='updated fade'><p><strong>".__('Leadhacker is almost ready.')."</strong> ".sprintf(__('You must <a href="%1$s">enter your Leadhacker project code</a> to begin using Leadhacker on your site.'), "admin.php?page=leadhacker-config")."</p></div>";
    }
    add_action('admin_notices', 'leadhacker_warning');
    return;
  } 
}

function leadhacker_admin_menu() {
  leadhacker_load_menu();
}

function leadhacker_load_menu() {
  add_submenu_page('plugins.php', __('Leadhacker Configuration'), __('Leadhacker Configuration'), 'manage_options', 'leadhacker-config', 'leadhacker_conf');
}
