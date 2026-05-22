<?php

/**
 * Page-slug locations for Meaningful Direction.
 *
 * IMPORTANT: Registers on acf/init only. ACF_Location is unavailable while the theme bootstrap
 * is loading (acf-location-md-page-slug.php is require'd before the ACF plugin class exists).
 */

if (!defined('ABSPATH')) {
	exit;
}

add_action(
	'acf/init',
	function (): void {

		if (!class_exists('ACF_Location', false) || !function_exists('acf_register_location_type')) {
			return;
		}

		if (class_exists('acf_location_md_page_slug', false)) {
			return;
		}

		/** Locations by page slug (page-about.php does not force post_template). */
		class acf_location_md_page_slug extends ACF_Location {

			public function initialize()
			{
				$this->name        = 'md_page_slug';
				$this->label       = __('Page slug — Meaningful Direction', 'html5blank');
				$this->category    = 'page';
				$this->object_type = 'post';
			}

			public function match($rule, $screen, $field_group)
			{
				unset($field_group);
				$post_id = isset($screen['post_id']) ? (int) $screen['post_id'] : 0;

				if (!$post_id && is_admin()) {

					if (isset($_GET['post'])) { // input var ok — admin canonical post ID.
						$post_id = (int) sanitize_text_field(wp_unslash((string) $_GET['post']));
					}

					if (!$post_id && isset($_REQUEST['post_ID'])) {

						$post_id = (int) sanitize_text_field(wp_unslash((string) $_REQUEST['post_ID']));
					}

					if (!$post_id && isset($_POST['acf_post_id'])) {

						if (strpos((string) $_POST['acf_post_id'], 'post_') === 0) {
							$post_id = (int) substr((string) $_POST['acf_post_id'], strlen('post_'));
						} else {

							$post_id = (int) (string) $_POST['acf_post_id'];
						}
					}
				}

				if (!$post_id || 'page' !== get_post_type($post_id)) {
					return false;
				}

				$slug = get_post_field('post_name', $post_id);

				if (!is_string($slug) || '' === $slug) {
					return false;
				}

				if (strpos($slug, '__trashed') !== false) {
					return false;
				}

				$needle = isset($rule['value']) ? (string) $rule['value'] : '';

				if ('' === $needle) {
					return false;
				}

				$ok       = ($slug === $needle);
				$operator = isset($rule['operator']) ? $rule['operator'] : '==';

				return '!=' === $operator ? !$ok : $ok;
			}

			public function get_values($rule)
			{
				unset($rule);
				return array(
					'about'       => __('about (About page)', 'html5blank'),
					'contact-us'  => __('contact-us (Contact page)', 'html5blank'),
				);
			}
		}

		acf_register_location_type('acf_location_md_page_slug');
	},
	5
);

