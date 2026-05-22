<?php
/**
 * Contact page (slug contact-us) — ACF + defaults (prototype contact.jsx → PHP).
 *
 * @package meaningful-direction
 */

if (!defined('ABSPATH')) {
	exit;
}

/**
 * @param int $page_id WP page ID.
 */
function md_get_contact_page_field(string $name, $fallback = '', int $page_id = 0)
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
 * @return list<array{q:string,a:string}>
 */
function md_contact_default_faq_rows(): array
{
	return array(
		array(
			'q' => __('Is there a cost for your service?', 'html5blank'),
			'a' => __('No, ever. Our service is free to families. We are paid by communities only when a placement is made — and never in a way that compromises our recommendation. If a community we\'d recommend doesn\'t have a referral arrangement with us, we still recommend it.', 'html5blank'),
		),
		array(
			'q' => __('What happens on the first call?', 'html5blank'),
			'a' => __('We listen. There is no script and no sales pitch. Most first calls last twenty to thirty minutes — we ask about your loved one\'s day, what\'s changed recently, and what worries you most. By the end, you\'ll know whether we\'re the right next step. If we\'re not, we\'ll tell you who is.', 'html5blank'),
		),
		array(
			'q' => __('How quickly can you help?', 'html5blank'),
			'a' => __('Most families hear from an advocate within one business day. For urgent situations — a hospital discharge, a sudden fall, a memory care safety concern — we can usually be at your kitchen table or on a video call the same day. Just say the word in your message.', 'html5blank'),
		),
		array(
			'q' => __('Do we have to know what kind of care we need?', 'html5blank'),
			'a' => __('Not at all. Most families don\'t, and many discover they need something different from what they assumed. Part of our job is helping you understand the difference between independent living, assisted living, memory care, and skilled nursing — and which fits today, and which to plan for tomorrow.', 'html5blank'),
		),
		array(
			'q' => __('Where do you serve?', 'html5blank'),
			'a' => __('Indian River County, Florida — primarily Vero Beach, Sebastian, Indian River Shores, Wabasso, and Roseland. If your loved one lives out of state but is moving to be near family in our area, we can absolutely help with that too.', 'html5blank'),
		),
	);
}

/**
 * @param mixed $rows ACF repeater.
 *
 * @return list<array{q:string,a:string}>
 */
function md_contact_parse_faq_rows($rows, int $page_id): array
{
	$defs = md_contact_default_faq_rows();
	if (!is_array($rows) || $rows === array()) {
		return $defs;
	}
	$incoming = array_values(array_filter($rows, 'is_array'));
	$out      = array();
	foreach ($incoming as $row) {
		$q = trim((string) ($row['contact_pg_faq_q'] ?? ''));
		$a = trim((string) ($row['contact_pg_faq_a'] ?? ''));
		if ($q === '' || $a === '') {
			continue;
		}
		$out[] = compact('q', 'a');
	}

	return $out !== array() ? $out : $defs;
}

/**
 * @return list<array{n:string,title:string,body:string,href_raw:string,link_title:string,target:string}>
 */
function md_contact_default_way_rows(): array
{
	$phone_disp = md_get_home_hero_field('contact_phone', '(772) 555-0142');
	$tel_href   = md_contact_tel_href($phone_disp);
	if ($tel_href === '#') {
		$tel_href = 'tel:7725550142';
	}
	$call_lbl = sprintf(
		/* translators: %s: phone number for display */
		__('Call %s', 'html5blank'),
		$phone_disp
	);

	return array(
		array(
			'n'          => '01',
			'title'      => __('A short phone call', 'html5blank'),
			'body'       => __('Twenty minutes, no script. We listen first, then ask a few questions. By the end, you\'ll know whether we\'re the right next step.', 'html5blank'),
			'href_raw'   => $tel_href,
			'link_title' => $call_lbl,
			'target'     => '',
		),
		array(
			'n'          => '02',
			'title'      => __('A kitchen-table visit', 'html5blank'),
			'body'       => __('For families in Vero Beach, Sebastian, or Indian River Shores. We\'ll come to you — no charge, no obligation, no brochures left behind.', 'html5blank'),
			'href_raw'   => '#form',
			'link_title' => __('Request a home visit', 'html5blank'),
			'target'     => '',
		),
		array(
			'n'          => '03',
			'title'      => __('A note when ready', 'html5blank'),
			'body'       => __('Not ready to talk yet? Send a few details using the form. We\'ll write back, not call, so you can read at your own pace.', 'html5blank'),
			'href_raw'   => '#form',
			'link_title' => __('Use the form', 'html5blank'),
			'target'     => '',
		),
	);
}

/**
 * @param mixed $rows ACF repeater.
 *
 * @return list<array{n:string,title:string,body:string,href_raw:string,link_title:string,target:string}>
 */
function md_contact_parse_way_rows($rows, int $page_id): array
{
	$defs = md_contact_default_way_rows();
	if (!is_array($rows) || $rows === array()) {
		return $defs;
	}
	$incoming = array_values(array_filter($rows, 'is_array'));
	$out      = array();
	foreach ($incoming as $row) {
		$n = trim((string) ($row['contact_pg_way_num'] ?? ''));
		$title = trim((string) ($row['contact_pg_way_title'] ?? ''));
		$body = trim((string) ($row['contact_pg_way_body'] ?? ''));
		$link_raw = isset($row['contact_pg_way_link']) ? $row['contact_pg_way_link'] : array();
		$norm = md_hero_normalize_link(
			is_array($link_raw) ? $link_raw : array(),
			__('Learn more', 'html5blank'),
			'form'
		);
		if ($title === '' || $body === '') {
			continue;
		}
		if ($n === '') {
			$n = (string) (count($out) + 1);
		}
		$out[] = array(
			'n'          => $n,
			'title'      => $title,
			'body'       => $body,
			'href_raw'   => $norm['href_raw'],
			'link_title' => $norm['title'],
			'target'     => $norm['target'],
		);
	}

	return $out !== array() ? $out : $defs;
}

/**
 * @return list<string>
 */
function md_contact_default_area_places(): array
{
	return array(
		'Vero Beach',
		'Indian River Shores',
		'Sebastian',
		'Wabasso',
		'Roseland',
		'Fellsmere',
		'Orchid',
		'Winter Beach',
	);
}

/**
 * @param mixed $rows ACF repeater.
 *
 * @return list<string>
 */
function md_contact_parse_area_places($rows): array
{
	$defs = md_contact_default_area_places();
	if (!is_array($rows) || $rows === array()) {
		return $defs;
	}
	$out = array();
	foreach ($rows as $row) {
		if (!is_array($row)) {
			continue;
		}
		$name = trim((string) ($row['contact_pg_area_place_name'] ?? ''));
		if ($name !== '') {
			$out[] = $name;
		}
	}

	return $out !== array() ? $out : $defs;
}

add_filter(
	'body_class',
	function (array $classes): array {
		if (is_page('contact-us')) {
			$classes[] = 'md-site-contact-page';
		}

		return $classes;
	}
);
