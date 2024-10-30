<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( is_admin() ) {

	/* Start of: WordPress Administration */
	function miracle_init() {

		global $import, $wpdb, $woocommerce;
		
		if( !ini_get( 'safe_mode' ) )
			@set_time_limit( $timeout );

		@ini_set( 'memory_limit', '2000M' );

		// Prevent header sent errors for the import
		@ob_start();

		$action = ( function_exists( 'miracle_get_action' ) ? miracle_get_action() : false );
		switch( $action ) {

			// Save changes on Settings screen
			case 'save-settings':
				$data = array(
								'username' => sanitize_user($_POST['miracle_username']),
								'password' => sanitize_text_field($_POST['miracle_password']),
								'name' => sanitize_text_field($_POST['miracle_company_name']),
								'contact_person' => sanitize_text_field($_POST['miracle_name']),
								'email_address' => sanitize_text_field($_POST['miracle_email']),
								'phone_number' => sanitize_text_field($_POST['miracle_phone_number'])
						);
				$customer_data = miracle_add_customer_to_api($data);
				miracle_update_option( 'miracle_username', ( isset( $_POST['miracle_username'] ) ? sanitize_user($_POST['miracle_username']) : '' ) );
				miracle_update_option( 'miracle_password', ( isset( $_POST['miracle_password'] ) ? sanitize_text_field($_POST['miracle_password']) : '' ) );
				miracle_update_option( 'miracle_name', ( isset( $_POST['miracle_name'] ) ? sanitize_text_field($_POST['miracle_name']) : '' ) );
				miracle_update_option( 'miracle_email', ( isset( $_POST['miracle_email'] ) ? sanitize_user($_POST['miracle_email']) : '' ) );
				miracle_update_option( 'miracle_company_name', ( isset( $_POST['miracle_company_name'] ) ? sanitize_text_field($_POST['miracle_company_name']) : '' ) );
				miracle_update_option( 'miracle_phone_number', ( isset( $_POST['miracle_phone_number'] ) ? sanitize_text_field($_POST['miracle_phone_number']) : '' ) );
				if(strpos($customer_data, 'Company account was created') !== false)
				{
					$message = __( $customer_data, MIRACLE_PREFIX );
					$msg_type = 'updated';
				}
				else
				{
					$message = __( $customer_data, MIRACLE_PREFIX );
					$msg_type = 'error';
				}
				
				echo miracle_admin_notice( $message, $msg_type );
				
				break;

			// The opening Import screen
			default:
			
		}
	}
	// Increase memory for AJAX importer process and Product Importer screens
	function miracle_init_memory() {
	@ini_set( 'memory_limit', WP_MAX_MEMORY_LIMIT );
	}
	add_action( 'plugins_loaded', 'miracle_init_memory' );

	/* End of: WordPress Administration */

}
if( !function_exists( 'miracle_get_action' ) ) {
	function miracle_get_action( $prefer_get = false ) {

		if ( isset( $_GET['action'] ) && $prefer_get )
			return sanitize_text_field( $_GET['action'] );

		if ( isset( $_POST['action'] ) )
			return sanitize_text_field( $_POST['action'] );

		if ( isset( $_GET['action'] ) )
			return sanitize_text_field( $_GET['action'] );

		return false;

	}
}

function miracle_check_settings_valid(){
		
	$miracle_username = miracle_get_option( 'miracle_username' );
	$valid = true;
	if(isset($miracle_username) && $miracle_username == '' )
	{
		$valid = false;
	}
	return $valid;
}

function miracle_update_option( $option = null, $value = null ) {

	$output = false;
	if( isset( $option ) && isset( $value ) ) {
		$separator = '_';
		$output = update_option( MIRACLE_PREFIX . $separator . $option, $value );
	}
	return $output;

}

function miracle_get_option( $option = null, $default = false, $allow_empty = false ) {

	$output = '';
	if( isset( $option ) ) {
		$separator = '_';
		$output = get_option( MIRACLE_PREFIX . $separator . $option, $default );
		if( $allow_empty == false && $output != 0 && ( $output == false || $output == '' ) )
			$output = $default;
	}
	return $output;

}

if( !function_exists( 'miracle_get_user_items' ) ) {
	function miracle_get_user_items( $userId ) {
		$result = array();
		$response = wp_remote_get( 'https://miraclecbdproducts.com/api/product/?username='.$userId, array( 'timeout' => 120, 'httpversion' => '1.1' ) );
		$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		if(isset($api_response['results']) && !empty($api_response['results']))
		{
			$result = $api_response['results'];
		}
		else
		{
			$result['error'] = true;
		}

		return $result;
	}
}

