<?php
namespace ElementorAvator\Core\Upgrade;

use Elementor\Core\Upgrade\Manager as Upgrades_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Manager extends Upgrades_Manager {

	public function get_action() {
		return 'elementor_avator_updater';
	}

	public function get_plugin_name() {
		return 'elementor-avator';
	}

	public function get_plugin_label() {
		return __( 'Elementor Avator', 'elementor-avator' );
	}

	public function get_new_version() {
		return ELEMENTOR_AVATOR_VERSION;
	}

	public function get_version_option_name() {
		return 'elementor_avator_version';
	}

	public function get_upgrades_class() {
		return 'ElementorAvator\Core\Upgrade\Upgrades';
	}
}
