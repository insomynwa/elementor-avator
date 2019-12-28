<?php
namespace ElementorAvator\Modules\TableOfContents;

use ElementorAvator\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		add_filter( 'elementor_avator/editor/localize_settings', [ $this, 'localize_settings' ] );
	}

	public function get_widgets() {
		return [
			'Table_Of_Contents',
		];
	}

	public function get_name() {
		return 'table-of-contents';
	}

	public function localize_settings( array $settings ) {
		$settings['i18n']['toc_no_headings_found'] = __( 'No headings were found on this page.', 'elementor-avator' );

		return $settings;
	}
}