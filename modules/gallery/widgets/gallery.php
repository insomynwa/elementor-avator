<?php

namespace ElementorAvator\Modules\Gallery\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use ElementorAvator\Base\Base_Widget;
use ElementorAvator\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Gallery extends Base_Widget {

	/**
	 * Get element name.
	 *
	 * Retrieve the element name.
	 *
	 * @return string The name.
	 * @since 2.7.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'gallery';
	}

	public function get_title() {
		return __( 'Gallery', 'elementor-avator' );
	}

	public function get_script_depends() {
		return [ 'elementor-gallery' ];
	}

	public function get_style_depends() {
		return [ 'elementor-gallery' ];
	}

	public function get_icon() {
		return 'eicon-gallery-justified';
	}

	protected function _register_controls() {
		$this->start_controls_section( 'settings', [ 'label' => __( 'Settings', 'elementor-avator' ) ] );

		$this->add_control(
			'gallery_type',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Type', 'elementor-avator' ),
				'default' => 'single',
				'options' => [
					'single' => __( 'Single', 'elementor-avator' ),
					'multiple' => __( 'Multiple', 'elementor-avator' ),
				],
			]
		);

		$this->add_control(
			'gallery',
			[
				'type' => Controls_Manager::GALLERY,
				'condition' => [
					'gallery_type' => 'single',
				],
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'gallery_title',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __( 'Title', 'elementor-avator' ),
				'default' => __( 'New Gallery', 'elementor-avator' ),
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'multiple_gallery',
			[
				'type' => Controls_Manager::GALLERY,
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'galleries',
			[
				'type' => Controls_Manager::REPEATER,
				'label' => __( 'Galleries', 'elementor-avator' ),
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ gallery_title }}}',
				'default' => [
					[
						'gallery_title' => __( 'New Gallery', 'elementor-avator' ),
					],
				],
				'condition' => [
					'gallery_type' => 'multiple',
				],
				'separator' => 'after',
			]
		);

		$this->add_control(
			'gallery_layout',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Layout', 'elementor-avator' ),
				'default' => 'grid',
				'options' => [
					'grid' => __( 'Grid', 'elementor-avator' ),
					'justified' => __( 'Justified', 'elementor-avator' ),
					'masonry' => __( 'Masonry', 'elementor-avator' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'elementor-avator' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'tablet_default' => 2,
				'mobile_default' => 1,
				'min' => 1,
				'max' => 24,
				'condition' => [
					'gallery_layout!' => 'justified',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'ideal_row_height',
			[
				'label' => __( 'Row Height', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'default' => [
					'size' => 200,
				],
				'tablet_default' => [
					'size' => 150,
				],
				'mobile_default' => [
					'size' => 150,
				],
				'condition' => [
					'gallery_layout' => 'justified',
				],
				'required' => true,
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => __( 'Spacing', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 10,
				],
				'tablet_default' => [
					'size' => 10,
				],
				'mobile_default' => [
					'size' => 10,
				],
				'required' => true,
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'link_to',
			[
				'label' => __( 'Link', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'file',
				'options' => [
					'' => __( 'None', 'elementor-avator' ),
					'file' => __( 'Media File', 'elementor-avator' ),
					'custom' => __( 'Custom URL', 'elementor-avator' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'url',
			[
				'label' => __( 'URL', 'elementor-avator' ),
				'type' => Controls_Manager::URL,
				'show_external' => false,
				'condition' => [
					'link_to' => 'custom',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'aspect_ratio',
			[
				'type' => Controls_Manager::SELECT,
				'label' => __( 'Aspect Ratio', 'elementor-avator' ),
				'default' => '3:2',
				'options' => [
					'1:1' => '1:1',
					'3:2' => '3:2',
					'4:3' => '4:3',
					'9:16' => '9:16',
					'16:9' => '16:9',
					'21:9' => '21:9',
				],
				'condition' => [
					'gallery_layout' => 'grid',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail_image',
				'default' => 'medium',
			]
		);

		$this->end_controls_section(); // settings

		$this->start_controls_section(
			'section_filter_bar_content',
			[
				'label' => __( 'Filter Bar', 'elementor-avator' ),
				'condition' => [
					'gallery_type' => 'multiple',
				],
			]
		);

		$this->add_control(
			'show_all_galleries',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => __( '"All" Filter', 'elementor-avator' ),
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'show_all_galleries_label',
			[
				'type' => Controls_Manager::TEXT,
				'label' => __( '"All" Filter Label', 'elementor-avator' ),
				'default' => __( 'All', 'elementor-avator' ),
				'condition' => [
					'show_all_galleries' => 'yes',
				],
			]
		);

		$this->add_control(
			'pointer',
			[
				'label' => __( 'Pointer', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => [
					'none' => __( 'None', 'elementor-avator' ),
					'underline' => __( 'Underline', 'elementor-avator' ),
					'overline' => __( 'Overline', 'elementor-avator' ),
					'double-line' => __( 'Double Line', 'elementor-avator' ),
					'framed' => __( 'Framed', 'elementor-avator' ),
					'background' => __( 'Background', 'elementor-avator' ),
					'text' => __( 'Text', 'elementor-avator' ),
				],
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'animation_line',
			[
				'label' => __( 'Animation', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => 'Fade',
					'slide' => 'Slide',
					'grow' => 'Grow',
					'drop-in' => 'Drop In',
					'drop-out' => 'Drop Out',
					'none' => 'None',
				],
				'condition' => [
					'pointer' => [ 'underline', 'overline', 'double-line' ],
				],
			]
		);

		$this->add_control(
			'animation_framed',
			[
				'label' => __( 'Animation', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => 'Fade',
					'grow' => 'Grow',
					'shrink' => 'Shrink',
					'draw' => 'Draw',
					'corners' => 'Corners',
					'none' => 'None',
				],
				'condition' => [
					'pointer' => 'framed',
				],
			]
		);

		$this->add_control(
			'animation_background',
			[
				'label' => __( 'Animation', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'fade',
				'options' => [
					'fade' => 'Fade',
					'grow' => 'Grow',
					'shrink' => 'Shrink',
					'sweep-left' => 'Sweep Left',
					'sweep-right' => 'Sweep Right',
					'sweep-up' => 'Sweep Up',
					'sweep-down' => 'Sweep Down',
					'shutter-in-vertical' => 'Shutter In Vertical',
					'shutter-out-vertical' => 'Shutter Out Vertical',
					'shutter-in-horizontal' => 'Shutter In Horizontal',
					'shutter-out-horizontal' => 'Shutter Out Horizontal',
					'none' => 'None',
				],
				'condition' => [
					'pointer' => 'background',
				],
			]
		);

		$this->add_control(
			'animation_text',
			[
				'label' => __( 'Animation', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'grow',
				'options' => [
					'grow' => 'Grow',
					'shrink' => 'Shrink',
					'sink' => 'Sink',
					'float' => 'Float',
					'skew' => 'Skew',
					'rotate' => 'Rotate',
					'none' => 'None',
				],
				'condition' => [
					'pointer' => 'text',
				],
			]
		);

		$this->end_controls_section(); // settings

		$this->start_controls_section( 'overlay', [ 'label' => __( 'Overlay', 'elementor-avator' ) ] );

		$this->add_control(
			'overlay_background',
			[
				'label' => __( 'Background', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'overlay_title',
			[
				'label' => __( 'Title', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'elementor-avator' ),
					'title' => __( 'Title', 'elementor-avator' ),
					'caption' => __( 'Caption', 'elementor-avator' ),
					'alt' => __( 'Alt', 'elementor-avator' ),
					'description' => __( 'Description', 'elementor-avator' ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'overlay_description',
			[
				'label' => __( 'Description', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'None', 'elementor-avator' ),
					'title' => __( 'Title', 'elementor-avator' ),
					'caption' => __( 'Caption', 'elementor-avator' ),
					'alt' => __( 'Alt', 'elementor-avator' ),
					'description' => __( 'Description', 'elementor-avator' ),
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section(); // overlay

		$this->start_controls_section(
			'image_style',
			[
				'label' => __( 'Image', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'image_tabs' );

		$this->start_controls_tab(
			'image_normal',
			[
				'label' => __( 'Normal', 'elementor-avator' ),
			]
		);

		$this->add_control(
			'image_border_color',
			[
				'label' => __( 'Border Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_border_width',
			[
				'label' => __( 'Border Width', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters',
				'selector' => '{{WRAPPER}} .e-gallery-image',
			]
		);

		$this->end_controls_tab(); // overlay_background normal

		$this->start_controls_tab(
			'image_hover',
			[
				'label' => __( 'Hover', 'elementor-avator' ),
			]
		);

		$this->add_control(
			'image_border_color_hover',
			[
				'label' => __( 'Border Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'image_border_radius_hover',
			[
				'label' => __( 'Border Radius', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item:hover' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'image_css_filters_hover',
				'selector' => '{{WRAPPER}} .e-gallery-item:hover .e-gallery-image',
			]
		);

		$this->end_controls_tab(); // overlay_background normal

		$this->end_controls_tabs();// overlay_background tabs

		$this->add_control(
			'image_hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => 'None',
					'grow' => 'Zoom In',
					'shrink-contained' => 'Zoom Out',
					'move-contained-left' => 'Move Left',
					'move-contained-right' => 'Move Right',
					'move-contained-top' => 'Move Up',
					'move-contained-bottom' => 'Move Down',
				],
				'separator' => 'before',
				'default' => '',
				'frontend_available' => true,
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'image_animation_duration',
			[
				'label' => __( 'Animation Duration', 'elementor-avator' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 800,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .e-gallery-image' => 'transition-duration: {{SIZE}}ms',
				],
			]
		);

		$this->end_controls_section(); // overlay_background

		$this->start_controls_section(
			'overlay_style',
			[
				'label' => __( 'Overlay', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'overlay_background' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'overlay_background_tabs' );

		$this->start_controls_tab(
			'overlay_normal',
			[
				'label' => __( 'Normal', 'elementor-avator' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background',
				'types' => [ 'classic', 'gradient' ],
				'exclude' => [ 'image' ],
				'selector' => '{{WRAPPER}} .elementor-gallery-item__overlay',
				'fields_options' => [
					'background' => [
						'label' => __( 'Overlay', 'elementor-avator' ),
					],
				],
			]
		);

		$this->end_controls_tab(); // overlay_background normal

		$this->start_controls_tab(
			'overlay_hover',
			[
				'label' => __( 'Hover', 'elementor-avator' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'overlay_background_hover',
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .e-gallery-item:hover .elementor-gallery-item__overlay',
				'exclude' => [ 'image' ],
				'fields_options' => [
					'background' => [
						'default' => 'classic',
					],
					'color' => [
						'default' => 'rgba(0,0,0,0.5)',
					],
				],
			]
		);

		$this->end_controls_tab(); // overlay_background normal

		$this->end_controls_tabs();// overlay_background tabs

		$this->add_control(
			'image_blend_mode',
			[
				'label' => __( 'Blend Mode', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => __( 'Normal', 'elementor-avator' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'color-burn' => 'Color Burn',
					'hue' => 'Hue',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'exclusion' => 'Exclusion',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item__overlay' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'before',
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'background_overlay_hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'groups' => [
					[
						'label' => __( 'None', 'elementor-avator' ),
						'options' => [
							'' => __( 'None', 'elementor-avator' ),
						],
					],
					[
						'label' => __( 'Entrance', 'elementor-avator' ),
						'options' => [
							'enter-from-right' => 'Slide In Right',
							'enter-from-left' => 'Slide In Left',
							'enter-from-top' => 'Slide In Up',
							'enter-from-bottom' => 'Slide In Down',
							'enter-zoom-in' => 'Zoom In',
							'enter-zoom-out' => 'Zoom Out',
							'fade-in' => 'Fade In',
						],
					],
					[
						'label' => __( 'Exit', 'elementor-avator' ),
						'options' => [
							'exit-to-right' => 'Slide Out Right',
							'exit-to-left' => 'Slide Out Left',
							'exit-to-top' => 'Slide Out Up',
							'exit-to-bottom' => 'Slide Out Down',
							'exit-zoom-in' => 'Zoom In',
							'exit-zoom-out' => 'Zoom Out',
							'fade-out' => 'Fade Out',
						],
					],
				],
				'separator' => 'before',
				'default' => '',
				'frontend_available' => true,
				'render_type' => 'ui',
			]
		);

		$this->add_control(
			'background_overlay_animation_duration',
			[
				'label' => __( 'Animation Duration', 'elementor-avator' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 800,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item__overlay' => 'transition-duration: {{SIZE}}ms',
				],
			]
		);

		$this->end_controls_section(); // overlay_background

		$this->start_controls_section(
			'overlay_content_style',
			[
				'label' => __( 'Content', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
				//TODO: add conditions for this section
			]
		);

		$this->add_control(
			'content_alignment',
			[
				'label' => __( 'Alignment', 'elementor-avator' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-avator' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-avator' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-avator' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item__content' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'content_vertical_position',
			[
				'label' => __( 'Vertical Position', 'elementor-avator' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'elementor-avator' ),
						'icon' => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'elementor-avator' ),
						'icon' => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'elementor-avator' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'selectors_dictionary' => [
					'top' => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item__content' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => __( 'Padding', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item__content' => 'padding: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_title',
			[
				'label' => __( 'Title', 'elementor-avator' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'overlay_title!' => '',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item__title' => 'color: {{VALUE}}',
				],
				'condition' => [
					'overlay_title!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-gallery-item__title',
				'condition' => [
					'overlay_title!' => '',
				],
			]
		);

		$this->add_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item__title + .elementor-gallery-item__description' => 'margin-top: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'overlay_title!' => '',
				],
			]
		);

		$this->add_control(
			'heading_description',
			[
				'label' => __( 'Description', 'elementor-avator' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'overlay_description!' => '',
				],
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item__description' => 'color: {{VALUE}}',
				],
				'condition' => [
					'overlay_description!' => '',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_3,
				'selector' => '{{WRAPPER}} .elementor-gallery-item__description',
				'condition' => [
					'overlay_description!' => '',
				],
			]
		);

		$this->add_control(
			'content_hover_animation',
			[
				'label' => __( 'Hover Animation', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'groups' => [
					[
						'label' => __( 'None', 'elementor-avator' ),
						'options' => [
							'' => __( 'None', 'elementor-avator' ),
						],
					],
					[
						'label' => __( 'Entrance', 'elementor-avator' ),
						'options' => [
							'enter-from-right' => 'Slide In Right',
							'enter-from-left' => 'Slide In Left',
							'enter-from-top' => 'Slide In Up',
							'enter-from-bottom' => 'Slide In Down',
							'enter-zoom-in' => 'Zoom In',
							'enter-zoom-out' => 'Zoom Out',
							'fade-in' => 'Fade In',
						],
					],
					[
						'label' => __( 'Reaction', 'elementor-avator' ),
						'options' => [
							'grow' => 'Grow',
							'shrink' => 'Shrink',
							'move-right' => 'Move Right',
							'move-left' => 'Move Left',
							'move-up' => 'Move Up',
							'move-down' => 'Move Down',
						],
					],
					[
						'label' => __( 'Exit', 'elementor-avator' ),
						'options' => [
							'exit-to-right' => 'Slide Out Right',
							'exit-to-left' => 'Slide Out Left',
							'exit-to-top' => 'Slide Out Up',
							'exit-to-bottom' => 'Slide Out Down',
							'exit-zoom-in' => 'Zoom In',
							'exit-zoom-out' => 'Zoom Out',
							'fade-out' => 'Fade Out',
						],
					],
				],
				'default' => 'fade-in',
				'separator' => 'before',
				'render_type' => 'ui',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'content_animation_duration',
			[
				'label' => __( 'Animation Duration', 'elementor-avator' ) . ' (ms)',
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 800,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3000,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-item__content > div' => 'transition-duration: {{SIZE}}ms',
					'{{WRAPPER}} .elementor-gallery-item__content.elementor-gallery--sequenced-animation > div:nth-child(2)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) )',
					'{{WRAPPER}} .elementor-gallery-item__content.elementor-gallery--sequenced-animation > div:nth-child(3)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 2 )',
					'{{WRAPPER}} .elementor-gallery-item__content.elementor-gallery--sequenced-animation > div:nth-child(4)' => 'transition-delay: calc( ( {{SIZE}}ms / 3 ) * 3 )',
				],
				'condition' => [
					'content_hover_animation!' => '',
				],
			]
		);

		$this->add_control(
			'content_sequenced_animation',
			[
				'label' => __( 'Sequenced Animation', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'content_hover_animation!' => '',
				],
				'frontend_available' => true,
				'render_type' => 'ui',
			]
		);

		$this->end_controls_section(); // overlay_content

		$this->start_controls_section(
			'filter_bar_style',
			[
				'label' => __( 'Filter Bar', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'gallery_type' => 'multiple',
				],
			]
		);

		$this->add_control(
			'align_filter_bar_items',
			[
				'label' => __( 'Align', 'elementor-avator' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-avator' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor-avator' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-avator' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'prefix_class' => 'elementor-gallery--filter-align-',
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'right' => 'flex-end',
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery__titles-container' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->start_controls_tabs( 'filter_bar_colors' );

		$this->start_controls_tab( 'filter_bar_colors_normal',
			[
				'label' => __( 'Normal', 'elementor-avator' ),
			]
		);

		$this->add_control(
			'galleries_title_color_normal',
			[
				'label' => __( 'Text Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_1,
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'galleries_titles_typography',
				'selector' => '{{WRAPPER}} .elementor-gallery-title',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
			]
		);

		$this->end_controls_tab();// filter_bar_colors_normal

		$this->start_controls_tab( 'filter_bar_colors_hover',
			[
				'label' => __( 'Hover', 'elementor-avator' ),
			]
		);

		$this->add_control(
			'galleries_title_color_hover',
			[
				'label' => __( 'Text Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-item:hover,
					{{WRAPPER}} a.elementor-item.elementor-item-active,
					{{WRAPPER}} a.elementor-item.highlighted,
					{{WRAPPER}} a.elementor-item:focus' => 'color: {{VALUE}}',
				],
				'condition' => [
					'pointer!' => 'background',
				],
			]
		);

		$this->add_control(
			'galleries_title_color_hover_pointer_bg',
			[
				'label' => __( 'Text Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} a.elementor-item:hover,
					{{WRAPPER}} a.elementor-item.elementor-item-active,
					{{WRAPPER}} a.elementor-item.highlighted,
					{{WRAPPER}} a.elementor-item:focus' => 'color: {{VALUE}}',
				],
				'condition' => [
					'pointer' => 'background',
				],
			]
		);

		$this->add_control(
			'galleries_pointer_color_hover',
			[
				'label' => __( 'Pointer Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery__titles-container:not(.e--pointer-framed) .elementor-item:before,
					{{WRAPPER}} .elementor-gallery__titles-container:not(.e--pointer-framed) .elementor-item:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .e--pointer-framed .elementor-item:before,
					{{WRAPPER}} .e--pointer-framed .elementor-item:after' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'pointer!' => [ 'none', 'text' ],
				],
			]
		);

		$this->end_controls_tab();// filter_bar_colors_hover

		$this->start_controls_tab( 'filter_bar_colors_active',
			[
				'label' => __( 'Active', 'elementor-avator' ),
			]
		);

		$this->add_control(
			'galleries_title_color_active',
			[
				'label' => __( 'Text Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_2,
				],
				'selectors' => [
					'{{WRAPPER}} a.elementor-item.elementor-item-active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'galleries_pointer_color_active',
			[
				'label' => __( 'Pointer Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'scheme' => [
					'type' => Scheme_Color::get_type(),
					'value' => Scheme_Color::COLOR_4,
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery__titles-container:not(.e--pointer-framed) .elementor-item.elementor-item-active:before,
					{{WRAPPER}} .elementor-gallery__titles-container:not(.e--pointer-framed) .elementor-item.elementor-item-active:after' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .e--pointer-framed .elementor-item.elementor-item-active:before,
					{{WRAPPER}} .e--pointer-framed .elementor-item.elementor-item-active:after' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					'pointer!' => [ 'none', 'text' ],
				],

			]
		);

		$this->end_controls_tab();// filter_bar_colors_active

		$this->end_controls_tabs(); // filter_bar_colors

		$this->add_control(
			'pointer_width',
			[
				'label' => __( 'Pointer Width', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'devices' => [ self::RESPONSIVE_DESKTOP, self::RESPONSIVE_TABLET ],
				'range' => [
					'px' => [
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .e--pointer-framed .elementor-item:before' => 'border-width: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--pointer-framed.e--animation-draw .elementor-item:before' => 'border-width: 0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--pointer-framed.e--animation-draw .elementor-item:after' => 'border-width: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 0',
					'{{WRAPPER}} .e--pointer-framed.e--animation-corners .elementor-item:before' => 'border-width: {{SIZE}}{{UNIT}} 0 0 {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .e--pointer-framed.e--animation-corners .elementor-item:after' => 'border-width: 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0',
					'{{WRAPPER}} .e--pointer-underline .elementor-item:after,
					 {{WRAPPER}} .e--pointer-overline .elementor-item:before,
					 {{WRAPPER}} .e--pointer-double-line .elementor-item:before,
					 {{WRAPPER}} .e--pointer-double-line .elementor-item:after' => 'height: {{SIZE}}{{UNIT}}',
				],
				'separator' => 'before',
				'condition' => [
					'pointer' => [ 'underline', 'overline', 'double-line', 'framed' ],
				],
			]
		);

		$this->add_control(
			'galleries_titles_space_between',
			[
				'label' => __( 'Space Between', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery-title' => '--space-between: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'galleries_titles_gap',
			[
				'label' => __( 'Gap', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-gallery__titles-container' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section(); // filter_bar_style
	}

	/**
	 *
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$is_multiple = 'multiple' === $settings['gallery_type'] && ! empty( $settings['galleries'] );

		$is_single = 'single' === $settings['gallery_type'] && ! empty( $settings['gallery'] );

		$has_description = ! empty( $settings['overlay_description'] );

		$has_title = ! empty( $settings['overlay_title'] );

		$has_animation = ! empty( $settings['image_hover_animation'] ) || ! empty( $settings['content_hover_animation'] ) || ! empty( $settings['background_overlay_hover_animation'] );

		$gallery_item_tag = ! empty( $settings['link_to'] ) ? 'a' : 'div';

		$galleries = [];

		if ( $is_multiple ) {
			$this->add_render_attribute( 'titles-container', 'class', 'elementor-gallery__titles-container' );

			if ( $settings['pointer'] ) {
				$this->add_render_attribute( 'titles-container', 'class', 'e--pointer-' . $settings['pointer'] );

				foreach ( $settings as $key => $value ) {
					if ( 0 === strpos( $key, 'animation' ) && $value ) {
						$this->add_render_attribute( 'titles-container', 'class', 'e--animation-' . $value );
						break;
					}
				}
			} ?>
			<div <?php echo $this->get_render_attribute_string( 'titles-container' ); ?>>
				<?php if ( $settings['show_all_galleries'] ) { ?>
					<a data-gallery-index="all" class="elementor-item elementor-gallery-title"><?php echo $settings['show_all_galleries_label']; ?></a>
				<?php } ?>

				<?php foreach ( $settings['galleries'] as $index => $gallery ) :
					if ( ! $gallery['multiple_gallery'] ) {
						continue;
					}

					$galleries[ $index ] = $gallery['multiple_gallery'];
					?>
					<a data-gallery-index="<?php echo $index; ?>" class="elementor-item elementor-gallery-title"><?php echo $gallery['gallery_title']; ?></a>
					<?php
				endforeach; ?>
			</div>
			<?php
		} elseif ( $is_single ) {
			$galleries[0] = $settings['gallery'];
		} elseif ( Plugin::elementor()->editor->is_edit_mode() ) { ?>
			<i class="elementor-widget-empty-icon eicon-gallery-justified"></i>
		<?php }

		$this->add_render_attribute( 'gallery_container', 'class', 'elementor-gallery__container' );

		if ( $has_title || $has_description ) {
			$this->add_render_attribute( 'gallery_item_content', 'class', 'elementor-gallery-item__content' );

			if ( $has_title ) {
				$this->add_render_attribute( 'gallery_item_title', 'class', 'elementor-gallery-item__title' );
			}

			if ( $has_description ) {
				$this->add_render_attribute( 'gallery_item_description', 'class', 'elementor-gallery-item__description' );
			}
		}

		$this->add_render_attribute( 'gallery_item_background_overlay', [ 'class' => 'elementor-gallery-item__overlay' ] );

		$gallery_items = [];
		foreach ( $galleries as $gallery_index => $gallery ) {
			foreach ( $gallery as $index => $item ) {
				if ( in_array( $item['id'], array_keys( $gallery_items ), true ) ) {
					$gallery_items[ $item['id'] ][] = $gallery_index;
				} else {
					$gallery_items[ $item['id'] ] = [ $gallery_index ];
				}
			}
		}

		if ( ! empty( $galleries ) ) { ?>
		<div <?php echo $this->get_render_attribute_string( 'gallery_container' ); ?>>
			<?php
			foreach ( $gallery_items as $id => $tags ) :
				$unique_index = $id; //$gallery_index . '_' . $index;
				$thumbnail_size = $settings['thumbnail_image_size'];
				$attachment = get_post( $id );
				$image_data = [
					'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
					'permalink' => get_permalink( $attachment->ID ),
					'media' => wp_get_attachment_image_src( $id, 'full' )['0'],
					'src' => wp_get_attachment_image_src( $id, $thumbnail_size )['0'],
				];

				$this->add_render_attribute( 'gallery_item_' . $unique_index, [
					'class' => [
						'e-gallery-item',
						'elementor-gallery-item',
					],
				] );

				if ( $has_animation ) {
					$this->add_render_attribute( 'gallery_item_' . $unique_index, [ 'class' => 'elementor-animated-content' ] );
				}

				if ( $is_multiple ) {
					$this->add_render_attribute( 'gallery_item_' . $unique_index, [ 'data-e-gallery-tags' => implode( ',', $tags ) ] );
				}

				if ( 'a' === $gallery_item_tag ) {
					$href = '#';
					if ( 'file' === $settings['link_to'] ) {
						$href = $image_data['media'];
						$this->add_render_attribute( 'gallery_item_' . $unique_index, [ 'data-elementor-lightbox-slideshow' => 'all' ] );
					}
					$this->add_render_attribute( 'gallery_item_' . $unique_index, [ 'href' => $href ] );
				}

				$this->add_render_attribute( 'gallery_item_image_' . $unique_index,
					[
						'class' => [
							'e-gallery-image',
							'elementor-gallery-item__image',
						],
						'data-thumbnail' => $image_data['src'],
						'alt' => $image_data['alt'],
					]
				);?>

				<<?php echo $gallery_item_tag; ?> <?php echo $this->get_render_attribute_string( 'gallery_item_' . $unique_index ); ?>>
					<div <?php echo $this->get_render_attribute_string( 'gallery_item_image_' . $unique_index ); ?> ></div>
					<?php if ( ! empty( $settings['overlay_background'] ) ) : ?>
					<div <?php echo $this->get_render_attribute_string( 'gallery_item_background_overlay' ); ?>></div>
					<?php endif; ?>
					<?php if ( $has_title || $has_description ) :
						$image_data = [
							'caption' => $attachment->post_excerpt,
							'description' => $attachment->post_content,
							'title' => $attachment->post_title,
						];
						?>
					<div <?php echo $this->get_render_attribute_string( 'gallery_item_content' ); ?>>
						<?php if ( $has_title ) :
							$title = $image_data[ $settings['overlay_title'] ];
							if ( ! empty( $title ) ) : ?>
							<div <?php echo $this->get_render_attribute_string( 'gallery_item_title' ); ?>><?php echo $title; ?></div>
							<?php endif;
						endif;
						if ( $has_description ) :
							$description = $image_data[ $settings['overlay_description'] ];
							if ( ! empty( $description ) ) :?>
							<div <?php echo $this->get_render_attribute_string( 'gallery_item_description' ); ?>><?php echo $description; ?></div>
							<?php endif;
						endif; ?>
					</div>
					<?php endif; ?>
				</<?php echo $gallery_item_tag; ?>>
			<?php endforeach;
			//endforeach; ?>
		</div>
	<?php }
	}
}
