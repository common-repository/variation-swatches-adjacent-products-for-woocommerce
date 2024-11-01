<?php

/**
 * Check Dependencies
 *
 * @package Install
 * @since 1.0.0
 * @author M.Tuhin <info@codesolz.com>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	exit;
}

if ( ! function_exists( 'CsWvsapCheckDependencies' ) ) {

	function CsWvsapCheckDependencies() {

		// trigger notice
		add_action(
			'admin_notices',
			function() {
				?>
				<div class="notice cs-notice notice-error" >
					<p>
						<strong><?php echo CS_WVSAP_PLUGIN_NAME; ?></strong>
					</p>
					<p>
					<?php
						echo sprintf(
							__(
								'In order to activate and use %2$s%1$s%3$s at first you need to keep installed & activate any one of the following plugins <br><br>'
								. '1. %4$sVariation Swatches for WooCommerce%5$s - %7$s Emran Ahmed %8$s <br>'
								. '2. %6$sVariation Swatches for WooCommerce%5$s - %7$s ThemeAlien %8$s <br>'
								. '3. %9$sProduct Variations Swatches for WooCommerce%5$s - %7$s VillaTheme %8$s <br>'
								. '4. %10$sVariation Swatches for WooCommerce%5$s - %7$s RadiusTheme %8$s <br>'
								. '5. %11$sSmart Variation Swatches for WooCommerce%5$s - %7$s aThemeArt %8$s <br>'
								. '6. %12$sVariation Swatches for WooCommerce%5$s - %7$s ThemeHigh %8$s <br>'
								. '7. %13$sXT WooCommerce Variation Swatches%5$s - %7$s XplodedThemes %8$s',
								'better-find-and-replace-pro'
							),
							CS_WVSAP_PLUGIN_NAME,
							'<code>',
							'</code>',
							'<a href="https://wordpress.org/plugins/woo-variation-swatches/" target="_blank">',
							'</a>',
							'<a href="https://wordpress.org/plugins/variation-swatches-for-woocommerce/" target="_blank">',
							'<b><i>', 
							'</i></b>',
							'<a href="https://wordpress.org/plugins/product-variations-swatches-for-woocommerce/" target="_blank">',
							'<a href="https://wordpress.org/plugins/woo-product-variation-swatches/" target="_blank">',
							'<a href="https://wordpress.org/plugins/variation-swatches-style/" target="_blank">',
							'<a href="https://wordpress.org/plugins/product-variation-swatches-for-woocommerce/" target="_blank">',
							'<a href="https://wordpress.org/plugins/xt-woo-variation-swatches/" target="_blank">'
						);
					?>
					</p>

				</div>
				<?php
			}
		);


		if ( ! function_exists( 'deactivate_plugins' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		deactivate_plugins( 'variation-swatches-adjacent-products-for-woocommerce/woo-variation-swatches-adjacent-products.php', true );

		return false;
	}
}


if ( function_exists( 'add_action' ) ) {
	add_action(
		'admin_init',
		function() {
			if( false === CsWvsapHasDependencies() ){	
				CsWvsapCheckDependencies();
			}
		}
	);

}


if ( ! function_exists( 'CsWvsapHasDependencies' ) ) {

	function CsWvsapHasDependencies() {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . '/wp-admin/includes/plugin.php';
		}

		if ( is_plugin_active( 'woo-variation-swatches/woo-variation-swatches.php' ) ||
			is_plugin_active( 'variation-swatches-for-woocommerce/variation-swatches-for-woocommerce.php' ) ||
			is_plugin_active( 'product-variations-swatches-for-woocommerce/product-variations-swatches-for-woocommerce.php' ) ||
			is_plugin_active( 'woo-product-variation-swatches/woo-product-variation-swatches.php' ) ||
			is_plugin_active( 'variation-swatches-style/smart-variation-swatches-for-woocommerce.php' ) ||
			is_plugin_active( 'product-variation-swatches-for-woocommerce/product-variation-swatches-for-woocommerce.php' ) ||
			is_plugin_active( 'xt-woo-variation-swatches/xt-woo-variation-swatches.php' ) 
		) {
			return true;
		}

		return false;
	}
}

