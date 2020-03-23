<?php
namespace ElementorAvator\Modules\AssetsManager\AssetTypes\Icons;

use ElementorAvator\Modules\AssetsManager\Classes\Assets_Base;
use ElementorAvator\Modules\AssetsManager\AssetTypes\Icons_Manager;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use Elementor\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Font_Awesome_Pro extends  Assets_Base {

	const FA_KIT_ID_OPTION_NAME = 'font_awesome_avator_kit_id';

	const FA_KIT_SCRIPT_LINK = 'https://kit.fontawesome.com/%s.js';

	public function get_name() {
		return __( 'Font Awesome Pro', 'elementor-avator' );
	}

	public function get_type() {
		return 'font-awesome-pro';
	}

	private function get_kit_id() {
		return get_option( 'elementor_' . self::FA_KIT_ID_OPTION_NAME, false );
	}

	public function replace_font_awesome_pro( $settings ) {
		$json_url = ELEMENTOR_AVATOR_ASSETS_URL . 'lib/font-awesome-pro/%s.js';
		$icons['fa-regular'] = [
			'name' => 'fa-regular',
			'label' => __( 'Font Awesome - Regular Pro', 'elementor-avator' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'far',
			'labelIcon' => 'fab fa-font-awesome-alt',
			'ver' => '5.12.0-pro',
			'fetchJson' => sprintf( $json_url, 'regular' ),
			'native' => true,
		];
		$icons['fa-solid'] = [
			'name' => 'fa-solid',
			'label' => __( 'Font Awesome - Solid Pro', 'elementor-avator' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fas',
			'labelIcon' => 'fab fa-font-awesome',
			'ver' => '5.12.0-pro',
			'fetchJson' => sprintf( $json_url, 'solid' ),
			'native' => true,
		];
		$icons['fa-brands'] = [
			'name' => 'fa-brands',
			'label' => __( 'Font Awesome - Brands Pro', 'elementor-avator' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fab',
			'labelIcon' => 'fab fa-font-awesome-flag',
			'ver' => '5.12.0-pro',
			'fetchJson' => sprintf( $json_url, 'brands' ),
			'native' => true,
		];
		$icons['fa-light'] = [
			'name' => 'fa-light',
			'label' => __( 'Font Awesome - Light Pro', 'elementor-avator' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fal',
			'labelIcon' => 'fal fa-flag',
			'ver' => '5.12.0-pro',
			'fetchJson' => sprintf( $json_url, 'light' ),
			'native' => true,
		];
		$icons['fa-duotone'] = [
			'name' => 'fa-duotone',
			'label' => __( 'Font Awesome - Duotone Pro', 'elementor-avator' ),
			'url' => false,
			'enqueue' => false,
			'prefix' => 'fa-',
			'displayPrefix' => 'fad',
			'labelIcon' => 'fad fa-flag',
			'ver' => '5.12.0-pro',
			'fetchJson' => sprintf( $json_url, 'duotone' ),
			'native' => true,
		];
		// remove Free
		unset(
			$settings['fa-solid'],
			$settings['fa-regular'],
			$settings['fa-brands']
		);
		return array_merge( $icons, $settings );
	}

	public function register_admin_fields( Settings $settings ) {
		$settings->add_section( Settings::TAB_INTEGRATIONS, 'font_awesome_pro', [
			'callback' => function() {
				echo '<hr><h2>' . esc_html__( 'Font Awesome Pro', 'elementor-avator' ) . '</h2>';
				esc_html_e( 'Font Awesome, the web\'s most popular icon set and toolkit, Pro Integration', 'elementor-avator' );
			},
			'fields' => [
				self::FA_KIT_ID_OPTION_NAME => [
					'label' => __( 'Kit ID', 'elementor-avator' ),
					'field_args' => [
						'type' => 'text',
						'desc' => sprintf( __( 'Enter Your <a href="%s" target="_blank">Font Awesome Pro Kit ID</a>.', 'elementor-avator' ), 'https://fontawesome.com/kits' ),
					],
					'setting_args' => [
						'sanitize_callback' => [ $this, 'sanitize_kit_id_settings' ],
					],
				],
				'validate_api_data' => [
					'field_args' => [
						'type' => 'raw_html',
						'html' => sprintf( '<button data-action="%s" data-nonce="%s" class="button elementor-button-spinner" id="elementor_avator_fa_avator_validate_button">%s</button><br><p><span class="elementor-avator-fa_avator_data hidden"></span></p>',
							self::FA_KIT_ID_OPTION_NAME . '_fetch',
							wp_create_nonce( self::FA_KIT_ID_OPTION_NAME ),
							__( 'Validate Kit ID', 'elementor-avator' )
						),
					],
				],
			],
		] );
	}

	public function enqueue_kit_js() {
		wp_enqueue_script( 'font-awesome-pro', sprintf( self::FA_KIT_SCRIPT_LINK, $this->get_kit_id() ), [], ELEMENTOR_AVATOR_VERSION );
	}

	public function sanitize_kit_id_settings( $input ) {
		if ( empty( $input ) ) {
			delete_option( 'elementor_' . self::FA_KIT_ID_OPTION_NAME );
		}

		return $input;
	}

	protected function actions() {
		parent::actions();

		if ( is_admin() ) {
			add_action( 'elementor/admin/after_create_settings/' . Settings::PAGE_ID, [ $this, 'register_admin_fields' ], 100 );
		}

		if ( $this->get_kit_id() ) {
			add_filter( 'elementor/icons_manager/native', [ $this, 'replace_font_awesome_pro' ] );
			add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_kit_js' ] );
			add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'enqueue_kit_js' ] );
		}
	}
}
