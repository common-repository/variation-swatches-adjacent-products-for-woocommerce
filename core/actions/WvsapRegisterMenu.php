<?php namespace WooVarSwatchesAdjacentProducts\actions;

/**
 * Class: Register custom menu
 *
 * @package Action
 * @since 1.0.0
 * @author M.Tuhin <info@codesolz.net>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	die();
}

use WooVarSwatchesAdjacentProducts\admin\options\Scripts_Settings;
use WooVarSwatchesAdjacentProducts\admin\builders\AdminPageBuilder;
use WooVarSwatchesAdjacentProducts\lib\Util;


class WvsapRegisterMenu {

	/**
	 * Hold pages
	 *
	 * @var type
	 */
	private $pages;

	/**
	 *
	 * @var type
	 */
	private $WcFunc;

	/**
	 *
	 * @var type
	 */
	public $current_screen;

	/**
	 * Hold Menus
	 *
	 * @var [type]
	 */
	public $wvsap_menus;

	private static $_instance;

	public function __construct() {
		 // call WordPress admin menu hook
		add_action( 'admin_menu', array( $this, 'wvsap_register_menu' ) );
	}

	/**
	 * Init current screen
	 *
	 * @return type
	 */
	public function init_current_screen() {
		 $this->current_screen = \get_current_screen();
		return $this->current_screen;
	}

	/**
	 * Create plugins menu
	 */
	public function wvsap_register_menu() {
		global $wvsap_menu;
		add_menu_page(
			__( 'WooCommerce Variation Swatches Adjacent Products', 'variation-swatches-adjacent-products-for-woocommerce' ),
			'Adjacent Products',
			'manage_options',
			CS_WVSAP_PLUGIN_IDENTIFIER,
			'cs-variation-swatches-adjacent-products-for-woocommerce',
			CS_WVSAP_PLUGIN_ASSET_URI . 'admin/assets/img/icon.png',
			57
		);

		$this->wvsap_menus['menu_welcome'] = add_submenu_page(
			CS_WVSAP_PLUGIN_IDENTIFIER,
			__( 'Welcome', 'variation-swatches-adjacent-products-for-woocommerce' ),
			'Welcome',
			'manage_options',
			'cs-wvsap-welcome',
			array( $this, 'wvsap_page_migrate' )
		);

		// load script
		add_action( "load-{$this->wvsap_menus['menu_welcome']}", array( $this, 'wvsap_register_admin_settings_scripts' ) );

		remove_submenu_page( CS_WVSAP_PLUGIN_IDENTIFIER, CS_WVSAP_PLUGIN_IDENTIFIER );

		// init pages
		$this->pages = new AdminPageBuilder();
		$wvsap_menu  = $this->wvsap_menus;

	}

	/**
	 * Add Replacement Rule
	 *
	 * @return void
	 */
	public function wvsap_page_migrate() {

		$option = array();

		$page_info = array(
			'title'     => __( 'Thank you!', 'variation-swatches-adjacent-products-for-woocommerce' ),
			'sub_title' => CS_WVSAP_PLUGIN_NAME,
		);

		if ( current_user_can( 'administrator' ) ) {
			$Welcome = $this->pages->Welcome();
			if ( is_object( $Welcome ) ) {
				echo $Welcome->generate_page( array_merge_recursive( $page_info, array( 'default_settings' => array() ) ), $option );
			} else {
				echo $Welcome;
			}
		} else {
			$AccessDenied = $this->pages->AccessDenied();
			if ( is_object( $AccessDenied ) ) {
				echo $AccessDenied->generate_access_denided( array_merge_recursive( $page_info, array( 'default_settings' => array() ) ) );
			} else {
				echo $AccessDenied;
			}
		}
	}



	/**
	 * generate instance
	 *
	 * @return void
	 */
	public static function get_instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * load funnel builder scripts
	 */
	public function wvsap_register_admin_settings_scripts() {

		// register scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'wvsap_load_settings_scripts' ) );

		// init current screen
		$this->init_current_screen();

		// load all admin footer script
		add_action( 'admin_footer', array( $this, 'wvsap_load_admin_footer_script' ) );
	}

	/**
	 * Load admin scripts
	 */
	public function wvsap_load_settings_scripts( $page_id ) {
		return Scripts_Settings::load_admin_settings_scripts( $page_id, $this->wvsap_menus );

	}

	/**
	 * load custom scripts on admin footer
	 */
	public function wvsap_load_admin_footer_script() {
		return Scripts_Settings::load_admin_footer_script( $this->current_screen->id, $this->wvsap_menus );
	}


}
