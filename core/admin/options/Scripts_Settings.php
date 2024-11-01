<?php namespace WooVarSwatchesAdjacentProducts\admin\options;

/**
 * Class: Admin Menu Scripts
 *
 * @package Admin
 * @since 1.0.0
 * @author M.Tuhin <info@codesolz.net>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	exit;
}

use WooVarSwatchesAdjacentProducts\lib\Util;


class Scripts_Settings {

	/**
	 * load admin settings scripts
	 */
	public static function load_admin_settings_scripts( $page_id, $rtafr_menu ) {

		$rtafr_menu = apply_filters( 'wvsap_menu_scripts', $rtafr_menu );

		wp_enqueue_style( 'wvsap', CS_WVSAP_PLUGIN_ASSET_URI . 'admin/assets/css/style.min.css', false );

		return;
	}

	/**
	 * admin footer script processor
	 *
	 * @global array $rtafr_menu
	 * @param string $page_id
	 */
	public static function load_admin_footer_script( $page_id, $rtafr_menu ) {

		Util::markup_tag( __( 'admin footer script start', 'variation-swatches-adjacent-products-for-woocommerce' ) );

		// load form submit script on footer
		if ( ( isset( $rtafr_menu['menu_welcome'] ) && $page_id == $rtafr_menu['menu_welcome'] ) ) {
			// custom scripts here
		}

		Util::markup_tag( __( 'admin footer script end', 'variation-swatches-adjacent-products-for-woocommerce' ) );

		return;
	}

}


