<?php
/**
 * Archive: Care Types CPT — `/care-types/`.
 */
get_header();

$care_h2_allow = array( 'br' => array() );

$archive_eyebrow = md_get_home_hero_field( 'care_eyebrow', __( 'Levels of Care', 'html5blank' ) );
$archive_lead_h2 = md_get_home_hero_field(
	'care_headline',
	__( 'Every level,<br />one trusted guide.', 'html5blank' )
);
$archive_intro = md_get_home_hero_field(
	'care_intro',
	__(
		'Browse how we serve families across independent living, assisted living, memory care, skilled nursing — and every step between.',
		'html5blank'
	)
);
?>

<main id="primary" class="site-main md-archive-care-types" tabindex="-1">
	<section class="care md-site-care" aria-label="<?php esc_attr_e( 'All care types', 'html5blank' ); ?>">
		<div class="container">
			<div class="care-head">
				<div>
					<p class="eyebrow"><?php echo esc_html( $archive_eyebrow ); ?></p>
					<h1 class="h2 care-h2"><?php echo wp_kses( $archive_lead_h2, $care_h2_allow ); ?></h1>
				</div>
				<p class="care-intro"><?php echo nl2br( esc_html( $archive_intro ) ); ?></p>
			</div>

			<?php if ( have_posts() ) : ?>
				<div class="care-grid md-archive-care-grid" role="list">
					<?php
					while ( have_posts() ) :
						the_post();
						$pid = (int) get_the_ID();
						if ( !$pid ) {
							continue;
						}
						$icon = md_care_archive_card_icon( $pid );
						$blurb = md_care_archive_card_blurb( $pid );
						$list_id = 'care-' . sanitize_title( get_post_field( 'post_name', $pid ) ?: (string) $pid );
						?>
					<a
						class="care-card care-card--link"
						id="<?php echo esc_attr( $list_id ); ?>"
						href="<?php the_permalink(); ?>"
						role="listitem"
					>
						<?php md_render_care_icon( $icon ); ?>
						<h2 class="care-card-heading"><?php the_title(); ?></h2>
						<p><?php echo esc_html( $blurb ); ?></p>
					</a>
						<?php
					endwhile;
					?>
				</div>

				<?php get_template_part( 'pagination' ); ?>
			<?php else : ?>
				<p class="care-archive-empty"><?php esc_html_e( 'Care type pages will appear here once published.', 'html5blank' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

	<?php
	if ( function_exists( 'md_render_road_divider' ) ) {
		md_render_road_divider();
	}
	if ( function_exists( 'md_contact_section_is_enabled' ) && md_contact_section_is_enabled() ) {
		$contact_h2_allow  = array( 'br' => array() );
		$contact_eyebrow   = md_get_home_hero_field( 'contact_eyebrow', __( 'Free Consultation', 'html5blank' ) );
		$contact_headline  = md_get_home_hero_field(
			'contact_headline',
			__( 'Let\'s begin with a conversation.', 'html5blank' )
		);
		$contact_intro     = md_get_home_hero_field(
			'contact_intro',
			__(
				'No pressure, no fee — just a senior advocate ready to listen. Tell us a little about your loved one and we\'ll be in touch within one business day.',
				'html5blank'
			)
		);
		$contact_phone    = md_get_home_hero_field( 'contact_phone', '(772) 555-0142' );
		$contact_email     = md_get_home_hero_field( 'contact_email', 'hello@meaningfuldirection.com' );
		$contact_serving   = md_get_home_hero_field(
			'contact_serving',
			__( 'Vero Beach · Sebastian · Indian River County', 'html5blank' )
		);
		include get_template_directory() . '/template-parts/front-contact.php';
	}
	?>
</main>

<?php
get_footer();
