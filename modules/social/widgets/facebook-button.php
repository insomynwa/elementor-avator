<?php
namespace ElementorAvator\Modules\Social\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use ElementorAvator\Modules\Social\Classes\Facebook_SDK_Manager;
use ElementorAvator\Modules\Social\Module;
use ElementorAvator\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Facebook_Button extends Widget_Base {

	public function get_name() {
		return 'facebook-button';
	}

	public function get_title() {
		return __( 'Facebook Button', 'elementor-avator' );
	}

	public function get_icon() {
		return 'eicon-facebook-like-box';
	}

	public function get_categories() {
		return [ 'avator-elements' ];
	}

	public function get_keywords() {
		return [ 'facebook', 'social', 'embed', 'button', 'like', 'share', 'recommend', 'follow' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Button', 'elementor-avator' ),
			]
		);

		Facebook_SDK_Manager::add_app_id_control( $this );

		$this->add_control(
			'type',
			[
				'label' => __( 'Type', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'like',
				'options' => [
					'like' => __( 'Like', 'elementor-avator' ),
					'recommend' => __( 'Recommend', 'elementor-avator' ),
					/* TODO: remove on 2.3 */
					'follow' => __( 'Follow', 'elementor-avator' ) . ' (' . __( 'Deprecated', 'elementor-avator' ) . ')',
				],
			]
		);

		/* TODO: remove on 2.3 */
		$this->add_control(
			'follow_description',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => __( 'The Follow button has been deprecated by Facebook and will no longer work.', 'elementor-avator' ),
				'content_classes' => 'elementor-descriptor',
				'condition' => [
					'type' => 'follow',
				],
			]
		);

		$this->add_control(
			'layout',
			[
				'label' => __( 'Layout', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard' => __( 'Standard', 'elementor-avator' ),
					'button' => __( 'Button', 'elementor-avator' ),
					'button_count' => __( 'Button Count', 'elementor-avator' ),
					'box_count' => __( 'Box Count', 'elementor-avator' ),
				],
			]
		);

		$this->add_control(
			'size',
			[
				'label' => __( 'Size', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'small',
				'options' => [
					'small' => __( 'Small', 'elementor-avator' ),
					'large' => __( 'Large', 'elementor-avator' ),
				],
			]
		);

		$this->add_control(
			'color_scheme',
			[
				'label' => __( 'Color Scheme', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'light',
				'options' => [
					'light' => __( 'Light', 'elementor-avator' ),
					'dark' => __( 'Dark', 'elementor-avator' ),
				],
			]
		);

		$this->add_control(
			'show_share',
			[
				'label' => __( 'Share Button', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'condition' => [
					'type!' => 'follow',
				],
			]
		);

		$this->add_control(
			'show_faces',
			[
				'label' => __( 'Faces', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
			]
		);

		$this->add_control(
			'url_type',
			[
				'label' => __( 'Target URL', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::URL_TYPE_CURRENT_PAGE => __( 'Current Page', 'elementor-avator' ),
					Module::URL_TYPE_CUSTOM => __( 'Custom', 'elementor-avator' ),
				],
				'default' => Module::URL_TYPE_CURRENT_PAGE,
				'separator' => 'before',
				'condition' => [
					'type' => [ 'like', 'recommend' ],
				],
			]
		);

		$this->add_control(
			'url_format',
			[
				'label' => __( 'URL Format', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					Module::URL_FORMAT_PLAIN => __( 'Plain Permalink', 'elementor-avator' ),
					Module::URL_FORMAT_PRETTY => __( 'Pretty Permalink', 'elementor-avator' ),
				],
				'default' => Module::URL_FORMAT_PLAIN,
				'condition' => [
					'url_type' => Module::URL_TYPE_CURRENT_PAGE,
				],
			]
		);

		$this->add_control(
			'url',
			[
				'label' => __( 'Link', 'elementor-avator' ),
				'placeholder' => __( 'https://your-link.com', 'elementor-avator' ),
				'label_block' => true,
				'condition' => [
					'type' => [ 'like', 'recommend' ],
					'url_type' => Module::URL_TYPE_CUSTOM,
				],
			]
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings();

		// Validate URL
		switch ( $settings['type'] ) {
			/* TODO: remove on 2.3 */
			case 'follow':
				if ( Plugin::elementor()->editor->is_edit_mode() ) {
					echo __( 'The Follow button has been deprecated by Facebook and will no longer work.', 'elementor-avator' );

				}
				return;
			case 'like':
			case 'recommend':
				if ( Module::URL_TYPE_CUSTOM === $settings['url_type'] && ! filter_var( $settings['url'], FILTER_VALIDATE_URL ) ) {
					if ( Plugin::elementor()->editor->is_edit_mode() ) {
						echo $this->get_title() . ': ' . esc_html__( 'Please enter a valid URL', 'elementor-avator' ); // XSS ok.
					}

					return;
				}
				break;
		}

		$attributes = [
			'data-layout' => $settings['layout'],
			'data-colorscheme' => $settings['color_scheme'],
			'data-size' => $settings['size'],
			'data-show-faces' => $settings['show_faces'] ? 'true' : 'false',
		];

		switch ( $settings['type'] ) {
			case 'like':
			case 'recommend':
				if ( Module::URL_TYPE_CURRENT_PAGE === $settings['url_type'] ) {
					$permalink = Facebook_SDK_Manager::get_permalink( $settings );
				} else {
					$permalink = esc_url( $settings['url'] );
				}

				$attributes['class'] = 'elementor-facebook-widget fb-like';
				$attributes['data-href'] = $permalink;
				$attributes['data-share'] = $settings['show_share'] ? 'true' : 'false';
				$attributes['data-action'] = $settings['type'];
				break;
		}

		$this->add_render_attribute( 'embed_div', $attributes );

		echo '<div ' . $this->get_render_attribute_string( 'embed_div' ) . '></div>'; // XSS ok.
	}

	public function render_plain_content() {}
}
