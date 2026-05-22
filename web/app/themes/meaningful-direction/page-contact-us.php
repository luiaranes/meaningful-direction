<?php
/**
 * Page slug template: meaningful-direction/contact-us/ (page slug must be "contact-us").
 * Contact standalone prototype (contact.jsx → PHP).
 */
get_header();

if (!have_posts()) {
	get_footer();
	return;
}

while (have_posts()) :
	the_post();
	$page_id = (int) get_the_ID();

	$faq_rows   = md_contact_parse_faq_rows(function_exists('get_field') ? get_field('contact_pg_faq', $page_id) : array(), $page_id);
	$way_rows   = md_contact_parse_way_rows(function_exists('get_field') ? get_field('contact_pg_ways', $page_id) : array(), $page_id);
	$area_places = md_contact_parse_area_places(function_exists('get_field') ? get_field('contact_pg_area_places', $page_id) : array());

	$phone_display = trim((string) md_get_contact_page_field(
		'contact_pg_phone_display',
		'',
		$page_id
	));
	if ($phone_display === '') {
		$phone_display = md_get_home_hero_field('contact_phone', '(772) 555-0142');
	}
	$tel_href = md_contact_tel_href($phone_display);

	$email_display = trim((string) md_get_contact_page_field(
		'contact_pg_email_display',
		'',
		$page_id
	));
	if ($email_display === '') {
		$email_display = md_get_home_hero_field('contact_email', 'hello@meaningfuldirection.com');
	}
	$email_href  = 'mailto:' . sanitize_email($email_display);
	$email_valid = is_email($email_display);
	if (!$email_valid) {
		$email_href = '#';
	}

	$hero_eb = md_get_contact_page_field(
		'contact_pg_hero_eyebrow',
		__('Free Consultation', 'html5blank'),
		$page_id
	);
	$h1b = md_get_contact_page_field(
		'contact_pg_hero_h1_before',
		__('Let\'s begin with a ', 'html5blank'),
		$page_id
	);
	$h1e = md_get_contact_page_field(
		'contact_pg_hero_h1_em',
		__('conversation', 'html5blank'),
		$page_id
	);
	$h1a = md_get_contact_page_field(
		'contact_pg_hero_h1_after',
		__('.', 'html5blank'),
		$page_id
	);
	$hero_intro = md_get_contact_page_field(
		'contact_pg_hero_intro',
		__('No pressure, no fee — just a senior advocate ready to listen. Tell us a little about your loved one and we\'ll be in touch within one business day.', 'html5blank'),
		$page_id
	);

	$call_note = md_get_contact_page_field(
		'contact_pg_call_note',
		__('Mon–Fri 8am–6pm · Sat 10am–2pm', 'html5blank'),
		$page_id
	);
	$email_note = md_get_contact_page_field(
		'contact_pg_email_note',
		__('We respond within one business day.', 'html5blank'),
		$page_id
	);
	$visit1 = md_get_contact_page_field(
		'contact_pg_visit_line1',
		__('2150 Indian River Blvd, Suite B', 'html5blank'),
		$page_id
	);
	$visit2 = md_get_contact_page_field(
		'contact_pg_visit_line2',
		__('Vero Beach, FL 32960 · By appointment', 'html5blank'),
		$page_id
	);

	$form_eb = md_get_contact_page_field(
		'contact_pg_form_eyebrow',
		__('Send a message', 'html5blank'),
		$page_id
	);
	$form_title = md_get_contact_page_field(
		'contact_pg_form_title',
		__('Tell us about your loved one.', 'html5blank'),
		$page_id
	);
	$form_sub = md_get_contact_page_field(
		'contact_pg_form_sub',
		__('A few details help us prepare. Don\'t worry about getting it perfect — we\'ll fill in the rest on the call.', 'html5blank'),
		$page_id
	);
	$form_shortcode = md_get_contact_page_field(
		'contact_pg_form_shortcode',
		'[formidable id=1]',
		$page_id
	);

	$ways_eb = md_get_contact_page_field(
		'contact_pg_ways_eyebrow',
		__('Three ways to begin', 'html5blank'),
		$page_id
	);
	$ways_h2b = md_get_contact_page_field(
		'contact_pg_ways_h2_before',
		__('However you\'d like to ', 'html5blank'),
		$page_id
	);
	$ways_h2e = md_get_contact_page_field(
		'contact_pg_ways_h2_em',
		__('start', 'html5blank'),
		$page_id
	);
	$ways_h2a = md_get_contact_page_field(
		'contact_pg_ways_h2_after',
		__('.', 'html5blank'),
		$page_id
	);
	$ways_intro = md_get_contact_page_field(
		'contact_pg_ways_intro',
		__('Some families want to talk on the phone the same day. Others want to read for a week first. Whatever pace feels right is the right pace.', 'html5blank'),
		$page_id
	);

	$faq_eb = md_get_contact_page_field(
		'contact_pg_faq_eyebrow',
		__('Common questions', 'html5blank'),
		$page_id
	);
	$faq_h2b = md_get_contact_page_field(
		'contact_pg_faq_h2_before',
		__('Before you ', 'html5blank'),
		$page_id
	);
	$faq_h2e = md_get_contact_page_field(
		'contact_pg_faq_h2_em',
		__('pick up the phone', 'html5blank'),
		$page_id
	);
	$faq_h2a = md_get_contact_page_field(
		'contact_pg_faq_h2_after',
		__('.', 'html5blank'),
		$page_id
	);
	$faq_intro = md_get_contact_page_field(
		'contact_pg_faq_intro',
		__('The questions we hear most often, answered honestly. If yours isn\'t here, ask it on the call.', 'html5blank'),
		$page_id
	);

	$area_eb = md_get_contact_page_field(
		'contact_pg_area_eyebrow',
		__('Where we serve', 'html5blank'),
		$page_id
	);
	$area_h2b = md_get_contact_page_field(
		'contact_pg_area_h2_before',
		__('Indian River County, ', 'html5blank'),
		$page_id
	);
	$area_h2e = md_get_contact_page_field(
		'contact_pg_area_h2_em',
		__('door to door', 'html5blank'),
		$page_id
	);
	$area_h2a = md_get_contact_page_field(
		'contact_pg_area_h2_after',
		__('.', 'html5blank'),
		$page_id
	);
	$area_intro = md_get_contact_page_field(
		'contact_pg_area_intro',
		__('We are deeply local — by design. Our advocates have walked every senior community in the county and many homes besides.', 'html5blank'),
		$page_id
	);
	$area_note = md_get_contact_page_field(
		'contact_pg_area_note',
		__('Out of area? If your loved one is relocating to be near family in Indian River County, we\'d be glad to help anyway.', 'html5blank'),
		$page_id
	);

	$area_map_raw = function_exists('get_field') ? get_field('contact_pg_area_map', $page_id) : null;
	if (!is_array($area_map_raw)) {
		$area_map_raw = array();
	}
	$area_map_id = !empty($area_map_raw['ID']) ? (int) $area_map_raw['ID'] : 0;
	$area_map_url = !empty($area_map_raw['url']) ? (string) $area_map_raw['url'] : '';
	$area_map_ph = md_get_contact_page_field(
		'contact_pg_area_map_placeholder',
		__('map / indian river county service area', 'html5blank'),
		$page_id
	);

	$faq_count = count($faq_rows);
	?>

