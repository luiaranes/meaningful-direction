<?php
/**
 * Care Type (CPT) single — resolves ACF with defaults aligned to Assisted Living standalone prototype.
 *
 * @package meaningful-direction
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * Primary nav URL for a care-type entry: CPT single first, otherwise legacy page or homepage hash.
 *
 * @param string $post_slug            Expected care_type {@see post_name} (e.g. assisted-living).
 * @param string $fragment_without_hash Home fragment when no CPT and no WP page (e.g. care-assisted).
 */
function md_care_type_nav_url(string $post_slug, string $fragment_without_hash): string
{
	if (!function_exists('md_care_type_post_type')) {
		return md_nav_url_page_or_hash($post_slug, $fragment_without_hash);
	}

	$slug = sanitize_title($post_slug);
	if ($slug === '') {
		return md_nav_url_page_or_hash($post_slug, $fragment_without_hash);
	}

	$post = get_posts(
		array(
			'name'           => $slug,
			'post_type'      => md_care_type_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);

	if (is_array($post) && isset($post[0]) && $post[0]) {
		return get_permalink((int) $post[0]);
	}

	return md_nav_url_page_or_hash($post_slug, $fragment_without_hash);
}

/**
 * URL for the Care Types archive (/care-types/); homepage #care if the archive is unavailable.
 */
function md_care_type_archive_url(): string
{
	if (!function_exists('get_post_type_archive_link') || !function_exists('md_care_type_post_type')) {
		return trailingslashit(home_url('/')) . '#care';
	}

	$url = get_post_type_archive_link(md_care_type_post_type());
	if (is_string($url) && $url !== '') {
		return $url;
	}

	return trailingslashit(home_url('/')) . '#care';
}

/**
 * @param int $post_id Care type post ID.
 */
function md_get_care_type_field(string $name, $fallback = '', int $post_id = 0)
{
	if (!function_exists('get_field')) {
		return $fallback;
	}
	if (!$post_id) {
		return $fallback;
	}
	$value = get_field($name, $post_id);
	if ($value === null || $value === false || $value === '') {
		return $fallback;
	}
	if (
		is_array($value)
		&& str_ends_with($name, '_link')
		&& trim((string) ($value['url'] ?? '')) === ''
	) {
		return $fallback;
	}

	return $value;
}

/** @return array{em?:true,strong?:true,br?:true} */
function md_care_type_m_allowed(): array
{
	return array(
		'em'     => array(),
		'strong' => array(),
		'br'     => array(),
	);
}

/**
 * @return array<int, array{n:string,num:string,lbl:string}>
 */
function md_care_type_default_stats_assisted(): array
{
	return array(
		array(
			'n'   => '',
			'num' => '24+',
			'lbl' => __('communities in our county', 'html5blank'),
		),
		array(
			'n'   => '',
			'num' => '$4.5–7.5k',
			'lbl' => __('monthly range', 'html5blank'),
		),
		array(
			'n'   => '',
			'num' => '1:1',
			'lbl' => __('advocate guidance', 'html5blank'),
		),
	);
}

/**
 * Match front-page care card by {@see sanitize_title()} of the English title → URL slug.
 *
 * @return array{title:string,desc:string}|null
 */
function md_care_type_level_card_for_slug(string $slug): ?array
{
	foreach (md_front_page_care_levels() as $lvl) {
		$t = isset($lvl['title']) ? (string) $lvl['title'] : '';
		if ($t === '') {
			continue;
		}
		if (sanitize_title($t) === $slug) {
			return array(
				'title' => $t,
				'desc'  => isset($lvl['desc']) ? (string) $lvl['desc'] : '',
			);
		}
	}

	return null;
}

/**
 * @return list<array{label:string,text:string}>
 */
function md_care_type_default_pillars_assisted(): array
{
	$m_em = md_care_type_m_allowed();

	return array(
		array(
			'label' => __('What it is', 'html5blank'),
			'text'  => __(
				'A <em>private apartment</em>, with caregivers a doorbell away — for the few daily tasks that have become harder.',
				'html5blank'
			),
			'_kses' => $m_em,
		),
		array(
			'label' => __('Who it\'s for', 'html5blank'),
			'text'  => __(
				'Seniors who are <em>mostly themselves</em> — but where bathing, medication, or meals have started to slip.',
				'html5blank'
			),
			'_kses' => $m_em,
		),
	);
}

/**
 * Non–assisted-living placeholders (hero/pillars only); editors fill ACF or replace via slug-specific fields later.
 *
 * @return list<array{label:string,text:string,_kses:array}>
 */
function md_care_type_default_pillars_generic(string $intro_text): array
{
	$m_em = md_care_type_m_allowed();

	return array(
		array(
			'label' => __('What it is', 'html5blank'),
			'text'  => $intro_text !== '' ? $intro_text : __('Describe this level of care for families landing on this page.', 'html5blank'),
			'_kses' => $m_em,
		),
		array(
			'label' => __('How we guide you', 'html5blank'),
			'text'  => __(
				'Indian River County has many options—we walk families through every reputable community until the comparison feels grounded in truth, not brochures.',
				'html5blank'
			),
			'_kses' => $m_em,
		),
	);
}

/**
 * @return list<array{h:string,p:string}>
 */
function md_care_type_default_facets_assisted(): array
{
	return array(
		array(
			'h' => __('Private apartment', 'html5blank'),
			'p' => __('Studio or one-bedroom, with a kitchenette and your own front door.', 'html5blank'),
		),
		array(
			'h' => __('Personal care', 'html5blank'),
			'p' => __('Help with bathing, dressing, medication, and meals — only what\'s needed.', 'html5blank'),
		),
		array(
			'h' => __('Shared dining', 'html5blank'),
			'p' => __('Restaurant-style meals in a dining room, three times a day.', 'html5blank'),
		),
		array(
			'h' => __('Optional programming', 'html5blank'),
			'p' => __('Book club, chair-yoga, social hours — none of it required.', 'html5blank'),
		),
	);
}

/**
 * @return list<array{n:string,h:string,p:string}>
 */
function md_care_type_default_signs_assisted(): array
{
	return array(
		array(
			'n' => '01',
			'h' => __('Medications missed or doubled', 'html5blank'),
			'p' => __('The pill organizer is full some days, empty others. A new prescription has gone unfilled for weeks.', 'html5blank'),
		),
		array(
			'n' => '02',
			'h' => __('Meals have shrunk', 'html5blank'),
			'p' => __('The fridge holds crackers, tea, and last week\'s leftovers. Cooking has quietly become too much.', 'html5blank'),
		),
		array(
			'n' => '03',
			'h' => __('A recent fall — or near-miss', 'html5blank'),
			'p' => __('A bruise nobody can quite explain. A cane left in the car. The bath that hasn\'t happened in a week.', 'html5blank'),
		),
		array(
			'n' => '04',
			'h' => __('The mail is piling up', 'html5blank'),
			'p' => __('Bills go unopened. A late notice arrives. Driving has quietly stopped, and so has banking, and so has church.', 'html5blank'),
		),
		array(
			'n' => '05',
			'h' => __('The family caregiver is exhausted', 'html5blank'),
			'p' => __('A spouse or adult child is doing the work of three people. The burnout is real, and it\'s not sustainable.', 'html5blank'),
		),
		array(
			'n' => '06',
			'h' => __('Loneliness has set in', 'html5blank'),
			'p' => __('The house is quiet. Friends have moved or passed. The TV is on more hours than it isn\'t.', 'html5blank'),
		),
	);
}

/**
 * @return array<int, array{n:string,label:string,title:string,desc:string,bullets:list<array{h:string,p:string}>}>
 */
function md_care_type_default_help_steps_assisted(): array
{
	return array(
		array(
			'n'       => '01',
			'label'   => __('Listen', 'html5blank'),
			'title'   => __('We start with your loved one — not a brochure', 'html5blank'),
			'desc'    => __(
				'A free Discovery Session, in person or by phone. We ask about her morning routine, her sister, her cat, the church choir she misses. Clinical needs come up. So does everything else.',
				'html5blank'
			),
			'bullets' => array(
				array(
					'h' => __('What\'s changed', 'html5blank'),
					'p' => __('We map the slips — meds, meals, mobility — without judgment, and without a sales pitch.', 'html5blank'),
				),
				array(
					'h' => __('What matters', 'html5blank'),
					'p' => __('Proximity to grandchildren. A Catholic chapel. A place where her best friend can move in too. We weigh the heart, too.', 'html5blank'),
				),
			),
		),
		array(
			'n'       => '02',
			'label'   => __('Curate', 'html5blank'),
			'title'   => __('We surface the three or four worth seeing', 'html5blank'),
			'desc'    => __(
				'We\'ve walked through every assisted living community in Indian River County. We know wait lists, recent ownership changes, and which night staff actually answer call buttons within the minute.',
				'html5blank'
			),
			'bullets' => array(
				array(
					'h' => __('Care fit', 'html5blank'),
					'p' => __('We confirm each community is licensed for what your loved one needs — now, and as needs evolve.', 'html5blank'),
				),
				array(
					'h' => __('Real-world honesty', 'html5blank'),
					'p' => __('We tell you which communities to skip, and why. The brochure won\'t.', 'html5blank'),
				),
			),
		),
		array(
			'n'       => '03',
			'label'   => __('Tour', 'html5blank'),
			'title'   => __('We tour with you — and read the contracts', 'html5blank'),
			'desc'    => __(
				'Your advocate joins each visit. We ask the questions families don\'t think to ask in the moment, and we read every contract before you sign it.',
				'html5blank'
			),
			'bullets' => array(
				array(
					'h' => __('Accompanied visits', 'html5blank'),
					'p' => __('Two or three communities, in a single afternoon. Side-by-side notes, not glossy folders.', 'html5blank'),
				),
				array(
					'h' => __('Contract review', 'html5blank'),
					'p' => __('Care-level fees, rate-increase caps, discharge clauses. We catch the language that matters.', 'html5blank'),
				),
			),
		),
		array(
			'n'       => '04',
			'label'   => __('Settle', 'html5blank'),
			'title'   => __('We stay close after move-in', 'html5blank'),
			'desc'    => __(
				'The relationship doesn\'t end at the signature. We\'re alongside the family through the first ninety days — and most of our families still hear from us a year later.',
				'html5blank'
			),
			'bullets' => array(
				array(
					'h' => __('Transition support', 'html5blank'),
					'p' => __('We help with the move logistics, the paperwork, the small details that wear families down.', 'html5blank'),
				),
				array(
					'h' => __('Ongoing check-ins', 'html5blank'),
					'p' => __('If something feels off, we\'re the first call. If it\'s working, we celebrate with you.', 'html5blank'),
				),
			),
		),
	);
}

/**
 * Fallback “how we help” when not Assisted Living narrative: mirrors homepage Process ACF defaults.
 *
 * @return array<int, array{n:string,label:string,title:string,desc:string,bullets:list<array{h:string,p:string}>}>
 */
function md_care_type_fallback_help_steps_from_home_process(): array
{
	return md_front_page_process_steps();
}

/**
 * Stats from repeater rows.
 *
 * @param mixed             $rows
 * @param list<array{...}>  $defs
 *
 * @return list<array{n:string,num:string,lbl:string}>
 */
function md_care_type_parse_stats_rows($rows, array $defs): array
{
	if (! is_array($rows) || $rows === array()) {
		return array_values(array_map(static function ($d) {
			return array_merge(
				array( 'n' => '' ),
				$d
			);
		}, $defs));
	}
	$out = array();
	foreach ($rows as $row) {
		if (! is_array($row)) {
			continue;
		}
		$num = isset($row['care_tp_stat_num']) ? trim((string) $row['care_tp_stat_num']) : '';
		$lbl = isset($row['care_tp_stat_lbl']) ? trim((string) $row['care_tp_stat_lbl']) : '';
		if ($num === '' || $lbl === '') {
			continue;
		}
		$n = isset($row['care_tp_stat_n']) ? trim((string) $row['care_tp_stat_n']) : '';
		$out[] = array(
			'n'   => $n,
			'num' => $num,
			'lbl' => $lbl,
		);
	}
	return $out !== array() ? $out : md_care_type_parse_stats_rows(array(), $defs);
}

/**
 * @param mixed $rows
 * @param list<array<string,mixed>> $defs Pillars defaults with `_kses` keys.
 *
 * @return list<array{label:string,text:string,_kses:array}>
 */
function md_care_type_parse_pillar_rows($rows, array $defs): array
{
	if (! is_array($rows) || $rows === array()) {
		return $defs;
	}
	$m_em = md_care_type_m_allowed();
	$out  = array();
	foreach ($rows as $row) {
		if (! is_array($row)) {
			continue;
		}
		$lbl = isset($row['care_tp_mission_label']) ? trim((string) $row['care_tp_mission_label']) : '';
		$txt = isset($row['care_tp_mission_text']) ? trim((string) $row['care_tp_mission_text']) : '';
		if ($lbl === '' || $txt === '') {
			continue;
		}
		$out[] = array(
			'label' => $lbl,
			'text'  => $txt,
			'_kses' => $m_em,
		);
	}

	return count($out) >= 2 ? $out : $defs;
}

/**
 * @param mixed $rows
 * @param list<array{h:string,p:string}> $defs
 *
 * @return list<array{h:string,p:string}>
 */
function md_care_type_parse_facet_rows($rows, array $defs): array
{
	if (! is_array($rows) || $rows === array()) {
		return $defs;
	}
	$out = array();
	foreach ($rows as $row) {
		if (! is_array($row)) {
			continue;
		}
		$h = isset($row['care_tp_facet_heading']) ? trim((string) $row['care_tp_facet_heading']) : '';
		$p = isset($row['care_tp_facet_body']) ? trim((string) $row['care_tp_facet_body']) : '';
		if ($h === '' && $p === '') {
			continue;
		}
		$out[] = compact('h', 'p');
	}

	return $out !== array() ? $out : $defs;
}

/**
 * @param mixed $rows
 * @param list<array{n:string,h:string,p:string}> $defs
 *
 * @return list<array{n:string,h:string,p:string}>
 */
function md_care_type_parse_sign_rows($rows, array $defs): array
{
	if (! is_array($rows) || $rows === array()) {
		return $defs;
	}
	$incoming = array_values(array_filter($rows, 'is_array'));
	$out      = array();
	for ($i = 0; $i < count($defs); $i++) {
		$row = isset($incoming[ $i ]) ? $incoming[ $i ] : null;
		if (! is_array($row)) {
			$out[] = $defs[ $i ];
			continue;
		}
		$n = isset($row['care_tp_sign_num']) ? trim((string) $row['care_tp_sign_num']) : '';
		$h = isset($row['care_tp_sign_heading']) ? trim((string) $row['care_tp_sign_heading']) : '';
		$p = isset($row['care_tp_sign_body']) ? trim((string) $row['care_tp_sign_body']) : '';
		if ($h === '' || $p === '') {
			$out[] = $defs[ $i ];
			continue;
		}
		if ($n === '') {
			$n = $defs[ $i ]['n'];
		}
		$out[] = compact('n', 'h', 'p');
	}

	return $out;
}

/**
 * Mirrors {@see md_get_front_page_process_steps()} merge rules.
 *
 * @param mixed $rows
 *
 * @return array<int, array{n:string,label:string,title:string,desc:string,bullets:list<array{h:string,p:string}>}>
 */
function md_care_type_parse_help_steps_rows($rows, array $fallback): array
{
	if (! is_array($rows) || $rows === array()) {
		return $fallback;
	}
	$out = array();
	foreach ($rows as $row) {
		if (! is_array($row)) {
			continue;
		}
		$n     = isset($row['care_tp_step_n']) ? trim((string) $row['care_tp_step_n']) : '';
		$label = isset($row['care_tp_step_label']) ? trim((string) $row['care_tp_step_label']) : '';
		$title = isset($row['care_tp_step_title']) ? trim((string) $row['care_tp_step_title']) : '';
		$desc  = isset($row['care_tp_step_desc']) ? trim((string) $row['care_tp_step_desc']) : '';
		if ($title === '' && $desc === '' && $n === '' && $label === '') {
			continue;
		}
		$bullets = array();
		$brs     = isset($row['care_tp_step_bullets']) && is_array($row['care_tp_step_bullets']) ? $row['care_tp_step_bullets'] : array();
		foreach ($brs as $b) {
			if (! is_array($b)) {
				continue;
			}
			$h_b = isset($b['care_tp_bullet_heading']) ? trim((string) $b['care_tp_bullet_heading']) : '';
			$p_b = isset($b['care_tp_bullet_body']) ? trim((string) $b['care_tp_bullet_body']) : '';
			if ($h_b === '' && $p_b === '') {
				continue;
			}
			$bullets[] = array(
				'h' => $h_b,
				'p' => $p_b,
			);
		}
		if ($n === '') {
			$n = sprintf('%02d', count($out) + 1);
		}
		$out[] = array(
			'n'       => $n,
			'label'   => $label,
			'title'   => $title,
			'desc'    => $desc,
			'bullets' => $bullets,
		);
	}

	return $out !== array() ? $out : $fallback;
}

/**
 * Resolved content for {@see single-care_type.php}.
 *
 * @return array<string,mixed>
 */
function md_care_type_resolve(int $post_id): array
{
	$slug       = sanitize_title(get_post_field('post_name', $post_id));
	$level_card = md_care_type_level_card_for_slug($slug);
	$post_title = get_the_title($post_id);

	$is_assisted = ($slug === 'assisted-living');

	/* —— Defaults driven by Assisted Living standalone / generic hero for other CPT slugs —— */

	$s_def = md_care_type_default_stats_assisted();
	$h2_ok = array( 'span' => array( 'class' => true ) );

	if ($is_assisted) {
		$heroes = array(
			'back_url'           => md_care_type_archive_url(),
			'eyebrow'            => __('Care Types · Assisted Living', 'html5blank'),
			'h1_before'          => __('Daily support, ', 'html5blank'),
			'h1_em'              => __('delivered with dignity.', 'html5blank'),
			'h1_after'           => '',
			'subcopy'            => __(
				'Assisted living is for seniors who are mostly independent but need a hand with the rhythms of daily life. We help Vero Beach families find the community where Mom or Dad won\'t just be cared for — they\'ll be known.',
				'html5blank'
			),
			'hero_placeholder_lbl' => __('assisted living / morning porch with two friends', 'html5blank'),
			'quote_text'         => __('“She has friends now. She has a porch. We didn\'t know assisted living could feel this much like home.”', 'html5blank'),
			'quote_src'          => __('— The Halpern Family', 'html5blank'),
			'h2_allow'           => $h2_ok,
			'default_stats_rows' => $s_def,
		);

		$pillars_defs = md_care_type_default_pillars_assisted();

		$what = array(
			'eyebrow'     => __('What it is', 'html5blank'),
			'h2_before'   => __('A home, with help ', 'html5blank'),
			'h2_em'       => __('woven in', 'html5blank'),
			'h2_after'    => __('.', 'html5blank'),
			'placeholder' => __('assisted living / private apartment, late afternoon', 'html5blank'),
			'p1'          => __(
				'Most assisted living communities feel a lot like upscale apartment living. Residents have their own studio or one-bedroom, their own front door, their own things on the walls. What\'s different is the staff — caregivers who can step in for the few specific tasks that have become harder.',
				'html5blank'
			),
			'p2'          => __(
				'That might be a morning shower, an evening medication reminder, or help with the buttons on a favorite cardigan. The care plan is private, dignified, and built around what your loved one actually needs — nothing more, nothing less.',
				'html5blank'
			),
			'facet_defs' => md_care_type_default_facets_assisted(),
		);

		$sign_defs = md_care_type_default_signs_assisted();
		$signs_ep  = array(
			'eyebrow'   => __('Is it the right time?', 'html5blank'),
			'h2_before' => __('The signs we hear ', 'html5blank'),
			'h2_em'     => __('most often', 'html5blank'),
			'h2_after'  => __('.', 'html5blank'),
			'intro'     => __(
				'Families rarely arrive certain. They arrive worried — usually about a parent who\'s been getting by, but increasingly thinly. None of these alone mean it\'s time. Together, they often do.',
				'html5blank'
			),
		);

		$help_defs = md_care_type_default_help_steps_assisted();
		$help_hd   = array(
			'eyebrow'   => __('How we help', 'html5blank'),
			'h2_before' => __('A path through, ', 'html5blank'),
			'h2_em'     => __('not around', 'html5blank'),
			'h2_after'  => __('.', 'html5blank'),
			'intro'     => __(
				'Indian River County alone has more than two dozen assisted living communities. They vary enormously — in price, in feel, in what they\'re licensed to actually provide. Here\'s how we narrow it down with you.',
				'html5blank'
			),
		);

		$other = array(
			'eyebrow' => __('Other Levels of Care', 'html5blank'),
			'h2_bf'   => __('If you\'re not sure this is ', 'html5blank'),
			'h2_em'   => __('the right fit', 'html5blank'),
			'h2_af'   => __('.', 'html5blank'),
			'intro'   => __(
				'Most families come to us thinking they need one type of care, and discover another fits better. Here\'s the rest of the landscape.',
				'html5blank'
			),
		);
	} else {
		$label = ($level_card && $level_card['title'] !== '') ? $level_card['title'] : $post_title;
		/* translators: Care type section label (e.g. Memory Care). */
		$eyeb = sprintf(__('Care Types · %s', 'html5blank'), $label);
		$desc = ($level_card && $level_card['desc'] !== '') ? $level_card['desc'] : (
			wp_strip_all_tags((string) get_post_field('post_excerpt', $post_id))
		);

		if ($desc === '') {
			$desc = __(
				'Explore what this level of care involves in Indian River County — and reach out when you are ready for a calm, honest Discovery Session.',
				'html5blank'
			);
		}

		$tweak = __('Meaningful Direction helps Vero Beach families compare communities with empathy and candor—not sales pressure.', 'html5blank');

		$heroes = array(
			'back_url'             => md_care_type_archive_url(),
			'eyebrow'              => $eyeb,
			'h1_before'            => '',
			'h1_em'                => '' !== $post_title ? $post_title : $label,
			'h1_after'             => __(' — guiding families with candor.', 'html5blank'),
			'subcopy'              => trim($desc . ' ' . $tweak),
			/* translators: %s level of care title (fallback placeholder image label). */
			'hero_placeholder_lbl' => sprintf(__('%s / hero imagery', 'html5blank'), $label),
			'quote_text'           => __(
				'“Tell us where you are—we’ll translate the jargon, shorten the wait list, and help you exhale.”',
				'html5blank'
			),
			'quote_src'            => __('— Meaningful Direction', 'html5blank'),
			'h2_allow'             => $h2_ok,
			'default_stats_rows'   => $s_def,
		);

		$pillars_defs = md_care_type_default_pillars_generic($desc);

		$what_placeholder = sprintf(__('%s / supporting imagery', 'html5blank'), $label);

		$what = array(
			'eyebrow'     => __('What it looks like here', 'html5blank'),
			'h2_before'   => __('How we explain ', 'html5blank'),
			'h2_em'       => $label,
			'h2_after'    => __(' to families.', 'html5blank'),
			'placeholder' => $what_placeholder,
			'p1'          => $desc,
			'p2'          => trim((string) get_post_field('post_excerpt', $post_id)) !== ''
				? __('Extend the excerpt with fuller context in “What this is” paragraphs below—or in the Body field—for touring families.', 'html5blank')
				: __('Use the paragraphs below—or the Body field—to spell out timelines, cues, pricing context, or program nuances before tours.', 'html5blank'),
			'facet_defs'  => md_care_type_default_facets_assisted(),
		);

		$sign_defs = md_care_type_default_signs_assisted();
		$signs_ep  = array(
			'eyebrow'   => __('Questions families ask early', 'html5blank'),
			'h2_before' => __('You don\'t have to arrive ', 'html5blank'),
			'h2_em'     => __('certain', 'html5blank'),
			'h2_after'  => __('.', 'html5blank'),
			'intro'     => __(
				'These aren’t diagnoses—only signposts caregivers mention when calendars, emotions, or safety begin to fray. Conversation still beats checklist.',
				'html5blank'
			),
		);

		$help_defs = md_care_type_fallback_help_steps_from_home_process();
		$help_hd   = array(
			'eyebrow'   => __('How we help', 'html5blank'),
			'h2_before' => __('Grounded guidance for ', 'html5blank'),
			'h2_em'     => $label,
			'h2_after'  => __('.', 'html5blank'),
			'intro'     => __(
				'We hold the statewide licensure quirks, nightly staffing realities, and family dynamics in focus while you steer what happens next.',
				'html5blank'
			),
		);

		$other = array(
			'eyebrow' => __('Other Levels of Care', 'html5blank'),
			'h2_bf'   => __('If you\'re not sure this is ', 'html5blank'),
			'h2_em'   => __('the right fit', 'html5blank'),
			'h2_af'   => __('.', 'html5blank'),
			'intro'   => __(
				'Jump between levels—then come back anytime for a facilitated comparison.',
				'html5blank'
			),
		);
	}

	/** @var mixed $acf_stats */
	$acf_stats = function_exists('get_field') ? get_field('care_tp_hero_stats', $post_id) : null;

	/** @var mixed $pill_rows */
	$pill_rows = function_exists('get_field') ? get_field('care_tp_mission_strip', $post_id) : null;

	/** @var mixed $facet_rows */
	$facet_rows = function_exists('get_field') ? get_field('care_tp_what_facets', $post_id) : null;

	/** @var mixed $sign_rows */
	$sign_rows = function_exists('get_field') ? get_field('care_tp_signs_grid', $post_id) : null;

	/** @var mixed $step_rows */
	$step_rows = function_exists('get_field') ? get_field('care_tp_help_steps', $post_id) : null;

	$hero_image_raw = function_exists('get_field') ? get_field('care_tp_hero_image', $post_id) : null;
	if (! is_array($hero_image_raw)) {
		$hero_image_raw = array();
	}
	$what_image_raw = function_exists('get_field') ? get_field('care_tp_what_image', $post_id) : null;
	if (! is_array($what_image_raw)) {
		$what_image_raw = array();
	}

	return array_merge(
		array(
			'post_id'         => $post_id,
			'slug'            => $slug,
			'is_assisted_tpl' => $is_assisted,
			'h2_allow'       => $h2_ok,
			'h2_plain_allow' => array( 'br' => array() ),

			'back_url'        => md_get_care_type_field('care_tp_back_url', $heroes['back_url'], $post_id),
			'hero_image'      => $hero_image_raw,
			'what_image'      => $what_image_raw,

			'hero_eyebrow'           => md_get_care_type_field('care_tp_hero_eyebrow', $heroes['eyebrow'], $post_id),
			'hero_headline_before'   => md_get_care_type_field('care_tp_hero_h1_before', $heroes['h1_before'], $post_id),
			'hero_headline_emphasis' => md_get_care_type_field('care_tp_hero_h1_emphasis', $heroes['h1_em'], $post_id),
			'hero_headline_after'    => md_get_care_type_field('care_tp_hero_h1_after', $heroes['h1_after'], $post_id),
			'hero_subcopy'           => md_get_care_type_field('care_tp_hero_subcopy', $heroes['subcopy'], $post_id),
			'hero_placeholder'      => md_get_care_type_field('care_tp_hero_placeholder_label', $heroes['hero_placeholder_lbl'], $post_id),

			'hero_quote_text'       => md_get_care_type_field('care_tp_hero_quote', $heroes['quote_text'], $post_id),
			'hero_quote_src'       => md_get_care_type_field('care_tp_hero_quote_src', $heroes['quote_src'], $post_id),

			'stats'               => md_care_type_parse_stats_rows($acf_stats, $heroes['default_stats_rows']),
			'pillars'             => md_care_type_parse_pillar_rows($pill_rows, $pillars_defs),
			'signs_intro'        => md_get_care_type_field('care_tp_signs_intro', $signs_ep['intro'], $post_id),
			'sign_items'          => md_care_type_parse_sign_rows($sign_rows, $sign_defs),
			'signs_head'          => array(
				'eyebrow'   => md_get_care_type_field('care_tp_signs_eyebrow', $signs_ep['eyebrow'], $post_id),
				'h2_before' => md_get_care_type_field('care_tp_signs_h2_before', $signs_ep['h2_before'], $post_id),
				'h2_em'     => md_get_care_type_field('care_tp_signs_h2_emphasis', $signs_ep['h2_em'], $post_id),
				'h2_after'  => md_get_care_type_field('care_tp_signs_h2_after', $signs_ep['h2_after'], $post_id),
			),

			'what' => array_merge(
				$what,
				array(
					'eyebrow'           => md_get_care_type_field('care_tp_what_eyebrow', $what['eyebrow'], $post_id),
					'h2_before'         => md_get_care_type_field('care_tp_what_h2_before', $what['h2_before'], $post_id),
					'h2_em'            => md_get_care_type_field('care_tp_what_h2_emphasis', $what['h2_em'], $post_id),
					'h2_after'         => md_get_care_type_field('care_tp_what_h2_after', $what['h2_after'], $post_id),
					'placeholder'      => md_get_care_type_field('care_tp_what_placeholder_label', $what['placeholder'], $post_id),
					'p1'               => md_get_care_type_field('care_tp_what_p1', $what['p1'], $post_id),
					'p2'               => md_get_care_type_field('care_tp_what_p2', $what['p2'], $post_id),
					'facets'           => md_care_type_parse_facet_rows($facet_rows, $what['facet_defs']),
				)
			),

			'help_intro'       => md_get_care_type_field('care_tp_help_intro', $help_hd['intro'], $post_id),
			'help_head'       => array(
				'eyebrow'   => md_get_care_type_field('care_tp_help_eyebrow', $help_hd['eyebrow'], $post_id),
				'h2_before' => md_get_care_type_field('care_tp_help_h2_before', $help_hd['h2_before'], $post_id),
				'h2_em'     => md_get_care_type_field('care_tp_help_h2_emphasis', $help_hd['h2_em'], $post_id),
				'h2_after'  => md_get_care_type_field('care_tp_help_h2_after', $help_hd['h2_after'], $post_id),
			),

			'help_steps'        => md_care_type_parse_help_steps_rows($step_rows, $help_defs),

			'other_eyebrow'     => md_get_care_type_field('care_tp_other_eyebrow', $other['eyebrow'], $post_id),
			'other_h2_bf'       => md_get_care_type_field('care_tp_other_h2_before', $other['h2_bf'], $post_id),
			'other_h2_em'       => md_get_care_type_field('care_tp_other_h2_emphasis', $other['h2_em'], $post_id),
			'other_h2_af'       => md_get_care_type_field('care_tp_other_h2_after', $other['h2_af'], $post_id),
			'other_intro'       => md_get_care_type_field('care_tp_other_intro', $other['intro'], $post_id),
		)
	);
}

/**
 * Front-page care grid row matched by CPT {@see post_name} (same sanitization as homepage card titles).
 *
 * @return array{icon:string,desc:string}|null
 */
function md_care_archive_match_front_level_by_slug(string $slug): ?array
{
	if (!function_exists('md_get_front_page_care_levels')) {
		return null;
	}
	$needle = sanitize_title($slug);
	if ($needle === '') {
		return null;
	}
	foreach (md_get_front_page_care_levels() as $lvl) {
		if (!is_array($lvl)) {
			continue;
		}
		$title = isset($lvl['title']) ? trim((string) $lvl['title']) : '';
		if ($title === '') {
			continue;
		}
		if (sanitize_title($title) !== $needle) {
			continue;
		}
		$icon = isset($lvl['icon']) ? trim((string) $lvl['icon']) : '';
		if ($icon === '' || !in_array($icon, md_front_page_care_icons(), true)) {
			$icon = 'heart';
		}
		$desc = isset($lvl['desc']) ? trim((string) $lvl['desc']) : '';

		return array(
			'icon' => $icon,
			'desc' => $desc,
		);
	}

	return null;
}

/** Icon slug for care-type archive cards ({@see md_render_care_icon()}). */
function md_care_archive_card_icon(int $post_id): string
{
	$slug = (string) get_post_field('post_name', $post_id, 'raw');
	$m    = md_care_archive_match_front_level_by_slug($slug);

	return ($m !== null && $m['icon'] !== '') ? $m['icon'] : 'heart';
}

/** Teaser copy for archive cards — homepage care blurb first, then manual excerpt. */
function md_care_archive_card_blurb(int $post_id): string
{
	$slug = (string) get_post_field('post_name', $post_id, 'raw');
	$m    = md_care_archive_match_front_level_by_slug($slug);
	if ($m !== null && $m['desc'] !== '') {
		return $m['desc'];
	}
	if (has_excerpt($post_id)) {
		$raw = (string) get_post_field('post_excerpt', $post_id);
		if ($raw !== '') {
			return wp_strip_all_tags($raw);
		}
	}

	return __('Learn how our advocates guide Indian River County families through this level of care.', 'html5blank');
}
