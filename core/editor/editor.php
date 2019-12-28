<?php
namespace ElementorAvator\Core\Editor;

use Elementor\Core\Base\App;
use ElementorAvator\License\Admin as License_Admin;
use ElementorAvator\License\API as License_API;
use ElementorAvator\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Editor extends App {

	/**
	 * Get app name.
	 *
	 * Retrieve the app name.
	 *
	 * @return string app name.
	 * @since  2.6.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'avator-editor';
	}

	public function __construct() {
		add_action( 'elementor/init', [ $this, 'on_elementor_init' ] );
		add_action( 'elementor/editor/init', [ $this, 'on_elementor_editor_init' ] );
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
	}

	public function get_init_settings() {
		$is_license_active = false;

		$license_key = License_Admin::get_license_key();

		if ( ! empty( $license_key ) ) {
			$license_data = License_API::get_license_data();

			if ( ! empty( $license_data['license'] ) && License_API::STATUS_VALID === $license_data['license'] ) {
				$is_license_active = true;
			}
		}

		$settings = [
			'i18n' => [],
			'isActive' => true,
			'urls' => [
				'modules' => ELEMENTOR_AVATOR_MODULES_URL,
			],
		];

		/**
		 * Editor settings.
		 *
		 * Filters the editor settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $settings settings.
		 */
		$settings = apply_filters( 'elementor_avator/editor/localize_settings', $settings );

		return $settings;
	}

	public function enqueue_editor_styles() {
		wp_enqueue_style(
			'elementor-avator',
			$this->get_css_assets_url( 'editor', null, 'default', true ),
			[
				'elementor-editor',
			],
			ELEMENTOR_AVATOR_VERSION
		);
	}

	public function enqueue_editor_scripts() {
		wp_enqueue_script(
			'elementor-avator',
			$this->get_js_assets_url( 'editor' ),
			[
				'backbone-marionette',
				'elementor-common-modules',
				'elementor-editor-modules',
			],
			ELEMENTOR_AVATOR_VERSION,
			true
		);

		$this->print_config( 'elementor-avator' );
	}

	public function on_elementor_init() {
		Plugin::elementor()->editor->notice_bar = new Notice_Bar();
	}

	public function on_elementor_editor_init() {
		Plugin::elementor()->common->add_template( __DIR__ . '/template.php' );
	}

	protected function get_assets_base_url() {
		return ELEMENTOR_AVATOR_URL;
	}
}
