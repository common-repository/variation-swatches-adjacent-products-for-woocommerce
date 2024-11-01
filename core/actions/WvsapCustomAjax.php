<?php namespace WooVarSwatchesAdjacentProducts\actions;

/**
 * Class: Custom ajax call
 *
 * @package Admin
 * @since 1.0.0
 * @author M.Tuhin <info@codesolz.net>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	die();
}


class WvsapCustomAjax {

	function __construct() {
		add_action( 'wp_ajax_wvsap_ajax', array( $this, 'wvsap_ajax' ) );
		add_action( 'wp_ajax_nopriv_wvsap_ajax', array( $this, 'wvsap_ajax' ) );
	}


	/**
	 * custom ajax call
	 */
	public function wvsap_ajax() {

		if ( ! isset( $_REQUEST['cs_token'] ) || false === check_ajax_referer( SECURE_AUTH_SALT, 'cs_token', false ) ) {
			wp_send_json(
				array(
					'status' => false,
					'title'  => __( 'Invalid token', 'variation-swatches-adjacent-products-for-woocommerce' ),
					'text'   => __( 'Sorry! we are unable recognize your auth!', 'variation-swatches-adjacent-products-for-woocommerce' ),
				)
			);
		}

		if ( ! isset( $_REQUEST['data'] ) && isset( $_POST['method'] ) ) {
			$data = \sanitize_post( $_POST );
		} else {
			$data = \sanitize_post( $_REQUEST['data'] );
		}

		if ( empty( $method = $data['method'] ) || strpos( $method, '@' ) === false ) {
			wp_send_json(
				array(
					'status' => false,
					'title'  => __( 'Invalid Request', 'variation-swatches-adjacent-products-for-woocommerce' ),
					'text'   => __( 'Method parameter missing / invalid!', 'variation-swatches-adjacent-products-for-woocommerce' ),
				)
			);
		}
		$method     = explode( '@', $method );
		$class_path = str_replace( '\\\\', '\\', '\\WooVarSwatchesAdjacentProducts\\' . $method[0] );
		if ( ! class_exists( $class_path ) ) {
			wp_send_json(
				array(
					'status' => false,
					'title'  => __( 'Invalid Library', 'variation-swatches-adjacent-products-for-woocommerce' ),
					'text'   => sprintf( __( 'Library Class "%s" not found! ', 'variation-swatches-adjacent-products-for-woocommerce' ), $class_path ),
				)
			);
		}

		if ( ! method_exists( $class_path, $method[1] ) ) {
			wp_send_json(
				array(
					'status' => false,
					'title'  => __( 'Invalid Method', 'variation-swatches-adjacent-products-for-woocommerce' ),
					'text'   => sprintf( __( 'Method "%1$s" not found in Class "%2$s"! ', 'variation-swatches-adjacent-products-for-woocommerce' ), $method[1], $class_path ),
				)
			);
		}

		echo ( new $class_path() )->{$method[1]}( $data );
		exit;
	}

}


