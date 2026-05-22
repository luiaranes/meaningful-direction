<?php
/**
 * Our Process — tab steps (prototype components.jsx STEPS).
 */
function md_front_page_process_steps(): array
{
    return array(
        array(
            'n'     => '01',
            'label' => __('Begin', 'html5blank'),
            'title' => __('Personalized Consultation & Discovery', 'html5blank'),
            'desc'  => __(
                'Connect with a Senior Advocate Professional for a deep-dive Discovery Session. We don\'t just look at medical charts — we look at the whole person.',
                'html5blank'
            ),
            'bullets' => array(
                array(
                    'h' => __('Needs Assessment', 'html5blank'),
                    'p' => __(
                        'We discuss clinical requirements, budget, and the desired level of independence — without assumptions.',
                        'html5blank'
                    ),
                ),
                array(
                    'h' => __('The “Vero Lifestyle” Match', 'html5blank'),
                    'p' => __(
                        'Personal interests matter. Proximity to the beach, social clubs, spiritual communities — we weigh the social and emotional fit alongside the clinical.',
                        'html5blank'
                    ),
                ),
            ),
        ),
        array(
            'n'     => '02',
            'label' => __('Curate', 'html5blank'),
            'title' => __('Tailored Option Curation', 'html5blank'),
            'desc'  => __(
                'We cut through the complexity of Indian River County\'s senior living landscape to present only the most viable, personalized matches.',
                'html5blank'
            ),
            'bullets' => array(
                array(
                    'h' => __('Care Alignment', 'html5blank'),
                    'p' => __(
                        'We narrow options based on care type — Independent Living, Assisted Living, Memory Care, or Skilled Nursing.',
                        'html5blank'
                    ),
                ),
                array(
                    'h' => __('Amenity Mapping', 'html5blank'),
                    'p' => __(
                        'We compare lifestyle offerings — from dining programs and wellness centers to specialized memory care programming.',
                        'html5blank'
                    ),
                ),
                array(
                    'h' => __('Initial Vetting', 'html5blank'),
                    'p' => __(
                        'We verify current availability and licensing status, so you never waste an afternoon on a dead-end option.',
                        'html5blank'
                    ),
                ),
            ),
        ),
        array(
            'n'     => '03',
            'label' => __('Tour', 'html5blank'),
            'title' => __('Guided Tours & Honest Comparison', 'html5blank'),
            'desc'  => __(
                'We walk every hallway with you. Real questions, real answers, and the side-by-side comparison sheet families actually need.',
                'html5blank'
            ),
            'bullets' => array(
                array(
                    'h' => __('Accompanied Visits', 'html5blank'),
                    'p' => __(
                        'Your advocate joins each tour to ask the questions families don\'t think to ask in the moment.',
                        'html5blank'
                    ),
                ),
                array(
                    'h' => __('Side-by-Side Notes', 'html5blank'),
                    'p' => __(
                        'A clear written summary of every community — not a brochure, a comparison.',
                        'html5blank'
                    ),
                ),
            ),
        ),
        array(
            'n'     => '04',
            'label' => __('Settle', 'html5blank'),
            'title' => __('Move-in Support & Continued Advocacy', 'html5blank'),
            'desc'  => __(
                'The relationship doesn\'t end at the signature. We\'re alongside the family through the first 90 days — and beyond.',
                'html5blank'
            ),
            'bullets' => array(
                array(
                    'h' => __('Transition Coordination', 'html5blank'),
                    'p' => __(
                        'We help with paperwork, move logistics, and the small details that wear families down.',
                        'html5blank'
                    ),
                ),
                array(
                    'h' => __('Continued Check-ins', 'html5blank'),
                    'p' => __(
                        'Regular follow-ups in the first months ensure the placement remains the right one.',
                        'html5blank'
                    ),
                ),
            ),
        ),
    );
}

/**
 * ACF process_steps repeater → same shape as md_front_page_process_steps(); empty/invalid → defaults.
 *
 * @return array<int, array{n:string,label:string,title:string,desc:string,bullets:array<int, array{h:string,p:string}>}>
 */
function md_get_front_page_process_steps(): array
{
    $defaults = md_front_page_process_steps();
    if (!function_exists('get_field')) {
        return $defaults;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return $defaults;
    }
    $rows = get_field('process_steps', $page_id);
    if (!is_array($rows) || $rows === array()) {
        return $defaults;
    }

    $out = array();
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $n     = isset($row['step_n']) ? trim((string) $row['step_n']) : '';
        $label = isset($row['step_label']) ? trim((string) $row['step_label']) : '';
        $title = isset($row['step_title']) ? trim((string) $row['step_title']) : '';
        $desc  = isset($row['step_desc']) ? trim((string) $row['step_desc']) : '';
        if ($title === '' && $desc === '' && $n === '' && $label === '') {
            continue;
        }

        $bullets = array();
        $brs     = isset($row['step_bullets']) && is_array($row['step_bullets']) ? $row['step_bullets'] : array();
        foreach ($brs as $b) {
            if (!is_array($b)) {
                continue;
            }
            $h = isset($b['bullet_heading']) ? trim((string) $b['bullet_heading']) : '';
            $p = isset($b['bullet_body']) ? trim((string) $b['bullet_body']) : '';
            if ($h === '' && $p === '') {
                continue;
            }
            $bullets[] = array(
                'h' => $h,
                'p' => $p,
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

    if ($out === array()) {
        return $defaults;
    }

    return $out;
}
