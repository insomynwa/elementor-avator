<?php
namespace ElementorAvator\Modules\ThemeBuilder\Documents;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Theme_Section_Document extends Theme_Document {

	public static function get_properties() {
		$propertyes = parent::get_properties();

		$propertyes['condition_type'] = 'general';

		return $propertyes;
	}

	public static function get_preview_as_default() {
		return '';
	}

	public static function get_preview_as_options() {
		return array_merge(
			[
				'' => __( 'Select...', 'elementor-avator' ),
			],
			Archive::get_preview_as_options(),
			Single::get_preview_as_options()
		);
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['category'] = '';

		return $config;
	}
}
