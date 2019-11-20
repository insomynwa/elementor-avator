<?php
namespace ElementorAvator\Modules\DynamicTags\Tags;

use Elementor\Core\DynamicTags\Tag;
use ElementorAvator\Modules\DynamicTags\Module;
use Elementor\Controls_Manager;
use Elementor\Embed;
use ElementorAvator\Modules\LinkActions\Module as LinkActionsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class Lightbox extends Tag {

	public function get_name() {
		return 'lightbox';
	}

	public function get_title() {
		return __( 'Lightbox', 'elementor-avator' );
	}

	public function get_group() {
		return Module::ACTION_GROUP;
	}

	public function get_categories() {
		return [ Module::URL_CATEGORY ];
	}

	// Keep Empty to avoid default advanced section
	protected function register_advanced_section() {}

	public function _register_controls() {
		$this->add_control(
			'type',
			[
				'label' => __( 'Type', 'elementor-avator' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'video' => [
						'title' => __( 'Video', 'elementor-avator' ),
						'icon' => 'eicon-video-camera',
					],
					'image' => [
						'title' => __( 'Image', 'elementor-avator' ),
						'icon' => 'eicon-image-bold',
					],
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => __( 'Image', 'elementor-avator' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'type' => 'image',
				],
			]
		);

		$this->add_control(
			'video_url',
			[
				'label' => __( 'Video URL', 'elementor-avator' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'condition' => [
					'type' => 'video',
				],
			]
		);
	}

	private function get_image_settings( $settings ) {
		return [
			'url' => $settings['image']['url'],
			'type' => 'image',
		];
	}

	private function get_video_settings( $settings ) {
		$video_propertyes = Embed::get_video_propertyes( $settings['video_url'] );
		$video_url = null;
		if ( ! $video_propertyes ) {
			$video_type = 'hosted';
			$video_url = $settings['video_url'];
		} else {
			$video_type = $video_propertyes['provider'];
			$video_url = Embed::get_embed_url( $settings['video_url'] );
		}

		if ( null === $video_url ) {
			return '';
		}

		return [
			'type' => 'video',
			'videoType' => $video_type,
			'url' => $video_url,
		];
	}

	public function render() {
		$settings = $this->get_settings();

		$value = [];

		if ( ! $settings['type'] ) {
			return;
		}

		if ( 'image' === $settings['type'] && $settings['image'] ) {
			$value = $this->get_image_settings( $settings );
		} elseif ( 'video' === $settings['type'] && $settings['video_url'] ) {
			$value = $this->get_video_settings( $settings );
		}

		if ( ! $value ) {
			return;
		}

		echo LinkActionsModule::create_action_url( 'lightbox', $value );
	}
}
