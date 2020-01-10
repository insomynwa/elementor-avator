<?php
namespace ElementorAvator\Core\Admin;

use Elementor\Core\Base\App;
use Elementor\Rollback;
use Elementor\Settings;
use Elementor\Tools;
use Elementor\Utils;
use ElementorAvator\License\API;
use ElementorAvator\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Admin extends App {

	/**
	 * Get module name.
	 *
	 * Retrieve the module name.
	 *
	 * @since 2.3.0
	 * @access public
	 *
	 * @return string Module name.
	 */
	public function get_name() {
		return 'admin';
	}

	/**
	 * Enqueue admin styles.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function enqueue_styles() {
		$suffix = Utils::is_script_debug() ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		wp_register_style(
			'elementor-avator-admin',
			ELEMENTOR_AVATOR_ASSETS_URL . 'css/admin' . $direction_suffix . $suffix . '.css',
			[],
			ELEMENTOR_AVATOR_VERSION
		);

		wp_enqueue_style( 'elementor-avator-admin' );
	}

	public function enqueue_scripts() {
		$suffix = Utils::is_script_debug() ? '' : '.min';

		wp_enqueue_script(
			'elementor-avator-admin',
			ELEMENTOR_AVATOR_URL . 'assets/js/admin' . $suffix . '.js',
			[
				'elementor-common',
			],
			ELEMENTOR_AVATOR_VERSION,
			true
		);

		$locale_settings = [];

		/**
		 * Localize admin settings.
		 *
		 * Filters the admin localized settings.
		 *
		 * @since 1.0.0
		 *
		 * @param array $locale_settings Localized settings.
		 */
		$locale_settings = apply_filters( 'elementor_avator/admin/localize_settings', $locale_settings );

		Utils::print_js_config(
			'elementor-avator-admin',
			'ElementorAvatorConfig',
			$locale_settings
		);
	}

	public function remove_go_avator_menu() {
		remove_action( 'admin_menu', [ Plugin::elementor()->settings, 'register_pro_menu' ], Settings::MENU_PRIORITY_GO_PRO );
	}

	private function get_rollback_versions() {
		$rollback_versions = get_transient( 'elementor_avator_rollback_versions_' . ELEMENTOR_AVATOR_VERSION );
		if ( false === $rollback_versions ) {
			$max_versions = 30;

			$versions = API::get_previous_versions();

			if ( is_wp_error( $versions ) ) {
				return [];
			}

			$rollback_versions = [];

			$current_index = 0;
			foreach ( $versions as $version ) {
				if ( $max_versions <= $current_index ) {
					break;
				}

				if ( preg_match( '/(trunk|beta|rc)/i', strtolower( $version ) ) ) {
					continue;
				}

				if ( version_compare( $version, ELEMENTOR_VERSION, '>=' ) ) {
					continue;
				}

				$current_index++;
				$rollback_versions[] = $version;
			}

			set_transient( 'elementor_avator_rollback_versions_' . ELEMENTOR_AVATOR_VERSION, $rollback_versions, WEEK_IN_SECONDS );
		}

		return $rollback_versions;
	}

	public function register_admin_tools_fields( Tools $tools ) {
		$rollback_html = '<select class="elementor-rollback-select">';

		foreach ( $this->get_rollback_versions() as $version ) {
			$rollback_html .= "<option value='{$version}'>$version</option>";
		}
		$rollback_html .= '</select>';

		// Rollback
		$tools->add_fields( 'versions', 'rollback', [
			'rollback_avator_separator' => [
				'field_args' => [
					'type' => 'raw_html',
					'html' => '<hr>',
				],
			],
			/* 'rollback_pro' => [
				'label' => __( 'Rollback Avator Version', 'elementor-avator' ),
				'field_args' => [
					'type' => 'raw_html',
					'html' => sprintf(
						$rollback_html . '<a data-placeholder-text="' . __( 'Reinstall', 'elementor-avator' ) . ' v{VERSION}" href="#" data-placeholder-url="%s" class="button elementor-button-spinner elementor-rollback-button">%s</a>',
						wp_nonce_url( admin_url( 'admin-post.php?action=elementor_avator_rollback&version=VERSION' ), 'elementor_avator_rollback' ),
						__( 'Reinstall', 'elementor-avator' )
					),
					'desc' => '<span style="color: red;">' . __( 'Warning: Please backup your database before making the rollback.', 'elementor-avator' ) . '</span>',
				],
			], */
		] );
	}

	public function post_elementor_avator_rollback() {
		check_admin_referer( 'elementor_avator_rollback' );

		$rollback_versions = $this->get_rollback_versions();
		if ( empty( $_GET['version'] ) || ! in_array( $_GET['version'], $rollback_versions, true ) ) {
			wp_die( __( 'Error occurred, The version selected is invalid. Try selecting different version.', 'elementor-avator' ) );
		}

		$package_url = API::get_plugin_package_url( $_GET['version'] );
		if ( is_wp_error( $package_url ) ) {
			wp_die( $package_url );
		}

		$plugin_slug = basename( ELEMENTOR_AVATOR__FILE__, '.php' );

		$rollback = new Rollback( [
			'version' => $_GET['version'],
			'plugin_name' => ELEMENTOR_AVATOR_PLUGIN_BASE,
			'plugin_slug' => $plugin_slug,
			'package_url' => $package_url,
		] );

		$rollback->run();

		wp_die( '', __( 'Rollback to Previous Version', 'elementor-avator' ), [ 'response' => 200 ] );
	}

	public function plugin_action_links( $links ) {
		unset( $links['go_pro'] );

		return $links;
	}

	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( ELEMENTOR_AVATOR_PLUGIN_BASE === $plugin_file ) {
			$plugin_slug = basename( ELEMENTOR_AVATOR__FILE__, '.php' );
			$plugin_name = __( 'Elementor Avator', 'elementor-avator' );

			$row_meta = [
				'view-details' => sprintf( '<a href="%s" class="thickbox open-plugin-details-modal" aria-label="%s" data-title="%s">%s</a>',
					esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin=' . $plugin_slug . '&TB_iframe=true&width=600&height=550' ) ),
					/* translators: %s: Plugin name - Elementor Avator. */
					esc_attr( sprintf( __( 'More information about %s', 'elementor-avator' ), $plugin_name ) ),
					esc_attr( $plugin_name ),
					__( 'View details', 'elementor-avator' )
				),
				'changelog' => '<a href="https://go.elementor.com/avator-changelog/" title="' . esc_attr( __( 'View Elementor Avator Changelog', 'elementor-avator' ) ) . '" target="_blank">' . __( 'Changelog', 'elementor-avator' ) . '</a>',
			];

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

	public function change_tracker_params( $params ) {
		unset( $params['is_first_time'] );

		return $params;
	}

	public function add_finder_items( array $categories ) {
		$settings_url = Settings::get_url();

		$categories['settings']['items']['integrations'] = [
			'title' => __( 'Integrations', 'elementor-avator' ),
			'icon' => 'integration',
			'url' => $settings_url . '#tab-integrations',
			'keywords' => [ 'integrations', 'settings', 'typekit', 'facebook', 'recaptcha', 'mailchimp', 'drip', 'activecampaign', 'getresponse', 'convertkit', 'elementor' ],
		];

		return $categories;
	}

	/**
	 * Admin constructor.
	 */
	public function __construct() {
		$this->add_component( 'canary-deployment', new Canary_Deployment() );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_menu', [ $this, 'remove_go_avator_menu' ], 0 );

		add_action( 'elementor/admin/after_create_settings/' . Tools::PAGE_ID, [ $this, 'register_admin_tools_fields' ], 50 );

		add_filter( 'plugin_action_links_' . ELEMENTOR_PLUGIN_BASE, [ $this, 'plugin_action_links' ], 50 );
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );

		add_filter( 'elementor/finder/categories', [ $this, 'add_finder_items' ] );

		add_filter( 'elementor/tracker/send_tracking_data_params', [ $this, 'change_tracker_params' ], 200 );
		add_action( 'admin_post_elementor_avator_rollback', [ $this, 'post_elementor_avator_rollback' ] );
	}
}
