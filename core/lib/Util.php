<?php namespace WooVarSwatchesAdjacentProducts\lib;

/**
 * Util Functions
 *
 * @package Library
 * @since 1.0.0
 * @author CodeSolz <info@codesolz.com>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	exit;
}


class Util {

	/**
	 * Encode Html Entites
	 *
	 * @param type $str
	 * @return type
	 */
	public static function encode_html_chars( $str ) {
		return esc_html( $str );
	}

	/**
	 * markup tagline
	 *
	 * @param type $tagline
	 */
	public static function markup_tag( $tagline ) {
		 echo sprintf( "\n<!--%s - %s-->\n", CS_WVSAP_PLUGIN_NAME, $tagline );
	}

	/**
	 * Check Evil Script Into User Input
	 *
	 * @param array|string $user_input
	 * @return type
	 */
	public static function check_evil_script( $user_input, $textarea = false ) {
		if ( is_array( $user_input ) ) {
			$user_input = self::cs_sanitize_recursive( $user_input, $textarea );
		} else {
			$user_input = self::cs_sanitize_field( $user_input, $textarea );
		}
		return $user_input;
	}

	/**
	 * Sanitize recursive array
	 *
	 * @param [type]  $user_input
	 * @param boolean $textarea
	 * @return void
	 */
	public static function cs_sanitize_recursive( $user_input, $textarea = false ) {
		foreach ( $user_input as $key => $value ) {
			if ( is_array( $value ) ) {
				$value = self::cs_sanitize_recursive( $value, $textarea );
			} else {
				$value = self::cs_sanitize_field( $value, $textarea );
			}
		}

		return $user_input;
	}

	/**
	 * Sanitize field
	 *
	 * @param [type] $user_input
	 * @param [type] $textarea
	 * @return void
	 */
	public static function cs_sanitize_field( $user_input, $textarea = false ) {
		if ( $textarea === true ) {
			$user_input = \sanitize_textarea_field( $user_input );
		} else {
			$user_input = \sanitize_text_field( $user_input );
		}
		return self::cs_stripslashes( $user_input );
	}

	/**
	 * Add slashes
	 *
	 * @param [type] $value
	 * @return void
	 */
	public static function cs_addslashes( $value ) {
		return \wp_slash( \stripslashes_deep( trim( $value ) ) );
	}

	/**
	 * Strip slashes
	 *
	 * @param [type] $value
	 * @return void
	 */
	public static function cs_esc_html( $value ) {
		return \esc_html( \stripslashes_deep( trim( $value ) ) );
	}

	/**
	 * Strip slashes
	 *
	 * @param [type] $value
	 * @return void
	 */
	public static function cs_stripslashes( $value ) {
		return \stripslashes_deep( trim( $value ) );
	}

	/**
	 * generate admin page url
	 *
	 * @return string
	 */
	public static function cs_generate_admin_url( $page_name ) {
		if ( empty( $page_name ) ) {
			return '';
		}

		return \admin_url( "admin.php?page={$page_name}" );
	}

	/**
	 * Free plugins
	 *
	 * @return void
	 */
	public static function cs_free_plugins() {
		return \self_admin_url( 'plugin-install.php?s=codesolz&tab=search&type=author' );
	}


}

