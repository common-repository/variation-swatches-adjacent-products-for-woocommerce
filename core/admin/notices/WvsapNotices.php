<?php namespace WooVarSwatchesAdjacentProducts\admin\notices;

/**
 * Admin Notice
 *
 * @package Notices
 * @since 1.0.0
 * @author M.Tuhin <tuhin@codesolz.net>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	exit;
}

use WooVarSwatchesAdjacentProducts\lib\Util;
use WooVarSwatchesAdjacentProducts\admin\builders\NoticeBuilder;


class WvsapNotices {

	public static function init() {
		$notice = NoticeBuilder::get_instance();
		self::activated( $notice );
		self::feedback( $notice );
	}

	/**
	 * Activated Notice
	 *
	 * @return String
	 */
	public static function activated( $notice ) {
		$message = __( 'Thank you for choosing us. No special configuration required! Magic has applied to the product single page!', 'variation-swatches-adjacent-products-for-woocommerce' );
		$notice->info( $message, 'Activated' );
	}


	/**
	 * Feedback
	 *
	 * @return void
	 */
	public static function feedback( $notice ) {
		// check installed time
		$installedOn = get_option( 'wvsap_plugin_install_date' );
		if ( empty( $installedOn ) ) {
			add_option( 'wvsap_plugin_install_date', date( 'Y-m-d H:i:s' ) );

			return false;
		}
		$date1 = new \DateTime( date( 'Y-m-d', \strtotime( $installedOn ) ) );
		$date2 = new \DateTime( date( 'Y-m-d' ) );

		if ( $date1->diff( $date2 )->days < 14 ) {
			return false;
		}
		$timeDiff    = \human_time_diff( \strtotime( $installedOn ), current_time( 'U' ) );
		$message     = __(
			'You are using our plugin more then %s. If you are enjoying it, %s would you mind%s to %s give us a 5 stars %s (%s) review?
			%2$s Your valuable review %3$s will %2$s inspire us %3$s to make it more better.',
			'variation-swatches-adjacent-products-for-woocommerce'
		);
		$review_link = 'https://wordpress.org/plugins/variation-swatches-adjacent-products-for-woocommerce';
		$message     = sprintf(
			$message,
			$timeDiff,
			'<b>',
			'</b>',
			'<a href="' . $review_link . '" target="_blank"><strong>',
			'</strong></a>',
			'<span class="dashicons dashicons-star-filled">
			</span><span class="dashicons dashicons-star-filled">
			</span><span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>
			<span class="dashicons dashicons-star-filled"></span>'
		);
		$notice->info( $message, 'Feedback' );
	}


}


