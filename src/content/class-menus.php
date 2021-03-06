<?php

namespace Isotop\Cargo\Content;

class Menus extends Abstract_Content {

	/**
	 * Menus constructor.
	 */
	public function __construct() {
		$menus  = wp_get_nav_menus();
		$result = [];

		// Get registered nav manues and nav menu locations.
		$locations = get_registered_nav_menus();
		$menu_locations = get_nav_menu_locations();

		foreach ( $menus as $menu ) {
			$item = [
				'id'        => $menu->term_id,
				'name'      => $menu->name,
				'items'     => $this->get_menu( $menu ),
				'locations' => [],
				'extra'     => [
					'site_id' => get_current_blog_id()
				],
			];

			// Add menu location slug if any.
			foreach ( $menu_locations as $location => $id ) {
				if ( $id === $menu->term_id && isset( $locations[$location] ) ) {
					$item['locations'][] = $location;
				}
			}

			$result[] = $item;
		}

		// Create menus object.
		$this->create( 'menus', $result );
	}

	/**
	 * Get menu array.
	 *
	 * @param  string $menu
	 *
	 * @return array
	 */
	protected function get_menu( $menu ) {
		$result = [];
		$items  = wp_get_nav_menu_items( $menu );

		foreach ( $items as $item ) {
			if ( intval( $item->menu_item_parent ) > 0 ) {
				continue;
			}

			$result[] = $this->create_item( $menu, $item->ID, $item );
		}

		return $result;
	}

	/**
	 * Get menu children.
	 *
	 * @param  string $menu
	 * @param  string $parent_id
	 *
	 * @return array
	 */
	protected function get_menu_children( $menu, $parent_id ) {
		if ( empty( $parent_id ) ) {
			return [];
		}

		$args = [
			'post_type'  => 'nav_menu_item',
			'meta_key'   => '_menu_item_menu_item_parent',
			'meta_value' => $parent_id,
			'tax_query'  => [
				[
					'taxonomy' => 'nav_menu',
					'field'    => 'slug',
					'terms'    => $menu
				]
			],
			'order'      => 'ASC',
			'orderby'    => 'menu_order',
		];

		$posts  = ( new \WP_Query( $args ) )->posts;
		$result = [];

		foreach ( $posts as $post ) {
			$result[] = $this->create_item( $menu, $parent_id, $post );
		}

		return $result;
	}

	/**
	 * Create menu item from a post.
	 *
	 * @param  string   $menu
	 * @param  string   $parent_id
	 * @param  \WP_Post $post
	 *
	 * @return array
	 */
	protected function create_item( $menu, $parent_id, $post ) {
		$item = [];
		$type = get_post_meta( $post->ID, '_menu_item_type', true );

		switch ( $type ) {
			case 'post_type':
				$object_id             = get_post_meta( $post->ID, '_menu_item_object_id', true );
				$object                = get_post( $object_id );
				$item['title']         = empty( $post->post_title ) ? $object->post_title : $post->post_title;
				$item['url']           = get_permalink( $object->ID );
				$item['object_id']     = intval( $object_id );
				$item['object_status'] = $object->post_status;
				break;
			case 'custom':
				$item['title']         = $post->post_title;
				$item['url']           = get_post_meta( $post->ID, '_menu_item_url', true );
				$item['object_status'] = 'publish';
				break;
			case 'taxonomy':
				$object_id             = get_post_meta( $post->ID, '_menu_item_object_id', true );
				$object                = get_term( $object_id );
				$item['title']         = empty( $post->post_title ) ? $object->name : $post->post_title;
				$itme['url']           = '';
				$item['object_id']     = intval( $object_id );
				$item['object_status'] = 'publish';
				break;
		}

		// Add common fields.
		$item['id']       = $post->ID;
		$item['target']   = get_post_meta( $post->ID, '_menu_item_target', true );
		$item['classes']  = get_post_meta( $post->ID, '_menu_item_classes', true );
		$item['parent']   = $parent_id;
		$item['children'] = $this->get_menu_children( $menu, $post->ID );
		$item['type']     = $type === 'post_type' ? 'post' : $type;

		// Remove empty classes.
		$item['classes'] = array_filter( (array) $item['classes'] );

		return $item;
	}
}
