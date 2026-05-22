<?php
/**
 * Family stories — carousel (prototype components.jsx STORIES / Testimonials).
 */

/**
 * Normalize ACF image field (array or attachment ID).
 */
function md_story_acf_attachment_id( $field ): int
{
    if (is_numeric( $field )) {
        return (int) $field;
    }
    if ( is_array( $field ) && isset( $field['ID'] ) ) {
        return (int) $field['ID'];
    }

    return 0;
}

/**
 * Two-letter initials for testimonial avatar placeholder (Unicode-safe).
 */
function md_story_avatar_initials( string $name ): string
{
    $name = trim( $name );
    if ( $name === '' ) {
        return '?';
    }

    $substr1 = static function ( string $s ): string {
        if ( function_exists( 'mb_substr' ) ) {
            return (string) mb_substr( $s, 0, 1, 'UTF-8' );
        }

        return substr( $s, 0, 1 );
    };

    $substr_at = static function ( string $s, int $start ): string {
        if ( function_exists( 'mb_substr' ) ) {
            return (string) mb_substr( $s, $start, 1, 'UTF-8' );
        }

        return substr( $s, $start, 1 );
    };

    $strlen = static function ( string $s ): int {
        if ( function_exists( 'mb_strlen' ) ) {
            return (int) mb_strlen( $s, 'UTF-8' );
        }

        return strlen( $s );
    };

    $upper = static function ( string $c ): string {
        if ( $c === '' ) {
            return '';
        }
        if ( function_exists( 'mb_strtoupper' ) ) {
            return mb_strtoupper( $c, 'UTF-8' );
        }

        return strtoupper( $c );
    };

    $parts = preg_split( '/[\s&]+/u', $name, -1, PREG_SPLIT_NO_EMPTY );
    if ( ! is_array( $parts ) || $parts === array() ) {
        return '?';
    }

    if ( count( $parts ) >= 2 ) {
        $first = $substr1( $parts[0] );
        $last  = $substr1( $parts[ count( $parts ) - 1 ] );

        return $upper( $first ) . $upper( $last );
    }

    $word = $parts[0];
    $len  = $strlen( $word );
    if ( $len >= 2 ) {
        return $upper( $substr1( $word ) ) . $upper( $substr_at( $word, 1 ) );
    }

    return $upper( $substr1( $word ) );
}

/**
 * @return array<int, array{quote:string,name:string,role:string,image_id:int,image_label:string,avatar_id:int}>
 */
function md_front_page_stories(): array
{
    return array(
        array(
            'quote'       => __(
                'After Dad\'s stroke we were drowning in brochures. Our advocate sat with us at the kitchen table — and within ten days he was somewhere he actually liked living.',
                'html5blank'
            ),
            'name'         => __( 'Margaret H.', 'html5blank' ),
            'role'         => __( 'Daughter · Indian River Shores', 'html5blank' ),
            'image_id'     => 0,
            'image_label'  => __( 'portrait / family at kitchen table', 'html5blank' ),
            'avatar_id'    => 0,
        ),
        array(
            'quote'       => __(
                'We toured three communities together. She caught things in the contracts I\'d have signed without thinking. She was an advocate in every sense of the word.',
                'html5blank'
            ),
            'name'         => __( 'Robert & Lynn P.', 'html5blank' ),
            'role'         => __( 'Spouse & son · Vero Beach', 'html5blank' ),
            'image_id'     => 0,
            'image_label'  => __( 'portrait / couple walking', 'html5blank' ),
            'avatar_id'    => 0,
        ),
        array(
            'quote'       => __(
                'Mom has dementia, and the search was breaking us. They found a memory care home where the staff knew her name on day one.',
                'html5blank'
            ),
            'name'         => __( 'Diane K.', 'html5blank' ),
            'role'         => __( 'Daughter · Sebastian', 'html5blank' ),
            'image_id'     => 0,
            'image_label'  => __( 'portrait / mother and daughter', 'html5blank' ),
            'avatar_id'    => 0,
        ),
    );
}

/**
 * ACF stories_items repeater → same shape as md_front_page_stories(); empty → defaults.
 *
 * @return array<int, array{quote:string,name:string,role:string,image_id:int,image_label:string,avatar_id:int}>
 */
function md_get_front_page_stories(): array
{
    $defaults = md_front_page_stories();
    if (!function_exists('get_field')) {
        return $defaults;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return $defaults;
    }
    $rows = get_field('stories_items', $page_id);
    if (!is_array($rows) || $rows === array()) {
        return $defaults;
    }

    $out = array();
    foreach ($rows as $row) {
        if (!is_array($row)) {
            continue;
        }
        $quote = isset($row['story_quote']) ? trim((string) $row['story_quote']) : '';
        $name  = isset($row['story_name']) ? trim((string) $row['story_name']) : '';
        if ($quote === '' || $name === '') {
            continue;
        }
        $role = isset($row['story_role']) ? trim((string) $row['story_role']) : '';

        $image_id = md_story_acf_attachment_id( $row['story_image'] ?? null );
        $avatar_id = md_story_acf_attachment_id( $row['story_avatar'] ?? null );

        $image_label = isset($row['story_image_placeholder']) ? trim((string) $row['story_image_placeholder']) : '';

        $idx = count($out);
        if ($image_id === 0 && $image_label === '' && isset($defaults[$idx])) {
            $image_label = $defaults[$idx]['image_label'];
        }

        $out[] = array(
            'quote'       => $quote,
            'name'        => $name,
            'role'        => $role,
            'image_id'    => $image_id,
            'image_label' => $image_label,
            'avatar_id'   => $avatar_id,
        );
    }

    if ($out === array()) {
        return $defaults;
    }

    return $out;
}
