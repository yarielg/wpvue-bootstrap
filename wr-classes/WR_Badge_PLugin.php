<?php

require 'utils.php';

class WR_Badge_Plugin{
	public $plugin;

	public function __construct(){
		$this->plugin = WRNB_PLUGIN_DIR_NAME;
	}

	function register(){

		/**
		 * Activate/Deactivate Actions
		 */

		register_activation_hook(  WRNB_WITH_CLASSES__FILE__ , array($this, 'activate') );
		register_deactivation_hook(  WRNB_WITH_CLASSES__FILE__ , array($this, 'deactivate') );

		/**
		 * Plugin Actions
		 */

		add_action('admin_menu', array($this, 'add_admin_page'));

		add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));

		add_filter("plugin_action_links_$this->plugin", array($this, 'setting_links'));

		//Ajax Calls
		add_action( 'wp_ajax_get_badges', array($this, 'getBadges') );
		add_action( 'wp_ajax_new_badge', array($this, 'newBadge') );

		//Show Product Badges
		add_action('woocommerce_before_shop_loop_item_title', array($this, 'show_product_badge'));

	}

	function add_admin_page(){
		add_menu_page('Woo Badges','Woo Badges','manage_options','wrn_plugin_badges', array($this,'admin_index') );

		$page_product = add_submenu_page( 'wrn_plugin_badges' ,  'Badges',  'Badges', 'manage_options', 'wrn_plugin_badges' );
		/**
		 * Loading styles and scripts just on the badge plugin admin scope
		 */
		add_action( 'load-' . $page_product, function(){
			add_action( 'admin_enqueue_scripts',function (){
				wp_enqueue_style('vue-custom-font', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900' );
				wp_enqueue_style('vue-custom-icon', 'https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css');
				/*wp_enqueue_style('index_css', plugins_url('/wrn_product_badge_customizer/dist/index.css'));*/

				wp_enqueue_script('vue-custom-js', plugins_url('/wrn_product_badge_customizer/dist/scripts.js') ,array(),'1.0', true);
				wp_localize_script( 'vue-custom-js', 'sharedData',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'plugin_path' => WRNB_PLUGIN_URL
					)
				);
			});
			/**
			 * Insert Overlay div to handle plugin load
			 */
	    	add_action('in_admin_header', function(){
	    		 ?>
				<div id="vwp-plugin-loading">
					<img src="<?php echo WRNB_PLUGIN_URL . '/src/img/loading.gif'?>" alt="">
				</div>
				<style>
					#vwp-plugin-loading{
						background: black;
						z-index: 1001;
						opacity: 0.6;
						height: 100%;
						width: 100%;
						position: absolute;
						display: flex;
						align-items: center;
						justify-content: center;
					}
					.update-nag,#wpadminbar,#adminmenumain,#wpfooter{
						display: none;
					}
					#wpcontent, .wp-toolbar{
						padding: 0 !important;
						margin: 0 !important;
					}
				</style>
				<?php
			});
		});

		add_submenu_page( 'wrn_plugin_badges' ,  'New Badge',  'New Badge', 'manage_options', 'wrn_plugin_new_badge', function(){
			echo 'asdasdasd';
		} );

		add_submenu_page( 'wrn_plugin_badges' ,  'Settings',  'Settings', 'manage_options', 'wrn_plugin_settings', function(){
			echo 'asdasdasd';
		} );
	}

	function admin_index(){
		require_once WRNB_PLUGIN_PATH . 'templates/admin/index.php';
	}

	function enqueue_assets(){

	}

	function setting_links($links){
		$settings_link = '<a href="admin.php?page=wrn_plugin_settings">Settings</a>';
		array_push($links, $settings_link);
		return $links;
	}

	/**
	 * Ajax call to get the badges
	 */
	function getBadges(){
		global $wpdb;
		$results = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wrn_badges", ARRAY_A);

		$badges = array();

		foreach($results as $badge){
			$image_url = -1;
			if( $badge['image_id'] > 0){
				$image_url = wp_get_attachment_image_src($badge['image_id'])[0];
			}

			array_push($badges, array(
				'id' => $badge['id'],
				'name' => $badge['name'],
				'type' => $badge['type'],
				'position' => $badge['position'],
				'image_url' => $image_url
			));

		}

		echo json_encode(array('badges'=> $badges));
		wp_die();
	}

	/**
	 * Ajax call to create a new badge
	 */
	function newBadge(){

		global $wpdb;

		$name = $_POST['name'];
		$position = $_POST['position'];
		$type = $_POST['type'];
		$id = wrnb_upload_file( $_FILES['image'] );

		if(is_wp_error($id)){

			echo json_encode(array('badge'=> $_POST, 'files' => $_FILES['image'], 'error' => $id));

		}else{

			$wpdb->query("INSERT INTO $wpdb->prefix" . "wrn_badges (name, type, position, image_id) VALUES ('$name', '$type', '$position','$id')");

			echo json_encode(array('success' => true,'badge'=> $_POST, 'files' => $_FILES['image'], 'id' => $id));
		}

		wp_die();

	}

   function show_product_badge (){
		global $product;
		echo '<span class="badge-y">Loco</span>';
	}

	function activate(){
		/*update_option('wrpl-assign-method',1);
		update_option('wrpl-hide_price',0);
		update_option('wrpl-default_list',1);
		update_option('wrpl-format-price-method',1);*/

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();


		$table_name1 = $wpdb->prefix . 'wrn_badges';

		$sql1 = "CREATE TABLE $table_name1 (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          name varchar(50) NOT NULL,
          type varchar(50) NOT NULL,
          position varchar(50) NOT NULL,
          image_id INT NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";


		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql1 );

	}

	function deactivate(){
		update_option('wrn_action', 'Hello');
	}

}
