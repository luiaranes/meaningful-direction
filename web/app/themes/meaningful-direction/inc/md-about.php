<?php
/**
 * About page — defaults + optional ACF (page template page-about.php).
 */

/**
 * Fetch ACF field for current About page.
 *
 * @param int $page_id WP page ID.
 */
function md_get_about_field(string $name, $fallback = '', int $page_id = 0)
{
	if (!function_exists('get_field')) {
		return $fallback;
	}
	if (!$page_id) {
		return $fallback;
	}
	$value = get_field($name, $page_id);
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

/**
 * @return list<array{n:string,h:string,p:string}>
 */
function md_about_default_values(): array
{
	return array(
		array(
			'n' => '01',
			'h' => __('Family first, always', 'html5blank'),
			'p' => __('We sit at your kitchen table before we send you a single brochure. The decision belongs to your family — our job is to make it a clear one.', 'html5blank'),
		),
		array(
			'n' => '02',
			'h' => __('No fees to families', 'html5blank'),
			'p' => __('Our service is free to you, ever and always. We are paid by communities only when a placement is made, and never in a way that compromises our recommendation.', 'html5blank'),
		),
		array(
			'n' => '03',
			'h' => __('Honest about every option', 'html5blank'),
			'p' => __('We will tell you which communities to skip, and why. Glossy brochures and renovated lobbies aren\'t enough — we know which night staff actually answer call buttons.', 'html5blank'),
		),
		array(
			'n' => '04',
			'h' => __('Local, walked, known', 'html5blank'),
			'p' => __('We\'ve personally walked every assisted living, memory care, and skilled nursing community in Indian River County. Our knowledge is feet-on-the-floor, not website-deep.', 'html5blank'),
		),
	);
}

/**
 * @param mixed $rows ACF repeater rows or false.
 *
 * @return list<array{n:string,h:string,p:string}>
 */
function md_about_parse_values_rows($rows, int $post_id): array
{
	$defs = md_about_default_values();
	if (!is_array($rows) || $rows === array()) {
		return $defs;
	}
	$out      = array();
	$incoming = array_values(array_filter($rows, 'is_array'));
	for ($i = 0; $i < count($defs); $i++) {
		$row = isset($incoming[$i]) ? $incoming[$i] : null;
		if (!is_array($row)) {
			$out[] = $defs[ $i ];
			continue;
		}
		$n = trim((string) ($row['about_value_num'] ?? ''));
		$h = trim((string) ($row['about_value_heading'] ?? ''));
		$p = trim((string) ($row['about_value_body'] ?? ''));
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
 * @return list<array{name:string,role:string,cred:string,bio:string,image_id:int,image_label:string}>
 */
function md_about_default_team(): array
{
	return array(
		array(
			'name'         => __('Caroline Halpern', 'html5blank'),
			'role'         => __('Founder & Senior Advocate', 'html5blank'),
			'cred'         => __('CSA · Certified Senior Advisor', 'html5blank'),
			'bio'          => __('After placing her own father in a Vero Beach memory care community in 2014, Caroline founded Meaningful Direction to give other families the unhurried, deeply local guidance she wished she\'d had. She has personally walked more than two hundred families through the search.', 'html5blank'),
			'image_id'     => 0,
			'image_label'  => __('portrait / Caroline at her desk', 'html5blank'),
		),
		array(
			'name'         => __('James Okafor', 'html5blank'),
			'role'         => __('Senior Advocate', 'html5blank'),
			'cred'         => __('RN · Geriatric Nursing', 'html5blank'),
			'bio'          => __('James spent twelve years as a charge nurse at a skilled nursing facility before joining the team. He brings clinical fluency to family conversations — translating discharge orders, medication changes, and care levels into plain language.', 'html5blank'),
			'image_id'     => 0,
			'image_label'  => __('portrait / James in clinic hallway', 'html5blank'),
		),
		array(
			'name'         => __('Renée Castillo', 'html5blank'),
			'role'         => __('Family Liaison', 'html5blank'),
			'cred'         => __('BSW · Licensed Social Worker', 'html5blank'),
			'bio'          => __('Renée is often the first voice families hear. A Vero Beach native, she has a gift for the early conversation — when nothing has been decided and everything still feels overwhelming.', 'html5blank'),
			'image_id'     => 0,
			'image_label'  => __('portrait / Renée on the porch', 'html5blank'),
		),
	);
}

/**
 * @return list<array{name:string,role:string,cred:string,bio:string,image_id:int,image_label:string}>
 */
function md_about_parse_team_rows($rows, int $post_id): array
{
	$defs = md_about_default_team();
	if (!is_array($rows) || $rows === array()) {
		return $defs;
	}
	$out      = array();
	$incoming = array_values(array_filter($rows, 'is_array'));
	for ($i = 0; $i < count($defs); $i++) {
		$row = isset($incoming[$i]) ? $incoming[$i] : null;
		if (!is_array($row)) {
			$out[] = $defs[ $i ];
			continue;
		}
		$name = trim((string) ($row['about_team_name'] ?? ''));
		$role = trim((string) ($row['about_team_role'] ?? ''));
		$cred = trim((string) ($row['about_team_cred'] ?? ''));
		$bio  = trim((string) ($row['about_team_bio'] ?? ''));
		if ($name === '' || $role === '' || $bio === '') {
			$out[] = $defs[ $i ];
			continue;
		}
		if ($cred === '') {
			$cred = $defs[ $i ]['cred'];
		}
		$image_id = 0;
		if (!empty($row['about_team_photo']) && is_array($row['about_team_photo']) && !empty($row['about_team_photo']['ID'])) {
			$image_id = (int) $row['about_team_photo']['ID'];
		}
		$img_lbl_raw = isset($row['about_team_photo_label']) ? trim((string) $row['about_team_photo_label']) : '';
		$image_label = $img_lbl_raw !== '' ? $img_lbl_raw : $defs[ $i ]['image_label'];
		$out[] = array(
			'name'        => $name,
			'role'        => $role,
			'cred'        => $cred,
			'bio'         => $bio,
			'image_id'    => $image_id,
			'image_label' => $image_label,
		);
	}

	return $out;
}

/**
 * @return list<array{yr:string,h:string,p:string}>
 */
function md_about_default_timeline(): array
{
	return array(
		array(
			'yr' => '2014',
			'h'  => __("A daughter's search becomes a calling", 'html5blank'),
			'p'  => __('Caroline navigates her father\'s move into memory care in Vero Beach. The lack of unbiased, family-side guidance plants the seed for what comes next.', 'html5blank'),
		),
		array(
			'yr' => '2017',
			'h'  => __('Meaningful Direction opens its doors', 'html5blank'),
			'p'  => __('Founded as a family-first placement service in Indian River County, with a single principle: no fees to families, ever.', 'html5blank'),
		),
		array(
			'yr' => '2020',
			'h'  => __('Expanding the team', 'html5blank'),
			'p'  => __('James joins as the team\'s first nurse advocate, deepening the clinical fluency we bring to every consultation.', 'html5blank'),
		),
		array(
			'yr' => '2023',
			'h'  => __('200 families served', 'html5blank'),
			'p'  => __('We celebrate two hundred Vero Beach families placed — and most of them still hear from us a year later.', 'html5blank'),
		),
		array(
			'yr' => '2026',
			'h'  => __('Where we are now', 'html5blank'),
			'p'  => __('Three advocates. Twenty-five-plus vetted communities across Indian River County. The same kitchen-table conversation we started with.', 'html5blank'),
		),
	);
}

/**
 * @return list<array{yr:string,h:string,p:string}>
 */
function md_about_parse_timeline_rows($rows, int $post_id): array
{
	$defs = md_about_default_timeline();
	if (!is_array($rows) || $rows === array()) {
		return $defs;
	}
	$out      = array();
	$incoming = array_values(array_filter($rows, 'is_array'));
	for ($i = 0; $i < count($defs); $i++) {
		$row = isset($incoming[$i]) ? $incoming[$i] : null;
		if (!is_array($row)) {
			$out[] = $defs[ $i ];
			continue;
		}
		$yr = trim((string) ($row['about_timeline_year'] ?? ''));
		$h  = trim((string) ($row['about_timeline_heading'] ?? ''));
		$p  = trim((string) ($row['about_timeline_body'] ?? ''));
		if ($yr === '' || $h === '' || $p === '') {
			$out[] = $defs[ $i ];
			continue;
		}
		$out[] = compact('yr', 'h', 'p');
	}

	return $out;
}

/** Digit-only string for tel: href. */
function md_sanitize_tel_digits(string $phone): string
{
	return preg_replace('/\D+/', '', $phone) ?? '';
}

/**
 * Builds `tel:+1…` URI from display/prototype phone strings.
 *
 * Falls back empty string only when no digits.
 */
function md_format_tel_href(string $phone): string
{
	$digits = md_sanitize_tel_digits($phone);
	if ($digits === '') {
		return '';
	}
	if ($digits[0] === '1' && strlen($digits) === 11) {
		return 'tel:+1' . substr($digits, 1);
	}
	if (strlen($digits) === 10) {
		return 'tel:+1' . $digits;
	}

	return 'tel:+' . ltrim($digits, '+');
}

/** Whether hero shows on About (mirrors hero_enable semantics on front page if unset). */
function md_about_hero_is_enabled(int $post_id = 0): bool
{
	if (!$post_id) {
		$post_id = (int) get_the_ID();
	}
	if (!$post_id) {
		return true;
	}
	if (!function_exists('get_field')) {
		return true;
	}
	$raw = get_field('about_hero_enable', $post_id);
	if ($raw === null || $raw === '') {
		return true;
	}

	return (bool) filter_var($raw, FILTER_VALIDATE_BOOLEAN);
}
