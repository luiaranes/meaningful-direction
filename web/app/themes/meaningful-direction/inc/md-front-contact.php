<?php
/**
 * Front page contact / free consultation — helpers.
 */

/** Build tel: href from display string (digits only). */
function md_contact_tel_href( string $display ): string
{
    $digits = preg_replace( '/\D+/', '', $display );

    return $digits !== '' ? 'tel:' . $digits : '#';
}
