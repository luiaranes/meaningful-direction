<?php
/**
 * ACF fields for slug `contact-us` (page-contact-us.php).
 *
 * @package meaningful-direction
 */

if (!defined('ABSPATH')) {
	exit;
}

add_action(
	'acf/init',
	function (): void {
		if (!function_exists('acf_add_local_field_group')) {
			return;
		}

		$bw = array(
			'instructions'      => '',
			'required'          => false,
			'conditional_logic' => false,
			'wrapper'           => array(
				'width' => '',
				'class' => '',
				'id'    => '',
			),
		);

		$tab = function (string $key, string $label) use ($bw): array {
			return array_merge(
				array(
					'key'         => $key,
					'label'       => $label,
					'name'        => '',
					'type'        => 'tab',
					'placement'   => 'top',
					'endpoint'    => 0,
					'selected'    => 0,
				),
				$bw
			);
		};

		$text = function (string $key, string $name, string $label, string $ins = '', string $def = '') use ($bw): array {
			return array_merge(
				$bw,
				array(
					'key'           => $key,
					'label'         => $label,
					'name'          => $name,
					'type'          => 'text',
					'instructions'  => $ins,
					'default_value' => $def,
					'maxlength'     => '',
					'placeholder'   => '',
				)
			);
		};

		$textarea = function (string $key, string $name, string $label, string $ins = '', int $rows = 4) use ($bw): array {
			return array_merge(
				$bw,
				array(
					'key'           => $key,
					'label'         => $label,
					'name'          => $name,
					'type'          => 'textarea',
					'instructions'  => $ins,
					'default_value' => '',
					'maxlength'     => '',
					'placeholder'   => '',
					'rows'          => $rows,
					'new_lines'     => '',
				)
			);
		};

		$link = function (string $key, string $name, string $label, string $ins = '') use ($bw): array {
			return array_merge(
				$bw,
				array(
					'key'               => $key,
					'label'             => $label,
					'name'              => $name,
					'type'              => 'link',
					'instructions'      => $ins,
					'return_format'     => 'array',
				)
			);
		};

		$image = function (string $key, string $name, string $label, string $ins = '') use ($bw): array {
			return array_merge(
				$bw,
				array(
					'key'           => $key,
					'label'         => $label,
					'name'          => $name,
					'type'          => 'image',
					'instructions'  => $ins,
					'return_format' => 'array',
					'library'       => 'all',
					'preview_size'  => 'medium',
				)
			);
		};

		$fields = array(
			$tab('field_md_contact_tab_hero', __('Hero & details', 'html5blank')),
			$text(
				'field_md_contact_hero_eb',
				'contact_pg_hero_eyebrow',
				__('Eyebrow', 'html5blank')
			),
			$text(
				'field_md_contact_hero_h1b',
				'contact_pg_hero_h1_before',
				__('H1 — before emphasis', 'html5blank')
			),
			$text(
				'field_md_contact_hero_h1e',
				'contact_pg_hero_h1_em',
				__('H1 — emphasis', 'html5blank')
			),
			$text(
				'field_md_contact_hero_h1a',
				'contact_pg_hero_h1_after',
				__('H1 — after emphasis', 'html5blank')
			),
			$textarea(
				'field_md_contact_hero_intro',
				'contact_pg_hero_intro',
				__('Intro paragraph', 'html5blank'),
				'',
				3
			),
			$text(
				'field_md_contact_phone',
				'contact_pg_phone_display',
				__('Call — phone (display)', 'html5blank'),
				__('Falls back to Homepage → Contact phone when blank.', 'html5blank')
			),
			$text(
				'field_md_contact_call_note',
				'contact_pg_call_note',
				__('Call — note', 'html5blank')
			),
			$text(
				'field_md_contact_email',
				'contact_pg_email_display',
				__('Email address', 'html5blank'),
				__('Falls back to Homepage → Contact email when blank.', 'html5blank')
			),
			$text(
				'field_md_contact_email_note',
				'contact_pg_email_note',
				__('Email — note', 'html5blank')
			),
			$text(
				'field_md_contact_visit_val',
				'contact_pg_visit_line1',
				__('Visit — line 1', 'html5blank')
			),
			$text(
				'field_md_contact_visit_note',
				'contact_pg_visit_line2',
				__('Visit — line 2', 'html5blank')
			),

			$tab('field_md_contact_tab_form', __('Form card', 'html5blank')),
			$text(
				'field_md_contact_form_eb',
				'contact_pg_form_eyebrow',
				__('Eyebrow', 'html5blank')
			),
			$text(
				'field_md_contact_form_title',
				'contact_pg_form_title',
				__('Title', 'html5blank')
			),
			$textarea(
				'field_md_contact_form_sub',
				'contact_pg_form_sub',
				__('Subcopy', 'html5blank'),
				'',
				3
			),
			$text(
				'field_md_contact_form_shortcode',
				'contact_pg_form_shortcode',
				__('Form shortcode', 'html5blank'),
				__('e.g. [formidable id=1]', 'html5blank'),
				'[formidable id=1]'
			),

			$tab('field_md_contact_tab_ways', __('Three ways', 'html5blank')),
			$text('field_md_contact_ways_eb', 'contact_pg_ways_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_contact_ways_h2b', 'contact_pg_ways_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_contact_ways_h2e', 'contact_pg_ways_h2_em', __('H2 — emphasis', 'html5blank')),
			$text('field_md_contact_ways_h2a', 'contact_pg_ways_h2_after', __('H2 — after emphasis', 'html5blank')),
			$textarea(
				'field_md_contact_ways_intro',
				'contact_pg_ways_intro',
				__('Intro (right column)', 'html5blank'),
				'',
				3
			),
			array_merge(
				$bw,
				array(
					'key'          => 'field_md_contact_ways_rep',
					'label'        => __('Way cards', 'html5blank'),
					'name'         => 'contact_pg_ways',
					'type'         => 'repeater',
					'instructions' => __('Leave empty for three built-in defaults.', 'html5blank'),
					'collapsed'    => 'field_md_contact_way_title',
					'min'          => 0,
					'max'          => 0,
					'layout'       => 'block',
					'button_label' => __('Add way', 'html5blank'),
					'sub_fields'   => array(
						$text('field_md_contact_way_num', 'contact_pg_way_num', __('Number', 'html5blank')),
						$text('field_md_contact_way_title', 'contact_pg_way_title', __('Title', 'html5blank')),
						$textarea('field_md_contact_way_body', 'contact_pg_way_body', __('Body', 'html5blank'), '', 3),
						$link(
							'field_md_contact_way_link',
							'contact_pg_way_link',
							__('Link', 'html5blank'),
							__('Use #form or tel: as needed.', 'html5blank')
						),
					),
				)
			),

			$tab('field_md_contact_tab_faq', __('FAQ', 'html5blank')),
			$text('field_md_contact_faq_eb', 'contact_pg_faq_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_contact_faq_h2b', 'contact_pg_faq_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_contact_faq_h2e', 'contact_pg_faq_h2_em', __('H2 — emphasis', 'html5blank')),
			$text('field_md_contact_faq_h2a', 'contact_pg_faq_h2_after', __('H2 — after emphasis', 'html5blank')),
			$textarea(
				'field_md_contact_faq_intro',
				'contact_pg_faq_intro',
				__('Intro paragraph', 'html5blank'),
				'',
				3
			),
			array_merge(
				$bw,
				array(
					'key'          => 'field_md_contact_faq_rep',
					'label'        => __('Questions', 'html5blank'),
					'name'         => 'contact_pg_faq',
					'type'         => 'repeater',
					'instructions' => __('Leave empty for five built-in Q&As.', 'html5blank'),
					'collapsed'    => 'field_md_contact_faq_q',
					'min'          => 0,
					'max'          => 0,
					'layout'       => 'block',
					'button_label' => __('Add question', 'html5blank'),
					'sub_fields'   => array(
						$text('field_md_contact_faq_q', 'contact_pg_faq_q', __('Question', 'html5blank')),
						$textarea('field_md_contact_faq_a', 'contact_pg_faq_a', __('Answer', 'html5blank'), '', 4),
					),
				)
			),

			$tab('field_md_contact_tab_area', __('Service area', 'html5blank')),
			$text('field_md_contact_area_eb', 'contact_pg_area_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_contact_area_h2b', 'contact_pg_area_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_contact_area_h2e', 'contact_pg_area_h2_em', __('H2 — emphasis', 'html5blank')),
			$text('field_md_contact_area_h2a', 'contact_pg_area_h2_after', __('H2 — after emphasis', 'html5blank')),
			$textarea(
				'field_md_contact_area_intro',
				'contact_pg_area_intro',
				__('Intro (right column)', 'html5blank'),
				'',
				3
			),
			$image(
				'field_md_contact_area_map',
				'contact_pg_area_map',
				__('Map image', 'html5blank'),
				__('Optional. Placeholder when empty.', 'html5blank')
			),
			$text(
				'field_md_contact_area_map_ph',
				'contact_pg_area_map_placeholder',
				__('Map placeholder label', 'html5blank')
			),
			$textarea(
				'field_md_contact_area_note',
				'contact_pg_area_note',
				__('Footnote under city list', 'html5blank'),
				'',
				3
			),
			array_merge(
				$bw,
				array(
					'key'          => 'field_md_contact_area_rep',
					'label'        => __('Cities / towns', 'html5blank'),
					'name'         => 'contact_pg_area_places',
					'type'         => 'repeater',
					'instructions' => __('Leave empty for the built-in county list.', 'html5blank'),
					'collapsed'    => 'field_md_contact_area_place_name',
					'min'          => 0,
					'max'          => 0,
					'layout'       => 'table',
					'button_label' => __('Add place', 'html5blank'),
					'sub_fields'   => array(
						$text('field_md_contact_area_place_name', 'contact_pg_area_place_name', __('Name', 'html5blank')),
					),
				)
			),
		);

		acf_add_local_field_group(
			array(
				'key'                   => 'group_md_contact_page',
				'title'                 => __('Contact page', 'html5blank'),
				'fields'                => $fields,
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => 'page',
						),
						array(
							'param'    => 'md_page_slug',
							'operator' => '==',
							'value'    => 'contact-us',
						),
					),
				),
				'menu_order'            => 16,
				'position'              => 'acf_after_title',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => __('Content for /contact-us/ (slug contact-us).', 'html5blank'),
				'show_in_rest'          => 0,
			)
		);
	},
	99
);
