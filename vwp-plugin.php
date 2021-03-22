<?php
/**
 * Plugin Name: Vue Wordpress
 * Plugin URI: https://example.com
 * Description: Learning how to use vue in wordpress
 * Version: 1.0.0
 */

if( ! defined('WPINC')){
    die;
}

class VueWpPlugin{
    public $plugin;

    public function __construct(){
        $this->plugin = plugin_basename(__FILE__);
    }

    function register(){
        add_action('admin_menu', array($this, 'add_admin_page'));

        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));

        add_filter("plugin_action_links_$this->plugin", array($this, 'setting_links'));
    }

    function add_admin_page(){
        add_menu_page('Vue Wordpress','Vue Wordpress','manage_options','vwp_plugin', array($this,'admin_index'));
    }

    function admin_index(){
        require_once plugin_dir_path(__FILE__) . 'templates/admin/index.php';
    }

    function enqueue_assets(){
        wp_enqueue_style('vue-custom-font', 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900' );
        wp_enqueue_style('vue-custom-icon', 'https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css');

        wp_enqueue_script('vue-custom-js', plugins_url('/vwp-plugin/dist/scripts.js') ,array(),'1.0', true);
    }

    function setting_links($links){
        $settings_link = '<a href="admin.php?page=vwp_plugin">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }
}

if(class_exists('VueWpPlugin')){
    $vuewpPlugin = new VueWpPlugin();
    $vuewpPlugin->register();
}