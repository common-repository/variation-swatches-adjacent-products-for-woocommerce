<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Variation Swatches Smart Products Gallery for WooCommerce
 * Plugin URI:        https://codesolz.net/our-products/wordpress-plugin/variation-swatches-adjacent-products-for-woocommerce
 * Description:       Magic adjacent product suggestion on different design to increase sales on variation products. Works on product single page.
 * Version:           1.0.9
 * Author:            CodeSolz
 * Author URI:        https://www.codesolz.net
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl.txt
 * Domain Path:       /languages
 * Text Domain:       variation-swatches-adjacent-products-for-woocommerce
 * Requires PHP: 7.0
 * Requires At Least: 4.0
 * Tested Up To: 6.4
 * WC requires at least: 4.5
 * WC tested up to: 8.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Woo_Variation_Swatches_Adjacent_Products' ) ) {

	class Woo_Variation_Swatches_Adjacent_Products {

		/**
		 * Hold actions hooks
		 *
		 * @var array
		 */
		private static $wvsap_hooks = array();

		/**
		 * Hold version
		 *
		 * @var String
		 */
		private static $version = '1.0.8';

		/**
		 * Hold version
		 *
		 * @var String
		 */
		private static $db_version = '1.0.0';

		/**
		 * Hold nameSpace
		 *
		 * @var string
		 */
		private static $namespace = 'WooVarSwatchesAdjacentProducts';


		public function __construct() {

			

			// load plugins constant.
			self::set_constants();

			// load core files.
			self::load_core_framework();

			// load init.
			self::load_hooks();

			/** Called during the plugin activation */
			self::on_activate();

			/** Load textdomain */
			add_action( 'plugins_loaded', array( __CLASS__, 'wvsap_textdomain' ), 15 );

			/**Init necessary functions */
			add_action( 'plugins_loaded', array( __CLASS__, 'init_wvsap_function' ), 14 );

		}

		/**
		 * Set constant data
		 */
		private static function set_constants() {

			$constants = array(
				'CS_WVSAP_VERSION'           => self::$version, // Define current version.
				'CS_WVSAP_DB_VERSION'        => self::$db_version, // Define current db version.
				'CS_WVSAP_HOOKS_DIR'         => untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/core/actions/', // plugin hooks dir.
				'CS_WVSAP_BASE_DIR_PATH'     => untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/', // Hold plugins base dir path.
				'CS_WVSAP_TEMPLATES_DIR'     => untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/',
				'CS_WVSAP_PLUGIN_ASSET_URI'  => plugin_dir_url( __FILE__ ) . 'templates/', // Define asset uri.
				'CS_WVSAP_PLUGIN_LIB_URI'    => plugin_dir_url( __FILE__ ) . 'lib/', // Library uri.
				'CS_WVSAP_PLUGIN_IDENTIFIER' => plugin_basename( __FILE__ ), // plugins identifier - base dir.
				'CS_WVSAP_PLUGIN_NAME'       => 'Variation Swatches Smart Products Gallery for WooCommerce', // Plugin name.
				'CS_WVSAP_NOTICE_ID'         => 'wvsap_notice_dismiss', // Plugin Notice id.
			);

			foreach ( $constants as $name => $value ) {
				self::set_constant( $name, $value );
			}

			return true;
		}

		/**
		 * Set constant
		 *
		 * @param type $name
		 * @param type $value
		 * @return boolean
		 */
		private static function set_constant( $name, $value ) {
			if ( ! defined( $name ) ) {
				define( $name, $value );
			}
			return true;
		}


		/**
		 * load core framework
		 */
		private static function load_core_framework() {
			require_once CS_WVSAP_BASE_DIR_PATH . 'vendor/autoload.php';
		}

		/**
		 * Load Action Files
		 *
		 * @return classes
		 */
		private static function load_hooks() {
			$namespace = self::$namespace . '\\actions\\';
			foreach ( glob( CS_WVSAP_HOOKS_DIR . '*.php' ) as $cs_action_file ) {
				$class_name = basename( $cs_action_file, '.php' );
				$class      = $namespace . $class_name;
				if ( class_exists( $class ) &&
					! array_key_exists( $class, self::$wvsap_hooks ) ) { // check class doesn't load multiple time
					self::$wvsap_hooks[ $class ] = new $class();
				}
			}
			return self::$wvsap_hooks;
		}

		/**
		 * init activation hook
		 */
		private static function on_activate() {

			// activation hook
			register_deactivation_hook( __FILE__, array( self::$namespace . '\\install\\Activate', 'on_activate' ) );

			// deactivation hook
			register_deactivation_hook( __FILE__, array( self::$namespace . '\\install\\Activate', 'on_deactivate' ) );

			return true;
		}

		/**
		 * init textdomain
		 */
		public static function wvsap_textdomain() {
			load_plugin_textdomain( 'variation-swatches-adjacent-products-for-woocommerce', false, CS_WVSAP_BASE_DIR_PATH . '/languages/' );
		}

		/**
		 * Init plugin's functions
		 *
		 * @return void
		 */
		public static function init_wvsap_function() {
			if ( false === CsWvsapHasDependencies() ) {
				return false;
			}
			
			// init notices
			\WooVarSwatchesAdjacentProducts\admin\notices\WvsapNotices::init();
		}


	}

	global $WSPS;
	$WSPS = new Woo_Variation_Swatches_Adjacent_Products();
}
