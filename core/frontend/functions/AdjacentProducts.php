<?php namespace WooVarSwatchesAdjacentProducts\frontend\functions;

/**
 * Adjacent Products
 *
 * @package Frontend
 * @since 1.0.0
 * @author M.Tuhin <info@codesolz.com>
 */

if ( ! defined( 'CS_WVSAP_VERSION' ) ) {
	exit;
}

class AdjacentProducts {

	/**
	 * Get Adjacent Products
	 * on Single Product page
	 *
	 * @param [type] $user_input
	 * @return void
	 */
	public function single_page_adjacent( $user_input ) {
		global $wpdb;

		$current_product_id    = $user_input['product_id'];
		$current_product_terms = $user_input['cats'];

		if ( empty( $current_product_id ) || empty( $current_product_terms ) ) {
			return false;
		}


		$colors = wc_get_product_terms( $current_product_id, 'pa_color', array( 'fields' => 'all' ) );

		$items = array();
		if ( $colors ) {
			foreach ( $colors as $color ) {
				$current_value = $color->slug;

				if ( empty( $item = $this->get_adjacent_item( $current_value, $current_product_id, $current_product_terms ) ) ) {
					continue;
				}

				$items = array_merge_recursive(
					$items,
					array( $color->slug => $item )
				);
			}
		}

		return wp_send_json( array( 'items' => $items ) );

	}

	/**
	 * Get adjacent product
	 *
	 * @param [type] $current_value
	 * @param [type] $current_product_id
	 * @param [type] $current_product_terms
	 * @return void
	 */
	public function get_adjacent_item( $current_value, $current_product_id, $current_product_terms ) {
		global $wpdb;

		$get_items = $wpdb->get_row(
			$wpdb->prepare(
				"SELECT GROUP_CONCAT(tr.object_id) object_id, tt.taxonomy taxonomy from {$wpdb->terms} as t 
                LEFT JOIN {$wpdb->term_taxonomy} as tt ON tt.term_id = t.term_id 
                LEFT JOIN {$wpdb->term_relationships} tr ON  tr.term_taxonomy_id = tt.term_taxonomy_id
                WHERE t.slug = %s AND tr.object_id != %d GROUP BY t.term_id",
				$current_value,
				$current_product_id
			)
		);

		if ( isset( $get_items->object_id ) && ! empty( $get_items->object_id ) ) {
			$get_variations = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT p.post_parent as product_id,pm_thumb.meta_value as _thumbnail_id, p.post_title as pname from {$wpdb->posts} as p 
                    LEFT JOIN {$wpdb->postmeta} as pm ON pm.post_id = p.ID and pm.meta_key = %s
                    LEFT JOIN {$wpdb->postmeta} as pm_thumb ON pm_thumb.post_id = p.ID and pm_thumb.meta_key = %s
                    
                    WHERE p.post_parent IN ({$get_items->object_id}) AND p.post_status = %s AND post_type = %s  and pm.meta_value = %s ",
					'attribute_' . $get_items->taxonomy,
					'_thumbnail_id',
					'publish',
					'product_variation',
					$current_value
				)
			);

			$store_currency_symbol = \get_woocommerce_currency_symbol();

			if ( $get_variations ) {
				$products = array();
				foreach ( $get_variations as $variation ) {
					$product_terms = self::get_terms( $variation->product_id, 'product_cat' );

					if ( empty( $product_terms ) ) {
						continue;
					}

					if ( empty( $thumb = \wp_get_attachment_image_src( $variation->_thumbnail_id ) ) ) {
						continue;
					}

					if ( true === $this->is_adjacent_term( $product_terms, $current_product_terms ) ) {
						$vProduct = wc_get_product( $variation->product_id );
						$products[] = array(
							'purl'    => \get_the_permalink( $variation->product_id ),
							'img_src' => isset( $thumb[0] ) ? $thumb[0] : '',
							'pname'   => $variation->pname,
							'price'   => $store_currency_symbol . $vProduct->get_variation_price() . ' - ' . $store_currency_symbol . $vProduct->get_variation_price( 'max' ),
						);
					}
				}
			}

			return $products;
		}

		return false;

	}


	/**
	 * Get products terms
	 *
	 * @param [type] $product_id
	 * @param [type] $taxonomy
	 * @return void
	 */
	public static function get_terms( $product_id, $taxonomy ) {
		$terms    = \get_the_terms( $product_id, $taxonomy );
		$terms_id = array();
		if ( ! empty( $terms ) ) {
			$flag_parent_id = '';
			$term_id        = '';
			$i              = 0;
			foreach ( $terms as $term ) {
				$terms_id[ empty( $term->parent ) ? '_np_' . \rand( 10, 1000 ) : $term->parent ] = $term->term_id;
				$terms_id = self::remove_term_parent( $term, $terms_id );
			}
			return $terms_id;
		}

		return false;
	}

	/**
	 * Remove parent terms
	 *
	 * @param [type] $term
	 * @param [type] $terms_id
	 * @return void
	 */
	private static function remove_term_parent( $term, $terms_id ) {
		if ( empty( $terms_id ) ) {
			return $terms_id;
		}
		foreach ( $terms_id as $key => $val ) {
			if ( $term->parent == $val ) {
				unset( $terms_id[ $key ] );
			}
		}
		return $terms_id;
	}

	/**
	 * Check adjacent
	 *
	 * @param [type] $product_terms
	 * @param [type] $current_product_terms
	 * @return boolean
	 */
	private function is_adjacent_term( $product_terms, $current_product_terms ) {
		$is_adjacent = false;
		foreach ( $product_terms as $term ) {
			if ( empty( $product_terms ) ) {
				continue;
			}
			if ( ! in_array( $term, $current_product_terms ) ) {
				continue;
			}

			$is_adjacent = true;
		}

		return $is_adjacent;
	}


}
