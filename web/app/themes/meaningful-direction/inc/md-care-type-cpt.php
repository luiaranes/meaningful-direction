<?php
/**
 * Custom post type: Care Types (levels of care content).
 */

if (! defined('ABSPATH')) {
	exit;
}

/**
 * Post type name (internal slug).
 */
function md_care_type_post_type(): string
{
	return 'care_type';
}

/**
 * @see register_post_type()
 */
function md_register_care_type_cpt(): void
{
	register_post_type(
		md_care_type_post_type(),
		array(
			'labels'              => array(
				'name'               => __('Care Types', 'html5blank'),
				'singular_name'      => __('Care Type', 'html5blank'),
				'menu_name'          => __('Care Types', 'html5blank'),
				'all_items'          => __('All Care Types', 'html5blank'),
				'add_new'            => __('Add New', 'html5blank'),
				'add_new_item'       => __('Add New Care Type', 'html5blank'),
				'edit_item'          => __('Edit Care Type', 'html5blank'),
				'new_item'           => __('New Care Type', 'html5blank'),
				'view_item'          => __('View Care Type', 'html5blank'),
				'search_items'       => __('Search Care Types', 'html5blank'),
				'not_found'          => __('No care types found.', 'html5blank'),
				'not_found_in_trash' => __('No care types found in Trash.', 'html5blank'),
			),
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'hierarchical'        => false,
			'has_archive'         => true,
			'menu_position'       => 25,
			'menu_icon'           => 'dashicons-heart',
			'capability_type'     => 'post',
			'map_meta_cap'        => true,
			'rewrite'             => array(
				'slug'       => 'care-types',
				'with_front' => false,
			),
			'supports'            => array(
				'title',
				'editor',
				'excerpt',
				'thumbnail',
				'page-attributes',
			),
		),
	);
}

add_action('init', 'md_register_care_type_cpt', 10);

/** Rebuild permalink rules after theme switch when this CPT registers. */
add_action(
	'after_switch_theme',
	static function (): void {
		md_register_care_type_cpt();
		flush_rewrite_rules();
	}
);

add_action(
	'pre_get_posts',
	static function (\WP_Query $q): void {
		if (is_admin() || !$q->is_main_query()) {
			return;
		}
		if (!$q->is_post_type_archive(md_care_type_post_type())) {
			return;
		}
		$q->set('orderby', array( 'menu_order' => 'ASC', 'title' => 'ASC' ));
		$q->set('posts_per_page', 48);
	}
);
