<?php
namespace ElementorAvator\Modules\DynamicTags\Tags;

use ElementorAvator\Modules\DynamicTags\Tags\Base\Tag;
use ElementorAvator\Modules\DynamicTags\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Post_Excerpt extends Tag {
	public function get_name() {
		return 'post-excerpt';
	}

	public function get_title() {
		return __( 'Post Excerpt', 'elementor-avator' );
	}

	public function get_group() {
		return Module::POST_GROUP;
	}

	public function get_categories() {
		return [ Module::TEXT_CATEGORY ];
	}

	public function render() {
		// Allow only a real `post_excerpt` and not the trimmed `post_content` from the `get_the_excerpt` filter
		$post = get_post();

		if ( ! $post || empty( $post->post_excerpt ) ) {
			return;
		}

		echo wp_kses_post( $post->post_excerpt );
	}
}
