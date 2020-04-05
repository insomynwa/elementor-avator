<?php
namespace ElementorAvator\Modules\DynamicTags\Tags;

use Elementor\Controls_Manager;
use ElementorAvator\Modules\DynamicTags\Tags\Base\Tag;
use ElementorAvator\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class User_Info extends Tag {

	public function get_name() {
		return 'user-info';
	}

	public function get_title() {
		return __( 'User Info', 'elementor-avator' );
	}

	public function get_group() {
		return Module::SITE_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		$type = $this->get_settings( 'type' );
		$user = wp_get_current_user();
		if ( empty( $type ) || 0 === $user->ID ) {
			return;
		}

		$value = '';
		switch ( $type ) {
			case 'login':
			case 'email':
			case 'url':
			case 'nicename':
				$field = 'user_' . $type;
				$value = isset( $user->$field ) ? $user->$field : '';
				break;
			case 'id':
				$value = $user->ID;
				break;
			case 'description':
			case 'first_name':
			case 'last_name':
			case 'display_name':
				$value = isset( $user->$type ) ? $user->$type : '';
				break;
			case 'meta':
				$key = $this->get_settings( 'meta_key' );
				if ( ! empty( $key ) ) {
					$value = get_user_meta( $user->ID, $key, true );
				}
				break;
		}

		echo wp_kses_post( $value );
	}

	public function get_panel_template_setting_key() {
		return 'type';
	}

	protected function _register_controls() {
		$this->add_control(
			'type',
			[
				'label' => __( 'Field', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Choose', 'elementor-avator' ),
					'id' => __( 'ID', 'elementor-avator' ),
					'display_name' => __( 'Display Name', 'elementor-avator' ),
					'login' => __( 'Username', 'elementor-avator' ),
					'first_name' => __( 'First Name', 'elementor-avator' ),
					'last_name' => __( 'Last Name', 'elementor-avator' ),
					'description' => __( 'Bio', 'elementor-avator' ),
					'email' => __( 'Email', 'elementor-avator' ),
					'url' => __( 'Website', 'elementor-avator' ),
					'meta' => __( 'User Meta', 'elementor-avator' ),
				],
			]
		);

		$this->add_control(
			'meta_key',
			[
				'label' => __( 'Meta Key', 'elementor-avator' ),
				'condition' => [
					'type' => 'meta',
				],
			]
		);
	}
}
