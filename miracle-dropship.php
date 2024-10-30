<?php
/**
 * Plugin Name: Miracle Dropship
 * Plugin URI: https://miraclecbdproducts.com
 * Description: It’s time to start your own CBD E-Commerce Store! Do you want to add CBD products to your website without having to invest in the overhead and inventory it would take to launch a new product line? Then the Miracle DropShip program is just what you need! This free plugin tool is built for Miracle DropShip Clients. To enroll, please see our settings section. Miracle CBD Dropship is a breakthrough technology for the Cannabis (namely CBD) industry that now connects publishers and e-commerce platforms with direct access to sell hemp-based CBD products to its customer base. This WordPress plugin gives you the ability to sell Miracle CBD products through your own website and we will dropship on your behalf. The Miracle Dropship plugin allows you to easily search and import the best CBD products for your audience to purchase. Miracle Nutritional Products™ is one of the largest and most trusted CBD manufacturers in the world. The Cannabis industry is just starting to explode, and it is only going to get bigger over the next few years. In fact, the New York Times predicts that the industry will hit $22.6 Billion by the year 2022! Our products are made from the hemp plant and contain no THC. This means you will enjoy all of the benefits of CBD without the high, and it is legal to sell and consume in all 50 states! We stand behind our products and its quality. Everything we produce is rigorously tested for its purity and quality. When we deliver our product, our customers are assured that they are receiving the highest quality CBD product they could ask for. As an Miracle DropShip Partner, you can expect to deliver high quality and potent products to your customers. Our Miracle CBD product catalog is continuously growing and currently has over 80 CBD products which vary from oils, pills, edibles, beauty and skincare, pain relief, e-liquids to pet and equestrian products. You will find that the target audience for our products is vast and is only getting larger as more people are educated on the CBD industry.
 * Version: 1.0
 * Author: Miracle Nutritional Products
 * Author URI: https://miraclecbdproducts.com
 * Text Domain: miracle-dropship
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'MIRACLE_FILE', __FILE__ );
define( 'MIRACLE_DIRNAME', basename( dirname( __FILE__ ) ) );
define( 'MIRACLE_RELPATH', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
define( 'MIRACLE_PATH', plugin_dir_path( __FILE__ ) );
define( 'MIRACLE_PREFIX', 'miracle-dropship' );
define( 'MIRACLE_PLUGINPATH', WP_PLUGIN_URL . '/' . basename( dirname( __FILE__ ) ) );

// Turn this on to enable additional debugging options
define( 'MIRACLE_DEBUG', false );

add_action( 'admin_init', 'miracle_required_plugins' );

function miracle_required_plugins()
{
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'woocommerce/woocommerce.php' )   )
    {
        add_action( 'admin_notices', 'miracle_required_plugins_notice' );

        deactivate_plugins( plugin_basename( __FILE__ ) ); 

        if ( isset( $_GET['activate'] ) ) {
            unset( $_GET['activate'] );
        }
    }
}

function miracle_required_plugins_notice()
{
?>
	<div class="error">
		<p>
			<?php _e("Sorry, the plugin Miracle Dropship requires woocommerce plugin to be installed and active.",MIRACLE_PREFIX) ?>
		</p>
	</div>
<?php
}

include_once( MIRACLE_PATH . '/core/admin.php' );
include_once( MIRACLE_PATH . '/core/functions.php' );
include_once( MIRACLE_PATH . '/core/class-import.php' );
if( is_admin() )
{
	// Register Items Importer in the list of available WordPress importers
	function miracle_register_dropship() {

		register_importer( 'miracle', __( 'Products', MIRACLE_PREFIX ), __( '<strong>Miracle Dropship</strong> - Import Products into WooCommerce from Miracle.', MIRACLE_PREFIX ), 'miracle_html_page' );

	}
	add_action( 'admin_init', 'miracle_register_dropship' );

	// HTML templates and form processor for Product Importer screen
	function miracle_html_page() {
		global $import;
		// Check the User has the manage_woocommerce capability
		if( current_user_can( 'manage_woocommerce' ) == false )
			return;
		$action = ( function_exists( 'miracle_get_action' ) ? miracle_get_action() : false );
		$title = __( 'Miracle Dropship', MIRACLE_PREFIX );

		miracle_template_header( $title );
		
		miracle_manage_form();
		
		miracle_template_footer();

	}

	function miracle_admin_init() {

		// Check the User has the manage_woocommerce_products capability
		if( current_user_can( 'manage_woocommerce' ) == false )
			return;

		@ini_set( 'memory_limit', WP_MAX_MEMORY_LIMIT );
		miracle_init();

	}
	add_action( 'admin_init', 'miracle_admin_init' );
}