<section class="care-hero md-site-contact-hero" id="top">

	<div class="container care-hero-grid care-hero-with-form">

		<div class="care-hero-text">

			<p class="eyebrow"><?php echo esc_html($hero_eb); ?></p>

			<h1 class="h1">
				<?php echo esc_html($h1b); ?><span class="em"><?php echo esc_html($h1e); ?></span><?php echo esc_html($h1a); ?>
			</h1>

			<p class="p care-hero-intro" style="margin-top: 24px;"><?php echo nl2br(esc_html($hero_intro)); ?></p>

			<div class="contact-detail-list">

				<a class="contact-row" href="<?php echo esc_url($tel_href); ?>">
					<span class="contact-row-ic">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.44 12.44 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.44 12.44 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
						</svg>
					</span>
					<span class="contact-row-body">
						<span class="contact-row-lbl"><?php esc_html_e('Call', 'html5blank'); ?></span>
						<span class="contact-row-val"><?php echo esc_html($phone_display); ?></span>
						<span class="contact-row-note"><?php echo esc_html($call_note); ?></span>
					</span>
				</a>

				<?php if ($email_valid) : ?>
				<a class="contact-row" href="<?php echo esc_url($email_href); ?>">
				<?php else : ?>
				<div class="contact-row contact-row-static">
				<?php endif; ?>
					<span class="contact-row-ic">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
							<polyline points="22,6 12,13 2,6" />
						</svg>
					</span>
					<span class="contact-row-body">
						<span class="contact-row-lbl"><?php esc_html_e('Email', 'html5blank'); ?></span>
						<span class="contact-row-val"><?php echo esc_html($email_display); ?></span>
						<span class="contact-row-note"><?php echo esc_html($email_note); ?></span>
					</span>
				<?php if ($email_valid) : ?>
				</a>
				<?php else : ?>
				</div>
				<?php endif; ?>

				<div class="contact-row contact-row-static">
					<span class="contact-row-ic">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
							<circle cx="12" cy="10" r="3" />
						</svg>
					</span>
					<span class="contact-row-body">
						<span class="contact-row-lbl"><?php esc_html_e('Visit', 'html5blank'); ?></span>
						<span class="contact-row-val"><?php echo esc_html($visit1); ?></span>
						<span class="contact-row-note"><?php echo esc_html($visit2); ?></span>
					</span>
				</div>
			</div>

		</div>

		<div class="care-hero-form-wrap" id="form">

			<div class="care-hero-form-card">

				<p class="eyebrow"><?php echo esc_html($form_eb); ?></p>
				<h3 class="care-hero-form-title"><?php echo esc_html($form_title); ?></h3>
				<p class="care-hero-form-sub"><?php echo nl2br(esc_html($form_sub)); ?></p>

				<div class="md-front-contact-formidable md-contact-page-hero-form">
					<?php echo do_shortcode($form_shortcode !== '' ? $form_shortcode : '[formidable id=1]'); ?>
				</div>
			</div>

		</div>

	</div>

