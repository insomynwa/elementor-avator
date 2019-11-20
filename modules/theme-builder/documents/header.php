<?php
namespace ElementorAvator\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Header extends Header_Footer_Base {

	public static function get_properties() {
		$propertyes = parent::get_properties();

		$propertyes['location'] = 'header';

		return $propertyes;
	}

	public function get_name() {
		return 'header';
	}

	public static function get_title() {
		return __( 'Header', 'elementor-avator' );
	}
}
