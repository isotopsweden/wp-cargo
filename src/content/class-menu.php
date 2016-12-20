<?php

namespace Isotop\Cargo\Content;

class Menu extends Abstract_Content {

	/**
	 * Menu constructor.
	 */
	public function __construct() {
		$menus  = wp_get_nav_menus();
		$result = [];

		foreach ( $menus as $menu ) {
			$result[] = [
				'id'   => $menu->term_id,
				'name' => $menu->name,
				'menu' => $this->get_menu( $menu )
			];
		}


		// Create menu object.
		$this->create( 'menu', $result );
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

			$result[] = [
				'id'       => $item->ID,
				'url'      => $item->url,
				'title'    => $item->title,
				'target'   => $item->target,
				'classes'  => $item->classes,
				'type'     => $item->object === 'page' ? 'post' : $item->object,
				'parent'   => 0,
				'children' => $this->get_menu_children( $menu, $item->ID )
			];
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
			$item = [];
			$type = get_post_meta( $post->ID, '_menu_item_type', true );

			switch ( $type ) {
				case 'post_type':
					$object_id     = get_post_meta( $post->ID, '_menu_item_object_id', true );
					$object        = get_post( $object_id );
					$item['title'] = $object->post_title;
					$item['url']   = get_permalink( $object->ID );
					break;
				case 'custom':
					$item['title'] = $post->post_title;
					$item['url']   = get_post_meta( $post->ID, '_menu_item_url', true );
					break;
			}

			$item['id']       = $post->ID;
			$item['target']   = get_post_meta( $post->ID, '_menu_item_target', true );
			$item['classes']  = get_post_meta( $post->ID, '_menu_item_classes', true );
			$item['parent']   = $parent_id;
			$item['children'] = $this->get_menu_children( $menu, $post->ID );
			$item['type']     = $type === 'post_type' ? 'post' : $type;

			$result[] = $item;
		}

		return $result;
	}
}
