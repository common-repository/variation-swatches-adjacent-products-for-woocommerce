<?php namespace WooVarSwatchesAdjacentProducts\actions;


/**
 * Class: WordPress Default Hooks
 *
 * @package Action
 * @since 1.0.0
 * @author M.Tuhin <info@codesolz.net>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	die();
}

use WooVarSwatchesAdjacentProducts\lib\Util;

class WvsapWPHooks {
	
	function __construct() {
		/*** add settings link */
		add_filter( 'plugin_action_links_' . CS_WVSAP_PLUGIN_IDENTIFIER, array( $this, 'wvsap_action_links' ) );

		/*** add docs link */
		add_filter( 'plugin_row_meta', array( $this, 'upn_plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Add settings links
	 *
	 * @param [type] $links
	 * @return void
	 */
	public static function wvsap_action_links( $links ) {
		$custom_links = array(
			'welcome' => '<a href="' . Util::cs_generate_admin_url( 'cs-wvsap-welcome' ) . '">' . __( 'Welcome Note', 'variation-swatches-adjacent-products-for-woocommerce' ) . '</a>',
		);

		return array_merge( $custom_links, $links );
	}


	/**
	 * Plugins Row
	 *
	 * @param [type] $links
	 * @param [type] $file
	 * @return void
	 */
	public function upn_plugin_row_meta( $links, $file ) {
		if ( plugin_basename( CS_WVSAP_PLUGIN_IDENTIFIER ) !== $file ) {
			return $links;
		}

		$row_meta = apply_filters(
			'rtafar_row_meta',
			array(
				'docs'    => '<a target="_blank" href="' . esc_url( 'https://docs.codesolz.net/variation-swatches-adjacent-products-for-woocommerce/' ) . '" aria-label="' . esc_attr__( 'documentation', 'variation-swatches-adjacent-products-for-woocommerce' ) . '">' . esc_html__( 'Docs', 'variation-swatches-adjacent-products-for-woocommerce' ) . '</a>',
				'videos'  => '<a target="_blank" href="' . esc_url( 'https://www.youtube.com/watch?v=t5dhWa9Lh3M&list=PLxLVEan0phTsKHS6gXFZPFXcmU-GVLtxW' ) . '" aria-label="' . esc_attr__( 'Video Tutorials', 'variation-swatches-adjacent-products-for-woocommerce' ) . '">' . esc_html__( 'Video Tutorials', 'variation-swatches-adjacent-products-for-woocommerce' ) . '</a>',
				'support' => '<a target="_blank" href="' . esc_url( 'https://codesolz.net/forum' ) . '" aria-label="' . esc_attr__( 'Community support', 'variation-swatches-adjacent-products-for-woocommerce' ) . '">' . esc_html__( 'Community support', 'variation-swatches-adjacent-products-for-woocommerce' ) . '</a>',
			)
		);

		return array_merge( $links, $row_meta );

	}

}

