<?php
namespace ElementorAvator\Modules\Woocommerce\Widgets;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use ElementorAvator\Modules\Woocommerce\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Menu_Cart extends Base_Widget {

	public function get_name() {
		return 'woocommerce-menu-cart';
	}

	public function get_title() {
		return __( 'Menu Cart', 'elementor-avator' );
	}

	public function get_icon() {
		return 'eicon-cart';
	}

	public function get_categories() {
		return [ 'theme-elements', 'woocommerce-elements' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_menu_icon_content',
			[
				'label' => __( 'Menu Icon', 'elementor-avator' ),
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'cart-light' => __( 'Cart', 'elementor-avator' ) . ' ' . __( 'Light', 'elementor-avator' ),
					'cart-medium' => __( 'Cart', 'elementor-avator' ) . ' ' . __( 'Medium', 'elementor-avator' ),
					'cart-solid' => __( 'Cart', 'elementor-avator' ) . ' ' . __( 'Solid', 'elementor-avator' ),
					'basket-light' => __( 'Basket', 'elementor-avator' ) . ' ' . __( 'Light', 'elementor-avator' ),
					'basket-medium' => __( 'Basket', 'elementor-avator' ) . ' ' . __( 'Medium', 'elementor-avator' ),
					'basket-solid' => __( 'Basket', 'elementor-avator' ) . ' ' . __( 'Solid', 'elementor-avator' ),
					'bag-light' => __( 'Bag', 'elementor-avator' ) . ' ' . __( 'Light', 'elementor-avator' ),
					'bag-medium' => __( 'Bag', 'elementor-avator' ) . ' ' . __( 'Medium', 'elementor-avator' ),
					'bag-solid' => __( 'Bag', 'elementor-avator' ) . ' ' . __( 'Solid', 'elementor-avator' ),
				],
				'default' => 'cart-medium',
				'prefix_class' => 'toggle-icon--',
			]
		);

		$this->add_control(
			'items_indicator',
			[
				'label' => __( 'Items Indicator', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'none' => __( 'None', 'elementor-avator' ),
					'bubble' => __( 'Bubble', 'elementor-avator' ),
					'plain' => __( 'Plain', 'elementor-avator' ),
				],
				'prefix_class' => 'elementor-menu-cart--items-indicator-',
				'default' => 'bubble',
			]
		);

