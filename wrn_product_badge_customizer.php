<?php
/**
 * Plugin Name: Woocommerce Product Badge Customizer
 * Plugin URI: https://example.com
 * Description: Define product badges following some product selection criteria
 * Version: 1.0.0
 */

if( ! defined('WPINC')){
    die;
}

require 'wr-classes/WR_Badge_PLugin.php';

//Change WRPL for plugin's initials
define('WRNB_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('WRNB_PLUGIN_URL' , plugin_dir_url(  __FILE__  ) );
define('WRNB_ADMIN_URL' , get_admin_url() );
define('WRNB_PLUGIN_DIR_BASENAME' , dirname(plugin_basename(__FILE__)) );
define('WRNB_PLUGIN_DIR_NAME' , plugin_basename(__FILE__));
define( 'WRNB_WITH_CLASSES__FILE__', __FILE__ );

/**
 * Check if Woocommerce is activated
 */
if ( class_exists( 'woocommerce' ) ){
	/**
	 * Create the plugin Instance
	 */
	if(class_exists('WR_Badge_Plugin')){

		$wr_badge_plugin = new WR_Badge_Plugin();

		$wr_badge_plugin->register();
	}
}else{

	add_action('admin_notices', function(){
		?>
		<div class="notice notice-error is-dismissible">
			<p>Woocommerce Product Badge Customizer Plugin required WooCommerce, please activate it to use it</p>
		</div>
		<?php
	});
}


