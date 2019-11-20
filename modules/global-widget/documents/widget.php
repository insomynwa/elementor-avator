<?php
namespace ElementorAvator\Modules\GlobalWidget\Documents;

use Elementor\Modules\Library\Documents\Library_Document;
use Elementor\User;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Widget extends Library_Document {

	public static function get_properties() {
		$propertyes = parent::get_properties();

		$propertyes['admin_tab_group'] = 'library';
		$propertyes['show_in_library'] = false;
		$propertyes['is_editable'] = false;

		return $propertyes;
	}

	public function get_name() {
		return 'widget';
	}

	public static function get_title() {
		return __( 'Global Widget', 'elementor-avator' );
	}

	public function is_editable_by_current_user() {
		return User::is_current_user_can_edit( $this->get_main_id() );
	}
}