</section>

	<?php md_render_road_divider(); ?>

<section class="md-site-contact-ways" id="ways">
	<div class="container">
		<div class="process-head">
			<div>
				<p class="eyebrow"><?php echo esc_html($ways_eb); ?></p>
				<h2 class="h2">
					<?php echo esc_html($ways_h2b); ?><span class="em"><?php echo esc_html($ways_h2e); ?></span><?php echo esc_html($ways_h2a); ?>
				</h2>
			</div>
			<p class="p md-site-contact-ways-intro"><?php echo nl2br(esc_html($ways_intro)); ?></p>
		</div>
		<div class="contact-ways">
			<?php foreach ($way_rows as $w) : ?>
			<div class="contact-way">
				<div class="contact-way-num"><?php echo esc_html($w['n']); ?></div>
				<h3><?php echo esc_html($w['title']); ?></h3>
				<p><?php echo esc_html($w['body']); ?></p>
				<a class="btn-link" href="<?php echo esc_url($w['href_raw']); ?>"<?php echo md_hero_link_target_attr_html($w['target']); ?>>
					<?php echo esc_html($w['link_title']); ?>
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<line x1="4" y1="12" x2="20" y2="12" />
						<polyline points="14 6 20 12 14 18" />
					</svg>
				</a>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

	<?php md_render_road_divider(); ?>

