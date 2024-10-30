<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Add menu in wordpress menu's
function miracle_menu_page() {
     $page = add_menu_page(
					        __( 'Miracle Dropship', MIRACLE_PREFIX ),
					        'Miracle Dropship',
					        'manage_options',
					         MIRACLE_PREFIX,
					        'miracle_html_page',
					        plugins_url( 'miracle-dropship/templates/admin/images/dropshipicon.png' )
					    );
     add_action( 'admin_print_styles-' . $page, 'miracle_enqueue_scripts' );
}
add_action( 'admin_menu', 'miracle_menu_page' );

// Load CSS and jQuery scripts for Product Importer screen
function miracle_enqueue_scripts( $hook ) {

	wp_enqueue_style( 'miracle_styles', plugins_url( '/templates/admin/css/miracle-admin.css', MIRACLE_RELPATH ) );
	wp_enqueue_script( 'miracle_scripts', plugins_url( '/templates/admin/js/miracle-admin.js', MIRACLE_RELPATH ), array( 'jquery' ) );
	
	wp_enqueue_style( 'dashicons' );

}
// HTML template header on Item Importer screen
function miracle_template_header( $title = '', $icon = 'woocommerce' ) { ?>
<div id="miracle-header" class="wrap">
  <div id="miracle-<?php echo $icon; ?>" class="icon32 icon32-miracle-dropship"><br />
  </div>
  <h2><img class="miracle-header-logo" src="<?php echo MIRACLE_PLUGINPATH;?>/templates/admin/images/miracle-logo.png"><br />
    <?php echo $title; ?></h2>
  <?php

}

// HTML template for Import screen
function miracle_manage_form() {

	$tab = false;
	if( isset( $_GET['tab'] ) ) {
		$tab = sanitize_text_field( $_GET['tab'] );
	} else {
		
		$tab = 'overview';
	}
	$url = add_query_arg( 'page', MIRACLE_PREFIX );

	include_once( MIRACLE_PATH . 'templates/admin/tabs.php' );

}

// HTML template footer on Item Importer screen
function miracle_template_footer() { ?>
</div>
<!-- .wrap -->
<?php

}

// HTML active class for the currently selected tab on the Item Importer screen
function miracle_admin_active_tab( $tab_name = null, $tab = null ) {

	if( isset( $_GET['tab'] ) && !$tab )
		$tab = sanitize_text_field( $_GET['tab'] );
	else
		$tab = 'overview';

	$output = '';
	if( isset( $tab_name ) && $tab_name ) {
		if( $tab_name == $tab )
			$output = ' nav-tab-active';
	}
	echo $output;

}


// HTML template for each tab on the Item Importer screen
function miracle_tab_template( $tab = '' ) {

	global $import;

	if( !$tab )
		$tab = 'overview';

	
	switch( $tab ) {
		
		case 'settings': // get settings after saving
			$miracle_username = miracle_get_option( 'miracle_username' );
			$miracle_password = miracle_get_option( 'miracle_password' );
			$miracle_name = miracle_get_option( 'miracle_name' );
			$miracle_email = miracle_get_option( 'miracle_email' );
			$miracle_company_name = miracle_get_option( 'miracle_company_name' );
			$miracle_phone_number = miracle_get_option( 'miracle_phone_number' );
			break;


	}
	if( $tab ) {
		if( file_exists( MIRACLE_PATH . 'templates/admin/tabs-' . $tab . '.php' ) ) {
			include_once( MIRACLE_PATH . 'templates/admin/tabs-' . $tab . '.php' );
		} else {
			$message = sprintf( __( 'We couldn\'t load the import template file <code>%s</code> within <code>%s</code>, this file should be present.', MIRACLE_PREFIX ), 'tabs-' . $tab . '.php', MIRACLE_PATH . 'templates/admin/...' );
			miracle_admin_notice_html( $message, 'error' );
			ob_start(); ?>
<p>
  <?php _e( 'You can see this error for one of a few common reasons', MIRACLE_PREFIX ); ?>
  :</p>
<ul class="ul-disc">
  <li>
    <?php _e( 'WordPress was unable to create this file when the Plugin was installed or updated', MIRACLE_PREFIX ); ?>
  </li>
  <li>
    <?php _e( 'The Plugin files have been recently changed and there has been a file conflict', MIRACLE_PREFIX ); ?>
  </li>
  <li>
    <?php _e( 'The Plugin file has been locked and cannot be opened by WordPress', MIRACLE_PREFIX ); ?>
  </li>
</ul>
<p>
  <?php _e( 'Jump onto our website and download a fresh copy of this Plugin as it might be enough to fix this issue. If this persists get in touch with us.', MIRACLE_PREFIX ); ?>
</p>
<?php
			ob_end_flush();
		}
	}

}

function miracle_admin_notice( $message = '', $priority = 'updated', $screen = '' ) {
		$output = '';
		if( $priority == false || $priority == '' )
		$priority = 'updated';
		if( $message ) {
		ob_start();
		$output .= miracle_admin_notice_html( $message, $priority, $screen );
		ob_end_clean();
		return $output;
		
		}

}

// HTML template for admin notice
function miracle_admin_notice_html( $message = '', $priority = 'updated', $screen = '' ) 
{

	// Display admin notice on specific screen
	$html = '<div id="message" class="'.$priority.' miracle-msg">
	  <p>'.$message.'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
	return $html;
}
 ?>