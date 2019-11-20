<?php
namespace ElementorAvator\Modules\Woocommerce\Tags;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Product_Price extends Base_Tag {
	public function get_name() {
		return 'woocommerce-product-price-tag';
	}

	public function get_title() {
		return __( 'Product Price', 'elementor-avator' );
	}

	protected function _register_controls() {
		$this->add_control( 'format', [
			'label' => __( 'Format', 'elementor-avator' ),
			'type' => Controls_Manager::SELECT,
			'options' => [
				'both' => __( 'Both', 'elementor-avator' ),
				'original' => __( 'Original', 'elementor-avator' ),
				'sale' => __( 'Sale', 'elementor-avator' ),
			],
			'default' => 'both',
		] );
	}

	public function render() {
		$product = wc_get_product();
		if ( ! $product ) {
			return '';
		}

		$format = $this->get_settings( 'format' );
		$value = '';
		switch ( $format ) {
			case 'both':
				$value = $product->get_price_html();
				break;
			case 'original':
				$value = wc_price( $product->get_regular_price() ) . $product->get_price_suffix();
				break;
			case 'sale' && $product->is_on_sale():
				$value = wc_price( $product->get_sale_price() ) . $product->get_price_suffix();
				break;
		}

		echo $value;
	}
}
