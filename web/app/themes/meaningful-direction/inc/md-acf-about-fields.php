<?php

/**
 * ACF fields for slug `about` (page-about.php hierarchy does not set post_template).
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

		$text = function (string $key, string $name, string $label, string $ins = '') use ($bw): array {
			return array_merge(
				$bw,
				array(
					'key'           => $key,
					'label'         => $label,
					'name'          => $name,
					'type'          => 'text',
					'instructions'  => $ins,
					'default_value' => '',
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

		$tof = function (string $key, string $name, string $label, string $ins = '') use ($bw): array {
			return array_merge(
				$bw,
				array(
					'key'           => $key,
					'label'         => $label,
					'name'          => $name,
					'type'          => 'true_false',
					'instructions'  => $ins,
					'message'       => '',
					'default_value' => 1,
					'ui'            => 1,
					'ui_on_text'    => '',
					'ui_off_text'   => '',
				)
			);
		};

		$image = function (string $key, string $name, string $label, string $ins = '') use ($bw): array {
			return array_merge(
				$bw,
				array(
					'key'             => $key,
					'label'           => $label,
					'name'            => $name,
					'type'            => 'image',
					'instructions'    => $ins,
					'return_format'   => 'array',
					'library'         => 'all',
					'preview_size'    => 'medium',
					'min_width'       => '',
					'min_height'      => '',
					'min_size'        => '',
					'max_width'       => '',
					'max_height'      => '',
					'max_size'        => '',
					'mime_types'      => '',
					'uploader'        => '',
				)
			);
		};

		$fields = array(
			$tab('field_md_about_tab_hero', __('Hero', 'html5blank')),
			$tof(
				'field_md_about_hero_enable',
				'about_hero_enable',
				__('Show hero', 'html5blank'),
				__('Unchecked hides the hero on the About template.', 'html5blank')
			),
			$image(
				'field_md_about_hero_image',
				'about_hero_image',
				__('Hero image', 'html5blank'),
				__('Optional. Placeholder renders when empty.', 'html5blank')
			),
			$link(
				'field_md_about_hero_primary_cta_link',
				'about_hero_primary_cta_link',
				__('Hero — primary CTA', 'html5blank'),
				__('Defaults to Contact page permalink when unset.', 'html5blank')
			),
			$link(
				'field_md_about_hero_secondary_cta_link',
				'about_hero_secondary_cta_link',
				__('Hero — secondary CTA', 'html5blank'),
				__('Defaults to Homepage #process.', 'html5blank')
			),
			$text('field_md_about_hero_eyebrow', 'about_hero_eyebrow', __('Hero — eyebrow', 'html5blank')),
			$text('field_md_about_hero_before_em', 'about_hero_headline_before_em', __('Hero headline — before emphasis', 'html5blank')),
			$text('field_md_about_hero_em', 'about_hero_headline_em', __('Hero headline — emphasis', 'html5blank')),
			$text('field_md_about_hero_after_em', 'about_hero_headline_after_em', __('Hero headline — after emphasis', 'html5blank')),
			$textarea('field_md_about_hero_sub', 'about_hero_subcopy', __('Hero — paragraph', 'html5blank')),
			$text('field_md_about_st1_num', 'about_hero_stat_1_num', __('Stat 1 — number', 'html5blank')),
			$text('field_md_about_st1_lbl', 'about_hero_stat_1_lbl', __('Stat 1 — caption', 'html5blank')),
			$text('field_md_about_st2_num', 'about_hero_stat_2_num', __('Stat 2 — number', 'html5blank')),
			$text('field_md_about_st2_lbl', 'about_hero_stat_2_lbl', __('Stat 2 — caption', 'html5blank')),
			$text('field_md_about_st3_num', 'about_hero_stat_3_num', __('Stat 3 — number', 'html5blank')),
			$text('field_md_about_st3_lbl', 'about_hero_stat_3_lbl', __('Stat 3 — caption', 'html5blank')),
			$text('field_md_about_hero_ph', 'about_hero_placeholder_label', __('Hero — placeholder label', 'html5blank')),
			$textarea('field_md_about_hero_quote', 'about_hero_quote', __('Quote card — text', 'html5blank')),
			$text('field_md_about_hero_quote_src', 'about_hero_quote_src', __('Quote card — attribution', 'html5blank')),

			$tab('field_md_about_tab_story', __('Our story', 'html5blank')),
			$image('field_md_about_story_img', 'about_story_image', __('Story photo', 'html5blank')),
			$text('field_md_about_story_ph', 'about_story_placeholder_label', __('Story placeholder label', 'html5blank')),
			$text('field_md_about_story_eyebrow', 'about_story_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_about_story_h2b', 'about_story_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_about_story_h2e', 'about_story_h2_em', __('H2 — emphasis', 'html5blank')),
			$text('field_md_about_story_h2a', 'about_story_h2_after', __('H2 — after emphasis', 'html5blank')),
			$textarea('field_md_about_story_p1', 'about_story_p1', __('Paragraph 1', 'html5blank')),
			$textarea('field_md_about_story_p2', 'about_story_p2', __('Paragraph 2', 'html5blank')),
			$textarea('field_md_about_story_p3', 'about_story_p3', __('Paragraph 3', 'html5blank')),

			$tab('field_md_about_tab_values_head', __('Values — header', 'html5blank')),
			$text('field_md_about_vals_eb', 'about_values_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_about_vals_h2b', 'about_values_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_about_vals_h2e', 'about_values_h2_em', __('H2 — emphasis', 'html5blank')),
			$text('field_md_about_vals_h2a', 'about_values_h2_after', __('H2 — after emphasis', 'html5blank')),
			$textarea('field_md_about_vals_intro', 'about_values_intro', __('Intro paragraph (right)', 'html5blank'), '', 3),

			$tab('field_md_about_tab_values_rep', __('Values — cards', 'html5blank')),
			array_merge(
				$bw,
				array(
					'key'          => 'field_md_about_values',
					'label'        => __('Value cards', 'html5blank'),
					'name'         => 'about_values',
					'type'         => 'repeater',
					'instructions' => __('Leave empty for built-in defaults (4 rows).', 'html5blank'),
					'collapsed'    => 'field_md_about_val_h',
					'min'          => 0,
					'max'          => 0,
					'layout'       => 'block',
					'button_label' => __('Add value row', 'html5blank'),
					'sub_fields'   => array(
						$text('field_md_about_val_n', 'about_value_num', __('Number label', 'html5blank')),
						$text(
							'field_md_about_val_h',
							'about_value_heading',
							__('Heading', 'html5blank')
						),
						$textarea(
							'field_md_about_val_p',
							'about_value_body',
							__('Body copy', 'html5blank'),
							'',
							4
						),
					),
				)
			),

			$tab('field_md_about_tab_team', __('Advocates (team)', 'html5blank')),
			$text('field_md_about_team_eb', 'about_team_eyebrow', __('Eyebrow', 'html5blank')),
			$textarea(
				'field_md_about_team_h2',
				'about_team_headline',
				__('Headline', 'html5blank'),
				__('Optional line break — use plain <br /> in the text.', 'html5blank'),
				3
			),
			$textarea('field_md_about_team_intro', 'about_team_intro', __('Intro paragraph', 'html5blank'), '', 3),

			array_merge(
				$bw,
				array(
					'key'          => 'field_md_about_team_rep',
					'label'        => __('Team cards', 'html5blank'),
					'name'         => 'about_team',
					'type'         => 'repeater',
					'instructions' => __('Leave empty for built-in three profiles. Add rows for additional team members.', 'html5blank'),
					'collapsed'    => 'field_md_about_tm_name',
					'min'          => 0,
					'max'          => 0,
					'layout'       => 'block',
					'button_label' => __('Add team member', 'html5blank'),
					'sub_fields'   => array(
						$text('field_md_about_tm_name', 'about_team_name', __('Name', 'html5blank')),
						$text('field_md_about_tm_role', 'about_team_role', __('Role title', 'html5blank')),
						$text('field_md_about_tm_cred', 'about_team_cred', __('Credential line', 'html5blank')),
						$textarea('field_md_about_tm_bio', 'about_team_bio', __('Bio', 'html5blank'), '', 4),
						$image('field_md_about_tm_photo', 'about_team_photo', __('Portrait', 'html5blank')),
						$text(
							'field_md_about_tm_lbl',
							'about_team_photo_label',
							__('Placeholder label (no portrait)', 'html5blank')
						),
					),
				)
			),

			$tab('field_md_about_tab_timeline_head', __('Timeline — header', 'html5blank')),
			$text('field_md_about_tl_eb', 'about_timeline_eyebrow', __('Eyebrow', 'html5blank')),
			$text('field_md_about_tl_h2b', 'about_timeline_h2_before', __('H2 — before emphasis', 'html5blank')),
			$text('field_md_about_tl_h2e', 'about_timeline_h2_em', __('H2 — emphasis', 'html5blank')),
			$text('field_md_about_tl_h2a', 'about_timeline_h2_after', __('H2 — after emphasis', 'html5blank')),
			$textarea('field_md_about_tl_intro', 'about_timeline_intro', __('Intro paragraph', 'html5blank'), '', 3),

			array_merge(
				$bw,
				array(
					'key'          => 'field_md_about_timeline',
					'label'        => __('Timeline rows', 'html5blank'),
					'name'         => 'about_timeline',
					'type'         => 'repeater',
					'instructions' => __('Leave empty for built-in milestones.', 'html5blank'),
					'collapsed'    => 'field_md_about_tm_y',
					'min'          => 0,
					'max'          => 0,
					'layout'       => 'block',
					'button_label' => __('Add year', 'html5blank'),
					'sub_fields'   => array(
						$text('field_md_about_tm_y', 'about_timeline_year', __('Year', 'html5blank')),
						$text(
							'field_md_about_tm_h',
							'about_timeline_heading',
							__('Heading', 'html5blank')
						),
						$textarea(
							'field_md_about_tm_p',
							'about_timeline_body',
							__('Paragraph', 'html5blank'),
							'',
							3
						),
					),
				)
			),

			$tab('field_md_about_tab_footer_cta', __('Footer band CTA', 'html5blank')),
			$link(
				'field_md_about_foot_link',
				'about_footer_primary_cta_link',
				__('Primary CTA', 'html5blank'),
				__('Defaults to Contact page.', 'html5blank')
			),
			$text(
				'field_md_about_foot_phone',
				'about_footer_phone_display',
				__('Phone (display)', 'html5blank'),
				__('Falls back to Home → Contact settings when blank.', 'html5blank')
			),
			$text('field_md_about_foot_eb', 'about_footer_eyebrow', __('Eyebrow', 'html5blank')),
			$textarea(
				'field_md_about_foot_h2',
				'about_footer_headline',
				__('Headline', 'html5blank'),
				__('Optional line break — use plain <br /> in the text.', 'html5blank'),
				3
			),
			$textarea(
				'field_md_about_foot_body',
				'about_footer_body',
				__('Body paragraph', 'html5blank'),
				'',
				3
			),
		);

		acf_add_local_field_group(
			array(
				'key'                   => 'group_md_about_page',
				'title'                 => __('About page', 'html5blank'),
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
							'value'    => 'about',
						),
					),
				),
				'menu_order'            => 15,
				'position'              => 'acf_after_title',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => __('Content for Meaningful Direction /about/ (slug about). Mission & vision still come from the static front page.', 'html5blank'),
				'show_in_rest'          => 0,
			)
		);
	},
	99
);
