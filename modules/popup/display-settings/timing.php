<?php

namespace ElementorAvator\Modules\Popup\DisplaySettings;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Timing extends Base {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @since  2.4.0
	 * @access public
	 *
	 * @return string The name.
	 */
	public function get_name() {
		return 'popup_timing';
	}

	protected function _register_controls() {
		$this->start_controls_section( 'timing' );

		$this->start_settings_group( 'page_views', __( 'Show after X page views', 'elementor-avator' ) );

		$this->add_settings_group_control(
			'views',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Page Views', 'elementor-avator' ),
				'default' => 3,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sessions', __( 'Show after X sessions', 'elementor-avator' ) );

		$this->add_settings_group_control(
			'sessions',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Sessions', 'elementor-avator' ),
				'default' => 2,
				'min' => 1,
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'times', __( 'Show up to X times', 'elementor-avator' ) );

		$this->add_settings_group_control(
			'times',
			[
				'type' => Controls_Manager::NUMBER,
				'label' => __( 'Times', 'elementor-avator' ),
				'default' => 3,
				'min' => 1,
			]
		);

		$this->add_settings_group_control(
			'count',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Count', 'elementor-avator' ),
				'options' => [
					'' => __( 'On Open', 'elementor-avator' ),
					'close' => __( 'On Close', 'elementor-avator' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'url', __( 'When arriving from specific URL', 'elementor-avator' ) );

		$this->add_settings_group_control(
			'action',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'show',
				'options' => [
					'show' => __( 'Show', 'elementor-avator' ),
					'hide' => __( 'Hide', 'elementor-avator' ),
					'regex' => __( 'Regex', 'elementor-avator' ),
				],
			]
		);

		$this->add_settings_group_control(
			'url',
			[
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'URL', 'elementor-avator' ),
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'sources', __( 'Show when arriving from', 'elementor-avator' ) );

		$this->add_settings_group_control(
			'sources',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'search', 'external', 'internal' ],
				'options' => [
					'search' => __( 'Search Engines', 'elementor-avator' ),
					'external' => __( 'External Links', 'elementor-avator' ),
					'internal' => __( 'Internal Links', 'elementor-avator' ),
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'logged_in', __( 'Hide for logged in users', 'elementor-avator' ) );

		$this->add_settings_group_control(
			'users',
			[
				'type' => Controls_Manager::SELECT,
				'default' => 'all',
				'options' => [
					'all' => __( 'All Users', 'elementor-avator' ),
					'custom' => __( 'Custom', 'elementor-avator' ),
				],
			]
		);

		global $wp_roles;

		$roles = array_map( function( $role ) {
			return $role['name'];
		}, $wp_roles->roles );

		$this->add_settings_group_control(
			'roles',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [],
				'options' => $roles,
				'select2options' => [
					'placeholder' => __( 'Select Roles', 'elementor-avator' ),
				],
				'condition' => [
					'users' => 'custom',
				],
			]
		);

		$this->end_settings_group();

		$this->start_settings_group( 'devices', __( 'Show on devices', 'elementor-avator' ) );

		$this->add_settings_group_control(
			'devices',
			[
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => __( 'Desktop', 'elementor-avator' ),
					'tablet' => __( 'Tablet', 'elementor-avator' ),
					'mobile' => __( 'Mobile', 'elementor-avator' ),
				],
			]
		);

		$this->end_settings_group();

		$this->end_controls_section();
	}
}
