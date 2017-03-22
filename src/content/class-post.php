<?php

namespace Isotop\Cargo\Content;

class Post extends Abstract_Content {

	/**
	 * Post constructor.
	 *
	 * @param mixed $post
	 */
	public function __construct( $post ) {
		// Bail if empty.
		if ( empty( $post ) ) {
			return;
		}

		// Bail if `nav_menu_item` post type.
		if ( get_post_type( $post ) === 'nav_menu_item' ) {
			return;
		}

		$post = get_post( $post );

		// Bail if empty.
		if ( empty( $post ) ) {
			return;
		}

		// Delete all oEmbed caches.
		if ( class_exists( '\WP_Embed' ) ) {
			global $wp_embed;

			if ( $wp_embed instanceof \WP_Embed ) {
				$wp_embed->cache_oembed( $post->ID );
			}
		}

		// Apply the content filter before creating post object.
		$post->post_content = apply_filters( 'the_content', $post->post_content );

		// Create post object.
		$this->create( 'post', $post );

		// Add meta data.
		$this->add( 'meta', $this->prepare_meta( $post->ID, get_post_meta( $post->ID ) ) );

		// Add terms.
		$this->add( 'terms', $this->get_terms( $post ) );

		// Add extra data.
		$this->add( 'extra', [
			'permalink' => get_permalink( $post ),
			'site_id'   => get_current_blog_id()
		] );
	}

	/**
	 * Get all terms for the given post.
	 *
	 * @param  \WP_Post $post
	 *
	 * @return array
	 */
	protected function get_terms( $post ) {
		$result     = [];
		$taxonomies = get_object_taxonomies( $post );

		foreach ( $taxonomies as $taxonomy ) {
			$terms = wp_get_post_terms( $post->ID, $taxonomy, ['fields' => 'ids'] );

			if ( is_wp_error( $terms ) ) {
				continue;
			}

			foreach ( $terms as $term_id ) {
				$result[] = [
					'id'       => $term_id,
					'taxonomy' => $taxonomy
				];
			}
		}

		return $result;
	}
}