if( !function_exists( 'miracle_add_customer_to_api' ) ) {
	function miracle_add_customer_to_api($data) {
		$result = array();
		$response = wp_remote_post( 'https://miraclecbdproducts.com/api/company/',
									array(
                                            'headers' => array(),
                                            'timeout' => 60,
                                            'body' =>   json_encode($data)
                                        )
                      );

		$api_response = json_decode( wp_remote_retrieve_body( $response ), true );
		
		if(isset($api_response['message']) && !empty($api_response['message']))
		{
			$result = $api_response['message'];
		}
		else
		{
			$result = $api_response['error'];
		}

		return $result;
	}
}

add_action( 'woocommerce_checkout_order_processed', 'miracle_create_order_on_api', 10 );
function miracle_create_order_on_api($order_id)
{
	$order = wc_get_order( $order_id );
    $order_data = $order->get_data();
    
    $miracle_products = $data = array();
    foreach ($order->get_items() as $item_key => $item )
    {
    	$product      = $item->get_product(); // 
    	$product_id   = $item->get_product_id();
    	$quantity     = $item->get_quantity();
    	$miracle_product_id = get_post_meta($product_id, '_miracle_id', true);
    	if($miracle_product_id)
    	{
    		$miracle_items = array(
    								'id' => $miracle_product_id,
    								'quantity' => $quantity
    							);
    		$miracle_products[] = $miracle_items;
    	}
    }
    $miracle_username = miracle_get_option( 'miracle_username' );
    $miracle_password = miracle_get_option( 'miracle_password' );
    if(!empty($miracle_products) && $miracle_username &&  $miracle_password)
    {
    	$country = $order->get_billing_country();
		$state = $order->get_billing_state();
    	$data = array(
    					'username' => $miracle_username,
    					'password' => $miracle_password,
    					'products' => $miracle_products,

    					'billing_address_first_name' => $order_data['billing']['first_name'],
						'billing_address_last_name' => $order_data['billing']['last_name'],
						'billing_address_address1' => $order_data['billing']['address_1'],
						'billing_address_address2' => $order_data['billing']['address_2'],
						'billing_address_city' => $order_data['billing']['city'],
						'billing_address_zip_code' => $order_data['billing']['postcode'],
						'billing_address_state' => WC()->countries->get_states( $country )[$state],
						'billing_address_email' => $order_data['billing']['email'],
						'billing_address_phone' => $order_data['billing']['phone']
    			);
    	if(isset($_POST['ship_to_different_address']))
    	{
    		$country = $order->get_shipping_country();
			$state = $order->get_shipping_state();
    		$data['shipping_address_first_name'] = $order_data['shipping']['first_name'];
			$data['shipping_address_last_name'] = $order_data['shipping']['last_name'];
			$data['shipping_address_address1'] = $order_data['shipping']['address_1'];
			$data['shipping_address_address2'] = $order_data['shipping']['address_2'];
			$data['shipping_address_city'] = $order_data['shipping']['city'];
			$data['shipping_address_zip_code'] = $order_data['shipping']['postcode'];
			$data['shipping_address_state'] = WC()->countries->get_states( $country )[$state];
			$data['shipping_address_email'] = $order_data['billing']['email'];
			$data['shipping_address_phone'] = $order_data['billing']['phone'];

    	}
    	else
    	{
    		$data['shipping_address_first_name'] = $order_data['billing']['first_name'];
			$data['shipping_address_last_name'] = $order_data['billing']['last_name'];
			$data['shipping_address_address1'] = $order_data['billing']['address_1'];
			$data['shipping_address_address2'] = $order_data['billing']['address_2'];
			$data['shipping_address_city'] = $order_data['billing']['city'];
			$data['shipping_address_zip_code'] = $order_data['billing']['postcode'];
			$data['shipping_address_state'] = WC()->countries->get_states( $country )[$state];
			$data['shipping_address_email'] = $order_data['billing']['email'];
			$data['shipping_address_phone'] = $order_data['billing']['phone'];
    	}
    }

	$response = wp_remote_post( 'https://miraclecbdproducts.com/api/order/',
								array(
                                        'headers' => array(),
                                        'timeout' => 60,
                                        'body' =>   json_encode($data)
                                    )
                  );
	update_post_meta($order_id, '_miracle_order_response',$response);
}
 ?>