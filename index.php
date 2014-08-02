<?php
/*
Plugin Name: WP API Admin Dashboard
Author: Eric Andrew Lewis
Author URI: http://ericandrewlewis.com
Description: A web app using WP-API to edit your content. Adds a top-level link in the admin menu "New Admin".
Version: 0.1
*/

class WP_API_Dashboard {
	/**
	 * Singleton.
	 *
	 * @return WP_API_Dashboard
	 */
	public static function get_instance()
	{
		static $inst = null;
		if ( $inst === null ) {
			$inst = new WP_API_Dashboard();
		}
		return $inst;
	}

	private function __construct()
	{
		add_action( 'init', array( $this, 'rewrite_rules' ) );

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		add_action( 'template_redirect', array( $this, 'init_dashboard' ) );
		add_filter('query_vars', array( $this, 'query_vars' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'dequeue_all_scripts' ), 999 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ), 1000 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public function rewrite_rules() {
		add_rewrite_rule('^dash(/(.*)?/?)?',
			'index.php?show_dashboard=1',
			'top' );
	}

	public function activate() {
		flush_rewrite_rules();
	}

	/**
	 * Add query var show_dashboard which when set will display the dashboard.
	 *
	 * @param  array $public_query_vars
	 * @return array
	 */
	public function query_vars( $public_query_vars ) {
		$public_query_vars[] = 'show_dashboard';
		return $public_query_vars;
	}

	public function admin_menu() {
		global $menu, $submenu, $_wp_last_object_menu;
		// $menu_position = $_wp_last_object_menu++;
		$menu[0] = array( 'New Admin', 'edit_posts', site_url( '/dash/' ), '', 'menu-top menu-top-first menu-icon-dashboard', 'menu-dashboard', 'dashicons-dashboard' );
	}

	public function dequeue_all_scripts() {
		global $wp_scripts;
		foreach( $wp_scripts->queue as $handle ) :
			wp_dequeue_script( $handle );
		endforeach;
	}

	public function wp_enqueue_scripts() {
		wp_register_script( 'marionette', plugin_dir_url( __FILE__ ) . 'bower_components/marionette/lib/backbone.marionette.js', array( 'backbone' ) );
		wp_register_script( 'spin-js', plugin_dir_url( __FILE__ ) . 'bower_components/spin.js/spin.js' );
		wp_register_script( 'spin-js-jquery', plugin_dir_url( __FILE__ ) . 'bower_components/spin.js/jquery.spin.js', array( 'spin-js') );
		wp_register_script( 'bootstrap', plugin_dir_url( __FILE__ ) . 'bower_components/bootstrap/dist/js/bootstrap.min.js' );
		wp_deregister_script( 'wp-api' );
		wp_register_script( 'wp-api', plugin_dir_url( __FILE__ ) . 'js/wp-api.js', array( 'backbone' ) );
		$settings = array( 'root' => esc_url_raw( get_json_url() ), 'nonce' => wp_create_nonce( 'wp_json' ) );
		wp_localize_script( 'wp-api', 'WP_API_Settings', $settings );

		wp_register_script( 'wp-api-dash', plugin_dir_url( __FILE__ ) . 'js/script.js', array( 'marionette', 'wp-api', 'spin-js-jquery', 'bootstrap' ) );
		wp_localize_script( 'wp-api-dash', 'WP_API_Dash_Settings', array( 'root' =>'/dash/' ) );
		wp_enqueue_script( 'wp-api-dash' );
		wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'bower_components/bootstrap/dist/css/bootstrap.min.css' );
		wp_enqueue_style( 'bootstrap-theme', plugin_dir_url( __FILE__ ) . 'bower_components/bootstrap/dist/css/bootstrap-theme.min.css' );
	}

	/**
	 * Load the dashboard template if we're on the dashboard.
	 */
	public function init_dashboard() {
		if ( ! get_query_var( 'show_dashboard' ) )
			return;
		// Remove all actions from wp_head; all we need is script and style enqueueing.
		remove_all_actions( 'wp_head' );
		add_action( 'wp_head', 'wp_enqueue_scripts', 1 );
		add_action( 'wp_head', 'wp_print_styles', 8 );
		add_action( 'wp_head', 'wp_print_head_scripts', 9 );
		require( 'template.php' );
		exit;
	}
}

WP_API_Dashboard::get_instance();