		$this->add_control(
			'hide_empty_indicator',
			[
				'label' => __( 'Hide Empty', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'elementor-avator' ),
				'label_off' => __( 'No', 'elementor-avator' ),
				'return_value' => 'hide',
				'prefix_class' => 'elementor-menu-cart--empty-indicator-',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'show_subtotal',
			[
				'label' => __( 'Subtotal', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-avator' ),
				'label_off' => __( 'Hide', 'elementor-avator' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'elementor-menu-cart--show-subtotal-',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label' => __( 'Alignment', 'elementor-avator' ),
				'type' => Controls_Manager::CHOOSE,
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
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style',
			[
				'label' => __( 'Menu Icon', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'toggle_button_colors' );

		$this->start_controls_tab( 'toggle_button_normal_colors', [ 'label' => __( 'Normal', 'elementor-avator' ) ] );

		$this->add_control(
			'toggle_button_text_color',
			[
				'label' => __( 'Text Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_icon_color',
			[
				'label' => __( 'Icon Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_background_color',
			[
				'label' => __( 'Background Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_border_color',
			[
				'label' => __( 'Border Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'toggle_button_hover_colors', [ 'label' => __( 'Hover', 'elementor-avator' ) ] );

		$this->add_control(
			'toggle_button_hover_text_color',
			[
				'label' => __( 'Text Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_icon_color',
			[
				'label' => __( 'Icon Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button:hover .elementor-button-icon' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_background_color',
			[
				'label' => __( 'Background Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_border_color',
			[
				'label' => __( 'Border Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'toggle_button_border_width',
			[
				'label' => __( 'Border Width', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_button_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],

			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'toggle_button_typography',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'heading_icon_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Icon', 'elementor-avator' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_size',
			[
				'label' => __( 'Size', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'toggle_icon_spacing',
			[
				'label' => __( 'Spacing', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size-units' => [ 'px', 'em' ],
				'selectors' => [
					'body:not(.rtl) {{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-text' => 'margin-right: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-text' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'toggle_button_padding',
			[
				'label' => __( 'Padding', 'elementor-avator' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'items_indicator_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Items Indicator', 'elementor-avator' ),
				'separator' => 'before',
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);
		$this->add_control(
			'items_indicator_text_color',
			[
				'label' => __( 'Text Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-icon[data-counter]:before' => 'color: {{VALUE}}',
				],
				'condition' => [
					'items_indicator!' => 'none',
				],
			]
		);

		$this->add_control(
			'items_indicator_background_color',
			[
				'label' => __( 'Background Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-icon[data-counter]:before' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->add_control(
			'items_indicator_distance',
			[
				'label' => __( 'Distance', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__toggle .elementor-button-icon[data-counter]:before' => 'right: -{{SIZE}}{{UNIT}}; top: -{{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'items_indicator' => 'bubble',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_cart_style',
			[
				'label' => __( 'Cart', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'show_divider',
			[
				'label' => __( 'Divider', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-avator' ),
				'label_off' => __( 'Hide', 'elementor-avator' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'elementor-menu-cart--show-divider-',
			]
		);

		$this->add_control(
			'show_remove_icon',
			[
				'label' => __( 'Remove Item Icon', 'elementor-avator' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'elementor-avator' ),
				'label_off' => __( 'Hide', 'elementor-avator' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'elementor-menu-cart--show-remove-button-',
			]
		);

		$this->add_control(
			'heading_subtotal_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Subtotal', 'elementor-avator' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subtotal_color',
			[
				'label' => __( 'Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__subtotal' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'subtotal_typography',
				'selector' => '{{WRAPPER}} .elementor-menu-cart__subtotal',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_product_tabs_style',
			[
				'label' => __( 'Products', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading_product_title_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Product Title', 'elementor-avator' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_title_color',
			[
				'label' => __( 'Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__product-name, {{WRAPPER}} .elementor-menu-cart__product-name a' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_title_typography',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-menu-cart__product-name, {{WRAPPER}} .elementor-menu-cart__product-name a',
			]
		);

		$this->add_control(
			'heading_product_price_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Product Price', 'elementor-avator' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'product_price_color',
			[
				'label' => __( 'Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__product-price' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_price_typography',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-menu-cart__product-price',
			]
		);

		$this->add_control(
			'heading_product_divider_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Divider', 'elementor-avator' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'divider_style',
			[
				'label' => __( 'Style', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'elementor-avator' ),
					'solid' => __( 'Solid', 'elementor-avator' ),
					'double' => __( 'Double', 'elementor-avator' ),
					'dotted' => __( 'Dotted', 'elementor-avator' ),
					'dashed' => __( 'Dashed', 'elementor-avator' ),
					'groove' => __( 'Groove', 'elementor-avator' ),
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__product, {{WRAPPER}} .elementor-menu-cart__subtotal' => 'border-bottom-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label' => __( 'Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__product, {{WRAPPER}} .elementor-menu-cart__subtotal' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'divider_width',
			[
				'label' => __( 'Weight', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__product, {{WRAPPER}} .elementor-menu-cart__products, {{WRAPPER}} .elementor-menu-cart__subtotal' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'divider_gap',
			[
				'label' => __( 'Spacing', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__product, {{WRAPPER}} .elementor-menu-cart__subtotal' => 'padding-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .elementor-menu-cart__product:not(:first-of-type), {{WRAPPER}} .elementor-menu-cart__footer-buttons, {{WRAPPER}} .elementor-menu-cart__subtotal' => 'padding-top: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_buttons',
			[
				'label' => __( 'Buttons', 'elementor-avator' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'buttons_layout',
			[
				'label' => __( 'Layout', 'elementor-avator' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'inline' => __( 'Inline', 'elementor-avator' ),
					'stacked' => __( 'Stacked', 'elementor-avator' ),
				],
				'default' => 'inline',
				'prefix_class' => 'elementor-menu-cart--buttons-',
			]
		);

		$this->add_control(
			'space_between_buttons',
			[
				'label' => __( 'Space Between', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__footer-buttons' => 'grid-column-gap: {{SIZE}}{{UNIT}}; grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'product_buttons_typography',
				'scheme' => Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .elementor-menu-cart__footer-buttons .elementor-button',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'button_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor-avator' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-menu-cart__footer-buttons .elementor-button' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'heading_view_cart_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'View Cart', 'elementor-avator' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'view_cart_button_text_color',
			[
				'label' => __( 'Text Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button--view-cart' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'view_cart_button_background_color',
			[
				'label' => __( 'Background Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button--view-cart' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'view_cart_border',
				'selector' => '{{WRAPPER}} .elementor-button--view-cart',
			]
		);

		$this->add_control(
			'heading_checkout_button_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => __( 'Checkout', 'elementor-avator' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'checkout_button_text_color',
			[
				'label' => __( 'Text Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button--checkout' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'checkout_button_background_color',
			[
				'label' => __( 'Background Color', 'elementor-avator' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-button--checkout' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'checkout_border',
				'selector' => '{{WRAPPER}} .elementor-button--checkout',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Check if user did not explicitly disabled the use of our mini-cart template and set the option accordingly.
	 * The option value is later used by Module::woocommerce_locate_template().
	 */
	private function maybe_use_mini_cart_template() {
		$option_value = get_option( 'elementor_' . Module::OPTION_NAME_USE_MINI_CART, '' );
		if ( empty( $option_value ) || 'initial' === $option_value ) {
			update_option( 'elementor_' . Module::OPTION_NAME_USE_MINI_CART, 'yes' );
		}
	}

	protected function render() {
		$this->maybe_use_mini_cart_template();
		Module::render_menu_cart();
	}

	public function render_plain_content() {}
}
