<?php
namespace ElementorAvator\Modules\Popup;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use ElementorAvator\Modules\Popup\DisplaySettings\Base;
use ElementorAvator\Modules\Popup\DisplaySettings\Timing;
use ElementorAvator\Modules\Popup\DisplaySettings\Triggers;
use ElementorAvator\Modules\ThemeBuilder\Documents\Theme_Section_Document;
use ElementorAvator\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Document extends Theme_Section_Document {

	const DISPLAY_SETTINGS_META_KEY = '_elementor_popup_display_settings';

	/**
	 * @var Base[]
	 */
	private $display_settings;

	public static function get_properties() {
		$propertyes = parent::get_properties();

		$propertyes['admin_tab_group'] = 'popup';
		$propertyes['location'] = 'popup';

		return $propertyes;
	}

	public static function get_title() {
		return __( 'Popup', 'elementor-avator' );
	}

	public function get_display_settings() {
		if ( ! $this->display_settings ) {
			$settings = $this->get_display_settings_data();

			if ( ! $settings ) {
				$settings = [
					'triggers' => [],
					'timing' => [],
				];
			}

			$id = $this->get_main_id();

			$this->display_settings = [
				'triggers' => new Triggers( [
					'id' => $id,
					'settings' => $settings['triggers'],
				] ),
				'timing' => new Timing( [
					'id' => $id,
					'settings' => $settings['timing'],
				] ),
			];
		}

		return $this->display_settings;
	}

	public function _get_initial_config() {
		$config = parent::_get_initial_config();

		$display_settings = $this->get_display_settings();

		$config['displaySettings'] = [
			'triggers' => [
				'controls' => $display_settings['triggers']->get_controls(),
				'settings' => $display_settings['triggers']->get_settings(),
			],
			'timing' => [
				'controls' => $display_settings['timing']->get_controls(),
				'settings' => $display_settings['timing']->get_settings(),
			],
		];

		$config['container'] = '.elementor-popup-modal .dialog-widget-content';

		return $config;
	}

	public function get_name() {
		return 'popup';
	}

	public function get_css_wrapper_selector() {
		return '#elementor-popup-modal-' . $this->get_main_id();
	}

	public function get_display_settings_data() {
		return $this->get_main_meta( self::DISPLAY_SETTINGS_META_KEY );
	}

	public function save_display_settings_data( $display_settings_data ) {
		$this->update_main_meta( self::DISPLAY_SETTINGS_META_KEY, $display_settings_data );
	}

	public function get_frontend_settings() {
		$settings = parent::get_frontend_settings();

		$display_settings = $this->get_display_settings();

		$settings['triggers'] = $display_settings['triggers']->get_frontend_settings();
		$settings['timing'] = $display_settings['timing']->get_frontend_settings();

		return $settings;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'popup_layout',
			[
				'label' => __( 'Layout', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_SETTINGS,
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' => __( 'Width', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vw' ],
				'default' => [
					'size' => 640,
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'height_type',
			[
				'label' => __( 'Height', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'auto',
				'options' => [
					'auto' => __( 'Fit To Content', 'elementor-avator' ),
					'fit_to_screen' => __( 'Fit To Screen', 'elementor-avator' ),
					'custom' => __( 'Custom', 'elementor-avator' ),
				],
				'selectors_dictionary' => [
					'fit_to_screen' => '100vh',
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'height: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' => __( 'Custom Height', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
						'max' => 1000,
					],
					'vh' => [
						'min' => 10,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'vh' ],
				'condition' => [
					'height_type' => 'custom',
				],
				'default' => [
					'size' => 380,
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_position',
			[
				'label' => __( 'Content Position', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'top',
				'options' => [
					'top' => __( 'Top', 'elementor-avator' ),
					'center' => __( 'Center', 'elementor-avator' ),
					'bottom' => __( 'Bottom', 'elementor-avator' ),
				],
				'condition' => [
					'height_type!' => 'auto',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'position_heading',
			[
				'label' => __( 'Position', 'elementor-avator' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'horizontal_position',
			[
				'label' => __( 'Horizontal', 'elementor-avator' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle' => false,
				'default' => 'center',
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-avator' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-avator' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-avator' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'justify-content: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end',
				],
			]
		);

		$this->add_responsive_control(
			'vertical_position',
			[
				'label' => __( 'Vertical', 'elementor-avator' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'toggle' => false,
				'default' => 'center',
				'options' => [
					'top' => [
						'title' => __( 'Top', 'elementor-avator' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-avator' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'elementor-avator' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'align-items: {{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'bottom' => 'flex-end',
				],
			]
		);

		$this->add_control(
			'overlay',
			[
				'label' => __( 'Overlay', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'elementor-avator' ),
				'label_on' => __( 'Show', 'elementor-avator' ),
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}}' => 'pointer-events: all',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'close_button',
			[
				'label' => __( 'Close Button', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'elementor-avator' ),
				'label_on' => __( 'Show', 'elementor-avator' ),
				'default' => 'yes',
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button' => 'display: block',
				],
			]
		);

		$this->add_responsive_control(
			'entrance_animation',
			[
				'label' => __( 'Entrance Animation', 'elementor-avator' ),
				'type' => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'exit_animation',
			[
				'label' => __( 'Exit Animation', 'elementor-avator' ),
				'type' => Controls_Manager::EXIT_ANIMATION,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'entrance_animation_duration',
			[
				'label' => __( 'Animation Duration', 'elementor-avator' ) . ' (sec)',
				'type' => Controls_Manager::SLIDER,
				'frontend_available' => true,
				'default' => [
					'size' => 1.2,
				],
				'range' => [
					'px' => [
						'min' => 0.1,
						'max' => 5,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-widget-content' => 'animation-duration: {{SIZE}}s',
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'entrance_animation',
							'operator' => '!==',
							'value' => '',
						],
						[
							'name' => 'exit_animation',
							'operator' => '!==',
							'value' => '',
						],
					],
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		parent::_register_controls();

		$this->start_controls_section(
			'section_page_style',
			[
				'label' => __( 'Popup', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'  => 'background',
				'selector' => '{{WRAPPER}} .dialog-widget-content',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'  => 'border',
				'selector' => '{{WRAPPER}} .dialog-widget-content',
			]
		);

		$this->add_responsive_control(
			'border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-avator' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .dialog-widget-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'box_shadow',
				'selector' => '{{WRAPPER}} .dialog-widget-content',
				'fields_options' => [
					'box_shadow_type' => [
						'default' => 'yes',
					],
					'box_shadow' => [
						'default' => [
							'horizontal' => 2,
							'vertical' => 8,
							'blur' => 23,
							'spread' => 3,
							'color' => 'rgba(0,0,0,0.2)',
						],
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay',
			[
				'label' => __( 'Overlay', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'overlay' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}}',
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => 'rgba(0,0,0,.8)',
					],
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_close_button',
			[
				'label' => __( 'Close Button', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'close_button!' => '',
				],
			]
		);

		$this->add_control(
			'close_button_position',
			[
				'label' => __( 'Position', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Inside', 'elementor-avator' ),
					'outside' => __( 'Outside', 'elementor-avator' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'close_button_vertical',
			[
				'label' => __( 'Vertical Position', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'max' => 100,
						'min' => 0,
						'step' => 0.1,
					],
					'px' => [
						'max' => 500,
						'min' => -500,
					],
				],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button' => 'top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'close_button_horizontal',
			[
				'label' => __( 'Horizontal Position', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'max' => 100,
						'min' => 0,
						'step' => 0.1,
					],
					'px' => [
						'max' => 500,
						'min' => -500,
					],
				],
				'default' => [
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .dialog-close-button' => 'right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .dialog-close-button' => 'left: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'after',
			]
		);

		$this->start_controls_tabs( 'close_button_style_tabs' );

		$this->start_controls_tab(
			'tab_x_button_normal',
			[
				'label' => __( 'Normal', 'elementor-avator' ),
			]
		);

		$this->add_control(
			'close_button_color',
			[
				'label' => __( 'Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_button_background_color',
			[
				'label' => __( 'Background Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_x_button_hover',
			[
				'label' => __( 'Hover', 'elementor-avator' ),
			]
		);

		$this->add_control(
			'close_button_hover_color',
			[
				'label' => __( 'Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button:hover i' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_button_hover_background_color',
			[
				'label' => __( 'Background Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Size', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .dialog-close-button' => 'font-size: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_advanced',
			[
				'label' => __( 'Advanced', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'close_button_delay',
			[
				'label' => __( 'Show Close Button After', 'elementor-avator' ) . ' (sec)',
				'type' => Controls_Manager::NUMBER,
				'min' => 0.1,
				'max' => 60,
				'step' => 0.1,
				'condition' => [
					'close_button' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'close_automatically',
			[
				'label' => __( 'Automatically Close After', 'elementor-avator' ) . ' (sec)',
				'type' => Controls_Manager::NUMBER,
				'min' => 0.1,
				'max' => 60,
				'step' => 0.1,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'prevent_close_on_background_click',
			[
				'label' => __( 'Prevent Closing on Overlay', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'prevent_close_on_esc_key',
			[
				'label' => __( 'Prevent Closing on ESC key', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'prevent_scroll',
			[
				'label' => __( 'Disable Page Scrolling', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'avoid_multiple_popups',
			[
				'label' => __( 'Avoid Multiple Popups', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'description' => __( 'If the user has seen another popup on the page hide this popup', 'elementor-avator' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'open_selector',
			[
				'label' => __( 'Open By Selector', 'elementor-avator' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( '#id, .class', 'elementor-avator' ),
				'description' => __( 'In order to open a popup on selector click, please set your Popup Conditions', 'elementor-avator' ),
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'margin',
			[
				'label' => __( 'Margin', 'elementor-avator' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .dialog-widget-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => __( 'Padding', 'elementor-avator' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .dialog-message' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'classes',
			[
				'label' => __( 'CSS Classes', 'elementor-avator' ),
				'type' => Controls_Manager::TEXT,
				'title' => __( 'Add your custom class WITHOUT the dot. e.g: my-class', 'elementor-avator' ),
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		Plugin::elementor()->controls_manager->add_custom_css_controls( $this );
	}

	protected function get_remote_library_config() {
		$config = parent::get_remote_library_config();

		$config['type'] = 'popup';
		$config['autoImportSettings'] = true;

		return $config;
	}
}
