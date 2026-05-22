<?php
/**
 * ACF fields for Care Types (`care_type` CPT → single-care_type.php).
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
					'key'        => $key,
					'label'      => $label,
					'name'       => '',
					'type'       => 'tab',
					'placement'  => 'top',
					'endpoint'   => 0,
					'selected'   => 0,
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

		$url = function (string $key, string $name, string $label, string $ins = '') use ($bw): array {
			return array_merge(
				$bw,
				array(
					'key'          => $key,
					'label'        => $label,
					'name'         => $name,
					'type'         => 'url',
					'instructions' => $ins,
					'default_value'=> '',
				)
			);
		};

		$link = function (string $key, string $name, string $label, string $ins = '') use ($bw): array {
			return array_merge(
				$bw,
				array(
					'key'           => $key,
					'label'         => $label,
					'name'          => $name,
					'type'          => 'link',
					'instructions'  => $ins,
					'return_format' => 'array',
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
			$tab('field_md_care_tp_tab_nav', __('Back link & CTAs', 'html5blank')),
			$url(
				'field_md_care_tp_back_url',
				'care_tp_back_url',
				__('Back to all care types URL', 'html5blank'),
				__('Blank uses the Care Types archive (/care-types/), or homepage #care when the archive is unavailable.', 'html5blank')
			),
			$link(
				'field_md_care_tp_primary_link',
				'care_tp_primary_cta_link',
				__('Primary hero CTA link', 'html5blank'),
				__('Default: Talk with an advocate → #contact on this page.', 'html5blank')
			),
			$link(
				'field_md_care_tp_secondary_link',
				'care_tp_secondary_cta_link',
				__('Secondary hero CTA link', 'html5blank'),
				__('Default: See how we help → #how-we-help on this page.', 'html5blank')
			),

			$tab('field_md_care_tp_tab_hero', __('Hero', 'html5blank')),
			$text('field_md_care_tp_hdr_e', 'care_tp_hero_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_care_tp_h1_b', 'care_tp_hero_h1_before', __('Headline — before emphasis', 'html5blank')),
			$text('field_md_care_tp_h1_e', 'care_tp_hero_h1_emphasis', __('Headline — emphasis line', 'html5blank')),
			$text('field_md_care_tp_h1_a', 'care_tp_hero_h1_after', __('Headline — after emphasis', 'html5blank')),
			$textarea('field_md_care_tp_hs', 'care_tp_hero_subcopy', __('Supporting paragraph', 'html5blank'), '', 4),
			$image('field_md_care_tp_hi', 'care_tp_hero_image', __('Hero image', 'html5blank')),
			$text(
				'field_md_care_tp_hph',
				'care_tp_hero_placeholder_label',
				__('Hero placeholder chip (no image)', 'html5blank')
			),
			$textarea('field_md_care_tp_quote', 'care_tp_hero_quote', __('Accent card quote', 'html5blank'), '', 3),
			$text('field_md_care_tp_qsrc', 'care_tp_hero_quote_src', __('Accent card attribution', 'html5blank')),
			array_merge(
				$bw,
				array(
					'key'           => 'field_md_care_tp_stats',
					'label'         => __('Hero stats row', 'html5blank'),
					'name'          => 'care_tp_hero_stats',
					'type'          => 'repeater',
					'instructions'  => __('Three columns beside the headline. Leave empty for built-in Assisted Living defaults.', 'html5blank'),
					'collapsed'     => '',
					'min'           => 0,
					'max'           => 0,
					'layout'        => 'table',
					'button_label'  => __('Add stat', 'html5blank'),
					'sub_fields'    => array(
						$text('field_md_care_tp_sn', 'care_tp_stat_num', __('Stat (display)', 'html5blank')),
						$text('field_md_care_tp_sl', 'care_tp_stat_lbl', __('Label', 'html5blank')),
					),
				)
			),

			$tab('field_md_care_tp_tab_miss', __('Mission strip', 'html5blank')),
			array_merge(
				$bw,
				array(
					'key'          => 'field_md_care_tp_mission_rep',
					'label'        => __('Two pillar blocks', 'html5blank'),
					'name'         => 'care_tp_mission_strip',
					'type'         => 'repeater',
					'instructions' => __('Allows <code>em</code> for gentle emphasis.', 'html5blank'),
					'collapsed'    => '',
					'min'          => 0,
					'max'          => 0,
					'layout'       => 'row',
					'button_label' => __('Add block', 'html5blank'),
					'sub_fields'   => array(
						$text('field_md_care_tp_ml', 'care_tp_mission_label', __('Label', 'html5blank')),
						$textarea('field_md_care_tp_mtx', 'care_tp_mission_text', __('Body copy', 'html5blank'), '', 3),
					),
				)
			),

			$tab('field_md_care_tp_tab_what', __('What it is block', 'html5blank')),
			$text('field_md_care_tp_w_e', 'care_tp_what_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_care_tp_whb', 'care_tp_what_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_care_tp_whe', 'care_tp_what_h2_emphasis', __('H2 — emphasis', 'html5blank')),
			$text('field_md_care_tp_wha', 'care_tp_what_h2_after', __('H2 — after emphasis', 'html5blank')),
			$image('field_md_care_tp_wi', 'care_tp_what_image', __('Supporting image', 'html5blank')),
			$text(
				'field_md_care_tp_wph',
				'care_tp_what_placeholder_label',
				__('Image placeholder chip (no image)', 'html5blank')
			),
			$textarea('field_md_care_tp_wp1', 'care_tp_what_p1', __('Paragraph one', 'html5blank'), '', 5),
			$textarea('field_md_care_tp_wp2', 'care_tp_what_p2', __('Paragraph two', 'html5blank'), '', 5),
			array_merge(
				$bw,
				array(
					'key'           => 'field_md_care_tp_facets',
					'label'         => __('Facet bullets', 'html5blank'),
					'name'          => 'care_tp_what_facets',
					'type'          => 'repeater',
					'instructions'  => '',
					'collapsed'     => 'field_md_care_tp_facet_h',
					'min'           => 0,
					'max'           => 0,
					'layout'        => 'row',
					'button_label'  => __('Add facet', 'html5blank'),
					'sub_fields'    => array(
						$text('field_md_care_tp_facet_h', 'care_tp_facet_heading', __('Heading', 'html5blank')),
						$textarea('field_md_care_tp_facet_p', 'care_tp_facet_body', __('Body', 'html5blank'), '', 3),
					),
				)
			),

			$tab('field_md_care_tp_tab_sig', __('Signs grid', 'html5blank')),
			$text('field_md_care_tp_sg_e', 'care_tp_signs_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_care_tp_sghb', 'care_tp_signs_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_care_tp_sghe', 'care_tp_signs_h2_emphasis', __('H2 — emphasis', 'html5blank')),
			$text('field_md_care_tp_sgha', 'care_tp_signs_h2_after', __('H2 — after emphasis', 'html5blank')),
			$textarea('field_md_care_tp_sgint', 'care_tp_signs_intro', __('Lead paragraph', 'html5blank'), '', 4),
			array_merge(
				$bw,
				array(
					'key'           => 'field_md_care_tp_signs_rep',
					'label'         => __('Numbered cards', 'html5blank'),
					'name'          => 'care_tp_signs_grid',
					'type'          => 'repeater',
					'instructions'  =>
						__('Keeps Assisted Living numbering when untouched. Remove rows sparingly—they align with curated defaults.', 'html5blank'),
					'collapsed'     => 'field_md_care_tp_sign_h',
					'min'           => 0,
					'max'           => 0,
					'layout'        => 'row',
					'button_label'  => __('Add card', 'html5blank'),
					'sub_fields'    => array(
						$text('field_md_care_tp_sign_n', 'care_tp_sign_num', __('Number', 'html5blank')),
						$text('field_md_care_tp_sign_h', 'care_tp_sign_heading', __('Heading', 'html5blank')),
						$textarea('field_md_care_tp_sign_p', 'care_tp_sign_body', __('Body copy', 'html5blank'), '', 3),
					),
				)
			),

			$tab('field_md_care_tp_tab_help', __('How we help', 'html5blank')),
			$text('field_md_care_tp_he', 'care_tp_help_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_care_tp_hhb', 'care_tp_help_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_care_tp_hhe', 'care_tp_help_h2_emphasis', __('H2 — emphasis', 'html5blank')),
			$text('field_md_care_tp_hha', 'care_tp_help_h2_after', __('H2 — after emphasis', 'html5blank')),
			$textarea('field_md_care_tp_hint', 'care_tp_help_intro', __('Intro paragraph', 'html5blank'), '', 4),
			array_merge(
				$bw,
				array(
					'key'           => 'field_md_care_tp_help_steps',
					'label'         => __('Process tabs', 'html5blank'),
					'name'          => 'care_tp_help_steps',
					'type'          => 'repeater',
					'instructions'  => __('Rails show the bold title column; bullets appear in the expanded panel.', 'html5blank'),
					'collapsed'     => 'field_md_care_tp_step_lab',
					'min'           => 0,
					'max'           => 0,
					'layout'        => 'row',
					'button_label'  => __('Add step', 'html5blank'),
					'sub_fields'    => array(
						$text('field_md_care_tp_step_n', 'care_tp_step_n', __('Step number', 'html5blank')),
						$text('field_md_care_tp_step_lab', 'care_tp_step_label', __('Short label', 'html5blank')),
						$text('field_md_care_tp_step_ttl', 'care_tp_step_title', __('Rail title', 'html5blank')),
						$textarea('field_md_care_tp_step_dc', 'care_tp_step_desc', __('Panel overview', 'html5blank'), '', 5),
						array_merge(
							$bw,
							array(
								'key'          => 'field_md_care_tp_step_bulls',
								'label'        => __('Panel bullets', 'html5blank'),
								'name'         => 'care_tp_step_bullets',
								'type'         => 'repeater',
								'instructions' => '',
								'collapsed'    => '',
								'min'          => 0,
								'max'          => 0,
								'layout'       => 'row',
								'button_label' => __('Add bullet', 'html5blank'),
								'sub_fields'   => array(
									$text('field_md_care_tp_bh', 'care_tp_bullet_heading', __('Heading', 'html5blank')),
									$textarea('field_md_care_tp_bb', 'care_tp_bullet_body', __('Copy', 'html5blank'), '', 3),
								),
							)
						),
					),
				)
			),

			$tab('field_md_care_tp_tab_other', __('Other care types teaser', 'html5blank')),
			$text('field_md_care_tp_oe', 'care_tp_other_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_care_tp_ohb', 'care_tp_other_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_care_tp_ohe', 'care_tp_other_h2_emphasis', __('H2 — emphasis', 'html5blank')),
			$text('field_md_care_tp_oha', 'care_tp_other_h2_after', __('H2 — after emphasis', 'html5blank')),
			$textarea('field_md_care_tp_oi', 'care_tp_other_intro', __('Intro paragraph', 'html5blank'), '', 3),
			$text(
				'field_md_care_tp_card_blurb',
				'care_tp_cross_short_desc',
				__('Card blurb cross-link teaser', 'html5blank'),
				__('Shown on sibling care type grids; falls back to the Excerpt.', 'html5blank')
			),
		);

		acf_add_local_field_group(
			array(
				'key'                   => 'group_md_care_type_page',
				'title'                 => __('Care Type page', 'html5blank'),
				'fields'                => $fields,
				'location'              => array(
					array(
						array(
							'param'    => 'post_type',
							'operator' => '==',
							'value'    => md_care_type_post_type(),
						),
					),
				),
				'menu_order'            => 26,
				'position'              => 'acf_after_title',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => __('Content for Meaningful Direction care-type landing pages.', 'html5blank'),
				'show_in_rest'          => 0,
			)
		);
	},
	98
);
