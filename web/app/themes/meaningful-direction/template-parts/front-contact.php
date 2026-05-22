<?php
/**
 * Free consultation block — expects $contact_* vars set by front-page.php.
 *
 * @var string $contact_eyebrow
 * @var string $contact_headline
 * @var string $contact_intro
 * @var string $contact_phone
 * @var string $contact_email
 * @var string $contact_serving
 * @var array  $contact_h2_allow
 */

defined( 'ABSPATH' ) || exit;

$contact_tel_href   = md_contact_tel_href( $contact_phone );
$contact_mail_href  = 'mailto:' . sanitize_email( $contact_email );
$contact_mail_valid = is_email( $contact_email );
if ( ! $contact_mail_valid ) {
    $contact_mail_href = '#';
}

?>
<section class="contact md-site-contact" id="contact" aria-label="<?php esc_attr_e( 'Free consultation', 'html5blank' ); ?>">
	<div class="container contact-grid">
		<div class="contact-intro">
			<p class="eyebrow"><?php echo esc_html( $contact_eyebrow ); ?></p>
			<h2 class="h2 contact-h2"><?php echo wp_kses( $contact_headline, $contact_h2_allow ); ?></h2>
			<p class="contact-lead"><?php echo nl2br( esc_html( $contact_intro ) ); ?></p>

			<div class="contact-details">
				<div class="contact-detail">
					<svg class="contact-detail__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.44 12.44 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.44 12.44 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
					</svg>
					<div>
						<div class="ttl"><?php esc_html_e( 'Call', 'html5blank' ); ?></div>
						<div class="val"><a href="<?php echo esc_url( $contact_tel_href ); ?>"><?php echo esc_html( $contact_phone ); ?></a></div>
					</div>
				</div>
				<div class="contact-detail">
					<svg class="contact-detail__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
						<polyline points="22,6 12,13 2,6" />
					</svg>
					<div>
						<div class="ttl"><?php esc_html_e( 'Email', 'html5blank' ); ?></div>
						<div class="val">
							<?php if ( $contact_mail_valid ) : ?>
							<a href="<?php echo esc_url( $contact_mail_href ); ?>"><?php echo esc_html( $contact_email ); ?></a>
							<?php else : ?>
							<?php echo esc_html( $contact_email ); ?>
							<?php endif; ?>
						</div>
					</div>
				</div>
				<div class="contact-detail">
					<svg class="contact-detail__icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
						<circle cx="12" cy="10" r="3" />
					</svg>
					<div>
						<div class="ttl"><?php esc_html_e( 'Serving', 'html5blank' ); ?></div>
						<div class="val"><?php echo esc_html( $contact_serving ); ?></div>
					</div>
				</div>
			</div>
		</div>

		<div class="md-front-contact-formidable">
			<?php echo do_shortcode( '[formidable id=1]' ); ?>
		</div>
	</div>
</section>
