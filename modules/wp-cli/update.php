<?php
namespace ElementorAvator\Modules\WpCli;

use Elementor\Modules\WpCli\Update as UpdateBase;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Page Builder Avator cli tools.
 */
class Update extends UpdateBase {

	protected function get_update_db_manager_class() {
		return '\ElementorAvator\Core\Upgrade\Manager';
	}
}
