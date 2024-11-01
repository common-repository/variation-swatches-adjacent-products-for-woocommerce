<?php namespace WooVarSwatchesAdjacentProducts\install;

/**
 * Installation
 *
 * @package Install
 * @since 1.0.0
 * @author M.Tuhin <info@codesolz.com>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	exit;
}


class Activate {


	/**
	 * Install DB
	 *
	 * @return void
	 */
	public static function on_activate() {

		// add db version to db
		add_option( 'wvsap_plugin_version', CS_WVSAP_VERSION );
		add_option( 'wvsap_plugin_install_date', date( 'Y-m-d H:i:s' ) );
	}

	/**
	 * Remove custom urls on detactive
	 *
	 * @return void
	 */
	public static function on_deactivate() {
		// remove notice status
		delete_option( CS_WVSAP_NOTICE_ID . 'ed_Activated' );
		delete_option( CS_WVSAP_NOTICE_ID . 'ed_Feedback' );
		return true;
	}

	/**
	 * show notices
	 *
	 * @return void
	 */
	public static function onUpgrade() {
		// remove notice status
		delete_option( CS_WVSAP_NOTICE_ID . 'ed_Feedback' );
		return true;
	}


}
