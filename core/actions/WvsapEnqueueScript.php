<?php namespace WooVarSwatchesAdjacentProducts\Actions;

/**
 * Class: Register Frontend Scripts
 *
 * @package Action
 * @since 1.0.0
 * @author M.Tuhin <tuhin@codesolz.net>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	die();
}

use WooVarSwatchesAdjacentProducts\frontend\functions\AdjacentProducts;

class WvsapEnqueueScript {


	function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'wvsap_admin_global_scripts' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'wvsap_register_scripts' ), 90 );
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @return void
	 */
	public function wvsap_admin_global_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'appGlobal', CS_WVSAP_PLUGIN_ASSET_URI . 'admin/assets/js/appGlobal.min.js', array(), CS_WVSAP_VERSION, true );
	}


	/**
	 * Enqueue app scripts
	 *
	 * @return void
	 */
	public function wvsap_register_scripts() {

		
		if ( false === CsWvsapHasDependencies() || ( ! is_single() && !is_product() ) ) {
			return false;
		}

		// slick slider
		wp_enqueue_style( 'slick', CS_WVSAP_PLUGIN_ASSET_URI . 'plugins/slick/slick.css', array(), CS_WVSAP_VERSION );
		wp_enqueue_script( 'slick', CS_WVSAP_PLUGIN_ASSET_URI . 'plugins/slick/slick.min.js', array(), CS_WVSAP_VERSION, true );

		wp_enqueue_style( 'wvsap.app', CS_WVSAP_PLUGIN_ASSET_URI . 'frontend/assets/css/wvsap.app.min.css', array(), CS_WVSAP_VERSION );
		wp_enqueue_script( 'wvsap.app', CS_WVSAP_PLUGIN_ASSET_URI . 'frontend/assets/js/wvsap.app.min.js', array(), CS_WVSAP_VERSION, true );

		$terms_id = AdjacentProducts::get_terms( get_the_id(), 'product_cat' );
		wp_localize_script(
			'wvsap.app',
			'wvsap_ajax',
			array(
				'url'        => admin_url( 'admin-ajax.php' ),
				'nonce'      => wp_create_nonce( SECURE_AUTH_SALT ),
				'product_id' => get_the_id(),
				'cats'       => $terms_id,
			)
		);

	}

}



