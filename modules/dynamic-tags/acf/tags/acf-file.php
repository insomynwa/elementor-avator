<?php
namespace ElementorAvator\Modules\DynamicTags\ACF\Tags;

use Elementor\Controls_Manager;
use ElementorAvator\Modules\DynamicTags\Tags\Base\Data_Tag;
use ElementorAvator\Modules\DynamicTags\ACF\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ACF_File extends ACF_Image {

	public function get_name() {
		return 'acf-file';
	}

	public function get_title() {
		return __( 'ACF', 'elementor-avator' ) . ' ' . __( 'File Field', 'elementor-avator' );
	}

	public function get_categories() {
		return [
			Module::MEDIA_CATEGORY,
		];
	}

	public function get_supported_fields() {
		return [
			'file',
		];
	}
}