<section class="faq-section md-site-contact-faq" id="faq">
	<div class="container faq-grid">
		<aside class="faq-aside">
			<p class="eyebrow"><?php echo esc_html($faq_eb); ?></p>
			<h2 class="h2">
				<?php echo esc_html($faq_h2b); ?><span class="em"><?php echo esc_html($faq_h2e); ?></span><?php echo esc_html($faq_h2a); ?>
			</h2>
			<p class="p faq-aside-intro"><?php echo nl2br(esc_html($faq_intro)); ?></p>
			<div class="faq-counter">
				<span><?php echo esc_html(str_pad((string) $faq_count, 2, '0', STR_PAD_LEFT)); ?></span>
				<span class="total">/ <?php esc_html_e('Q&A', 'html5blank'); ?></span>
			</div>
		</aside>
		<div class="faq-list">
			<?php
			$i = 0;
			foreach ($faq_rows as $faq) :
				++$i;
				?>
			<details class="faq-item" name="md-contact-faq">
				<summary class="faq-q">
					<span class="faq-mark" aria-hidden="true">
						<span class="num"><?php echo esc_html(str_pad((string) $i, 2, '0', STR_PAD_LEFT)); ?></span>
					</span>
					<span class="faq-q-main">
						<span class="faq-q-text"><?php echo esc_html($faq['q']); ?></span>
						<span class="pm" aria-hidden="true">
							<svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round">
								<line x1="2" y1="6" x2="10" y2="6" />
								<line x1="6" y1="2" x2="6" y2="10" />
							</svg>
						</span>
					</span>
				</summary>
				<div class="faq-a-shell">
					<div class="faq-a">
						<span class="faq-a-mark">—</span>
						<span><?php echo esc_html($faq['a']); ?></span>
					</div>
				</div>
			</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

	<?php md_render_road_divider(); ?>

<section id="service-area" class="contact-area md-site-contact-area">
	<div class="container">
		<div class="process-head">
			<div>
				<p class="eyebrow"><?php echo esc_html($area_eb); ?></p>
				<h2 class="h2">
					<?php echo esc_html($area_h2b); ?><span class="em"><?php echo esc_html($area_h2e); ?></span><?php echo esc_html($area_h2a); ?>
				</h2>
			</div>
			<p class="p md-site-contact-area-intro"><?php echo nl2br(esc_html($area_intro)); ?></p>
		</div>

		<div class="area-grid">
			<?php if ($area_map_id) : ?>
				<div class="area-map area-map--img">
					<?php
					echo wp_get_attachment_image(
						$area_map_id,
						'large',
						false,
						array(
							'class'   => 'area-map__img',
							'loading' => 'lazy',
							'sizes'   => '(min-width: 760px) 50vw, 100vw',
						)
					);
					?>
				</div>
			<?php elseif ($area_map_url !== '') : ?>
				<div class="area-map area-map--img">
					<?php
					$map_alt = '';
					if (isset($area_map_raw['alt'])) {
						$map_alt = (string) $area_map_raw['alt'];
					}
					?>
					<img class="area-map__img" src="<?php echo esc_url($area_map_url); ?>" alt="<?php echo esc_attr($map_alt); ?>" decoding="async" loading="lazy" />
				</div>
			<?php else : ?>
				<div class="placeholder hero-image area-map" role="presentation" aria-hidden="true">
					<span class="ph-label"><?php echo esc_html($area_map_ph); ?></span>
				</div>
			<?php endif; ?>

			<ul class="area-list">
				<?php foreach ($area_places as $place) : ?>
				<li><span class="dot"></span> <?php echo esc_html($place); ?></li>
				<?php endforeach; ?>
				<?php if ($area_note !== '') : ?>
				<li class="area-list-note"><?php echo esc_html($area_note); ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</section>

	<?php
endwhile;

get_footer();
