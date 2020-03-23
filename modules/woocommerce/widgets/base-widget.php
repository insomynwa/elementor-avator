<?php
namespace ElementorAvator\Modules\Woocommerce\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Base_Widget extends \ElementorAvator\Base\Base_Widget {

	public function get_categories() {
		return [ 'woocommerce-elements-single' ];
	}
}
