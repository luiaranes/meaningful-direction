<?php
/**
 * Levels of care — grid cards (prototype components.jsx CARE).
 */
function md_front_page_care_levels(): array
{
    return array(
        array(
            'id'    => 'care-independent',
            'icon'  => 'sun',
            'title' => __('Independent Living', 'html5blank'),
            'desc'  => __('Active, social communities for seniors who want connection and amenities without the upkeep of a home.', 'html5blank'),
        ),
        array(
            'id'    => 'care-assisted',
            'icon'  => 'heart',
            'title' => __('Assisted Living', 'html5blank'),
            'desc'  => __('Daily support with bathing, dressing, and medication — delivered with dignity, not disruption.', 'html5blank'),
        ),
        array(
            'id'    => 'care-memory',
            'icon'  => 'brain',
            'title' => __('Memory Care', 'html5blank'),
            'desc'  => __('Specialized, secure programming for loved ones living with Alzheimer\'s or other forms of dementia.', 'html5blank'),
        ),
        array(
            'id'    => 'care-skilled',
            'icon'  => 'cross',
            'title' => __('Skilled Nursing', 'html5blank'),
            'desc'  => __('24-hour clinical care for seniors with complex medical needs or recovering from a hospital stay.', 'html5blank'),
        ),
    );
}

/** @return list<string> */
function md_front_page_care_icons(): array
{
    return array( 'sun', 'heart', 'brain', 'cross' );
}

/**
 * ACF care_cards repeater → same shape as md_front_page_care_levels(); empty → defaults.
 *
 * @return array<int, array{id:string,icon:string,title:string,desc:string}>
 */
function md_get_front_page_care_levels(): array
{
    $defaults = md_front_page_care_levels();
    if (!function_exists('get_field')) {
        return $defaults;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return $defaults;
    }
    $rows = get_field('care_cards', $page_id);
    if (!is_array($rows) || $rows === array()) {
        return $defaults;
    }

    $allowed = array_flip( md_front_page_care_icons() );
    $out       = array();
    $n         = 0;
    foreach ($rows as $row ) {
        if (!is_array($row)) {
            continue;
        }
        $title = isset($row['care_card_title']) ? trim((string) $row['care_card_title']) : '';
        $desc  = isset($row['care_card_desc']) ? trim((string) $row['care_card_desc']) : '';
        if ($title === '') {
            continue;
        }
        $icon_raw = isset($row['care_card_icon']) ? (string) $row['care_card_icon'] : 'sun';
        $icon     = isset($allowed[ $icon_raw ]) ? $icon_raw : 'sun';

        $id_raw = isset($row['care_card_id']) ? trim((string) $row['care_card_id']) : '';
        if ($id_raw !== '') {
            $id = sanitize_title( $id_raw );
        } else {
            $id = sanitize_title( $title );
        }
        if ($id === '') {
            ++$n;
            $id = 'care-level-' . $n;
        }
        if (strpos($id, 'care-') !== 0) {
            $id = 'care-' . $id;
        }

        $out[] = array(
            'id'    => $id,
            'icon'  => $icon,
            'title' => $title,
            'desc'  => $desc,
        );
    }

    if ($out === array()) {
        return $defaults;
    }

    return $out;
}

/**
 * Echo inline SVG care icon (sage line icons, matches prototype).
 */
function md_render_care_icon(string $slug): void
{
    switch ($slug) {
        case 'sun':
            ?>
<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
	<circle cx="18" cy="18" r="6" />
	<line x1="18" y1="3" x2="18" y2="7" /><line x1="18" y1="29" x2="18" y2="33" />
	<line x1="3" y1="18" x2="7" y2="18" /><line x1="29" y1="18" x2="33" y2="18" />
	<line x1="7.5" y1="7.5" x2="10" y2="10" /><line x1="26" y1="26" x2="28.5" y2="28.5" />
	<line x1="7.5" y1="28.5" x2="10" y2="26" /><line x1="26" y1="10" x2="28.5" y2="7.5" />
</svg>
			<?php
            break;
        case 'heart':
            ?>
<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
	<path d="M18 31s-12-7-12-15a7 7 0 0 1 12-5 7 7 0 0 1 12 5c0 8-12 15-12 15z" />
</svg>
			<?php
            break;
        case 'brain':
            ?>
<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
	<path d="M14 6a4 4 0 0 0-4 4v0a4 4 0 0 0-4 4 4 4 0 0 0 2 3.5V20a4 4 0 0 0 4 4v3a4 4 0 0 0 6 1V8a4 4 0 0 0-4-2z" />
	<path d="M22 6a4 4 0 0 1 4 4v0a4 4 0 0 1 4 4 4 4 0 0 1-2 3.5V20a4 4 0 0 1-4 4v3a4 4 0 0 1-6 1V8a4 4 0 0 1 4-2z" />
</svg>
			<?php
            break;
        case 'cross':
            ?>
<svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 36 36" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
	<rect x="6" y="6" width="24" height="24" rx="2" />
	<line x1="18" y1="12" x2="18" y2="24" /><line x1="12" y1="18" x2="24" y2="18" />
</svg>
			<?php
            break;
        default:
            break;
    }
}
