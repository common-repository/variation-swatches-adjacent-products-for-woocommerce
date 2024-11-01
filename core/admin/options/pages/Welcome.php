<?php namespace WooVarSwatchesAdjacentProducts\admin\options\pages;

/**
 * Class: Add New Rule
 *
 * @package Options
 * @since 1.0.0
 * @author M.Tuhin <info@codesolz.net>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	die();
}

use WooVarSwatchesAdjacentProducts\admin\builders\FormBuilder;
use WooVarSwatchesAdjacentProducts\admin\builders\AdminPageBuilder;


class Welcome {

	/**
	 * Hold page generator class
	 *
	 * @var type
	 */
	private $Admin_Page_Generator;

	/**
	 * Form Generator
	 *
	 * @var type
	 */
	private $Form_Generator;


	public function __construct( AdminPageBuilder $AdminPageGenerator ) {
		$this->Admin_Page_Generator = $AdminPageGenerator;

		/*create obj form generator*/
		$this->Form_Generator = new FormBuilder();

	}

	/**
	 * Generate add new coin page
	 *
	 * @param type $args
	 * @return type
	 */
	public function generate_page( $args, $option ) {
		$args['show_btn']   = false;
		$args['body_class'] = 'no-bottom-margin';

		ob_start();
		?>
			<h3><?php _e( 'All clean!', 'variation-swatches-adjacent-products-for-woocommerce' ); ?></h3>
			<p><?php _e( 'No configuration required!', 'variation-swatches-adjacent-products-for-woocommerce' ); ?></p>
		<?php

		$html = ob_get_clean();

		$args['content'] = $html;

		return $this->Admin_Page_Generator->generate_page( $args );

	}


}


