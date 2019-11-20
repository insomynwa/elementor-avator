<?php
namespace ElementorAvator\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Footer extends Header_Footer_Base {

	public static function get_properties() {
		$propertyes = parent::get_properties();

		$propertyes['location'] = 'footer';

		return $propertyes;
	}

	public function get_name() {
		return 'footer';
	}

	public static function get_title() {
		return __( 'Footer', 'elementor-avator' );
	}
}
