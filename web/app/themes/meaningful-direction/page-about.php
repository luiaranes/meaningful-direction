<?php
/**
 * Page slug template: meaningful-direction/about/ (must use page slug "about").
 * Meaningful Direction — standalone About prototype (prototype about.jsx → PHP).
 */
get_header();

if (!have_posts()) {
	get_footer();
	return;
}

while (have_posts()) :
	the_post();
	$page_id = (int) get_the_ID();

	$values_rows   = md_about_parse_values_rows(function_exists('get_field') ? get_field('about_values', $page_id) : array(), $page_id);
	$team_rows     = md_about_parse_team_rows(function_exists('get_field') ? get_field('about_team', $page_id) : array(), $page_id);
	$timeline_rows = md_about_parse_timeline_rows(function_exists('get_field') ? get_field('about_timeline', $page_id) : array(), $page_id);

	$home_hash_process = trailingslashit(home_url('/')) . '#process';

	$primary_cta = md_hero_normalize_link(
		md_get_about_field(
			'about_hero_primary_cta_link',
			array(
				'url' => md_nav_url_page_or_hash('contact', 'contact'),
			),
			$page_id
		),
		__('Talk with an advocate', 'html5blank'),
		'contact'
	);
	$secondary_cta = md_hero_normalize_link(
		md_get_about_field(
			'about_hero_secondary_cta_link',
			array(
				'url' => $home_hash_process,
			),
			$page_id
		),
		__('See our process', 'html5blank'),
		'process'
	);

	$bottom_primary = md_hero_normalize_link(
		md_get_about_field(
			'about_footer_primary_cta_link',
			array(
				'url' => md_nav_url_page_or_hash('contact', 'contact'),
			),
			$page_id
		),
		__('Request a consultation', 'html5blank'),
		'contact'
	);

	$phone_default = md_get_home_hero_field('contact_phone', '(772) 555-0142');
	$phone_display = md_get_about_field(
		'about_footer_phone_display',
		$phone_default,
		$page_id
	);

	$tel_href = md_format_tel_href($phone_display);
	if ($tel_href === '') {
		$tel_href = md_format_tel_href('(772) 555-0142');
	}

	if (md_about_hero_is_enabled($page_id)) :
		$hero_image_raw = function_exists('get_field') ? get_field('about_hero_image', $page_id) : null;
		if (!is_array($hero_image_raw)) {
			$hero_image_raw = array();
		}
		$hero_has_image_id = !empty($hero_image_raw['ID']);
		$hero_image_url    = (!empty($hero_image_raw['url'])) ? (string) $hero_image_raw['url'] : '';

		$eyebrow = md_get_about_field(
			'about_hero_eyebrow',
			__('About Us', 'html5blank'),
			$page_id
		);
		$h_before = md_get_about_field(
			'about_hero_headline_before_em',
			__("Built by a daughter who'd ", 'html5blank'),
			$page_id
		);
		$h_em = md_get_about_field(
			'about_hero_headline_em',
			__('been there', 'html5blank'),
			$page_id
		);
		$h_after = md_get_about_field(
			'about_hero_headline_after_em',
			__('.', 'html5blank'),
			$page_id
		);
		$subcopy = md_get_about_field(
			'about_hero_subcopy',
			__('Meaningful Direction Senior Placement was founded in Vero Beach in 2017 — by a family that knew firsthand how lost the senior living search can feel. We are advocates, not agents. Our service is free to families, our knowledge is local, and our loyalty is to you.', 'html5blank'),
			$page_id
		);
		$st1n = md_get_about_field(
			'about_hero_stat_1_num',
			__('2017', 'html5blank'),
			$page_id
		);
		$st1l = md_get_about_field(
			'about_hero_stat_1_lbl',
			__('founded in Vero Beach', 'html5blank'),
			$page_id
		);
		$st2n = md_get_about_field(
			'about_hero_stat_2_num',
			'200+',
			$page_id
		);
		$st2l = md_get_about_field(
			'about_hero_stat_2_lbl',
			__('families guided', 'html5blank'),
			$page_id
		);
		$st3n = md_get_about_field(
			'about_hero_stat_3_num',
			'25+',
			$page_id
		);
		$st3l = md_get_about_field(
			'about_hero_stat_3_lbl',
			__('communities personally vetted', 'html5blank'),
			$page_id
		);

		$ph_lab = md_get_about_field(
			'about_hero_placeholder_label',
			__('founder portrait / on the vero beach pier', 'html5blank'),
			$page_id
		);
		$quote = md_get_about_field(
			'about_hero_quote',
			__('“We started this company because we needed it ourselves — and couldn\'t find it.”', 'html5blank'),
			$page_id
		);
		$quote_src = md_get_about_field(
			'about_hero_quote_src',
			__('— Caroline Halpern, Founder', 'html5blank'),
			$page_id
		);
?>

<section class="hero md-site-hero" id="top">

	<div class="container hero-grid">

		<div class="hero-copy">

			<p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>

			<h1 class="h1">
				<?php echo esc_html($h_before); ?><span class="em"><?php echo esc_html($h_em); ?></span><?php echo esc_html($h_after); ?>
			</h1>

			<p class="hero-sub"><?php echo nl2br(esc_html($subcopy)); ?></p>

			<div class="hero-ctas">

				<a class="btn btn-primary" href="<?php echo esc_url($primary_cta['href_raw']); ?>"<?php echo md_hero_link_target_attr_html($primary_cta['target']); ?>>

					<?php echo esc_html($primary_cta['title']); ?>

					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<line x1="4" y1="12" x2="20" y2="12" />
						<polyline points="14 6 20 12 14 18" />
					</svg>

				</a>

				<a class="btn btn-ghost" href="<?php echo esc_url($secondary_cta['href_raw']); ?>"<?php echo md_hero_link_target_attr_html($secondary_cta['target']); ?>><?php echo esc_html($secondary_cta['title']); ?></a>

			</div>

			<div class="hero-meta">

				<div class="hero-meta-item">
					<div class="num"><?php echo esc_html($st1n); ?></div>
					<div class="lbl"><?php echo esc_html($st1l); ?></div>
				</div>

				<div class="hero-meta-item">
					<div class="num"><?php echo esc_html($st2n); ?></div>
					<div class="lbl"><?php echo esc_html($st2l); ?></div>
				</div>

				<div class="hero-meta-item">
					<div class="num"><?php echo esc_html($st3n); ?></div>
					<div class="lbl"><?php echo esc_html($st3l); ?></div>
				</div>

			</div>

		</div>

		<div class="hero-image-stack">

			<?php if ($hero_has_image_id) : ?>
				<div class="hero-image">
					<?php
					echo wp_get_attachment_image(
						(int) $hero_image_raw['ID'],
						'large',
						false,
						array(
							'class'   => 'hero-image__img',
							'loading' => 'lazy',
							'sizes'   => '(min-width: 900px) 42vw, 100vw',
						)
					);
					?>
				</div>
			<?php elseif ($hero_image_url !== '') : ?>
				<div class="hero-image">
					<?php
					$hero_alt = '';
					if (is_array($hero_image_raw)) {
						$hero_alt = isset($hero_image_raw['alt'])
							? (string) $hero_image_raw['alt']
							: '';
					}
					if ($hero_alt === '' && isset($hero_image_raw['title'])) {
						$hero_alt = (string) $hero_image_raw['title'];
					}
					?>
					<img class="hero-image__img"
						src="<?php echo esc_url($hero_image_url); ?>"
						alt="<?php echo esc_attr($hero_alt); ?>"
						decoding="async"
						loading="lazy" />
				</div>
			<?php else : ?>
				<div class="placeholder hero-image" role="presentation" aria-hidden="true">
					<span class="ph-label"><?php echo esc_html($ph_lab); ?></span>
				</div>
			<?php endif; ?>

			<div class="accent-card">
				<p class="quote"><?php echo nl2br(esc_html($quote)); ?></p>
				<div class="src"><?php echo esc_html($quote_src); ?></div>
			</div>

		</div>

	</div>

</section>

<?php
	endif;

	if (md_mission_vision_is_enabled()) :
		md_render_road_divider();
		$m_em = array( 'em' => array() );
		$mission_text_default = __(
			'Providing <em>peace of mind</em> to Vero Beach families by connecting seniors with personalized living solutions that prioritize comfort, safety, and love.',
			'html5blank'
		);
		$vision_text_default = __(
			'To redefine aging — connecting seniors to vibrant, supportive environments where they don\'t just <em>reside</em>, but belong.',
			'html5blank'
		);
		$mission_heading = md_get_home_hero_field( 'mission_heading', __( 'Our Mission', 'html5blank' ) );
		$vision_heading  = md_get_home_hero_field( 'vision_heading', __( 'Our Vision', 'html5blank' ) );
		$mission_body    = md_get_home_hero_field( 'mission_body', $mission_text_default );
		$vision_body     = md_get_home_hero_field( 'vision_body', $vision_text_default );
?>

<section class="mission md-site-mission" id="about" aria-label="<?php esc_attr_e( 'Mission and vision', 'html5blank' ); ?>">
	<div class="container mission-grid">
		<div class="mission-block">
			<div class="mission-label"><?php echo esc_html( $mission_heading ); ?></div>
			<p class="mission-text"><?php echo wp_kses( $mission_body, $m_em ); ?></p>
		</div>
		<div class="mission-block">
			<div class="mission-label"><?php echo esc_html( $vision_heading ); ?></div>
			<p class="mission-text"><?php echo wp_kses( $vision_body, $m_em ); ?></p>
		</div>
	</div>
</section>

<?php
		md_render_road_divider();
	endif;

	if (! md_mission_vision_is_enabled()) {
		md_render_road_divider();
	}

	$story_img = function_exists('get_field') ? get_field('about_story_image', $page_id) : null;
	if (! is_array($story_img)) {
		$story_img = array();
	}
	$story_has_id = ! empty($story_img['ID']);
	$story_url    = ! empty($story_img['url']) ? (string) $story_img['url'] : '';

	$story_ph = md_get_about_field(
		'about_story_placeholder_label',
		__('story / kitchen table, late afternoon', 'html5blank'),
		$page_id
	);
	$story_eyebrow = md_get_about_field(
		'about_story_eyebrow',
		__('Our Story', 'html5blank'),
		$page_id
	);
	$story_h2_b = md_get_about_field(
		'about_story_h2_before',
		__('A search that started ', 'html5blank'),
		$page_id
	);
	$story_h2_em = md_get_about_field(
		'about_story_h2_em',
		__('at a kitchen table', 'html5blank'),
		$page_id
	);
	$story_h2_a = md_get_about_field(
		'about_story_h2_after',
		__('.', 'html5blank'),
		$page_id
	);
	$story_p1 = md_get_about_field(
		'about_story_p1',
		__('In 2014, our founder Caroline Halpern was searching for a memory care community for her father. She had a stack of brochures, a sales call from every community in the county, and no one telling her what was actually true about any of them.', 'html5blank'),
		$page_id
	);
	$story_p2 = md_get_about_field(
		'about_story_p2',
		__('She found her way through eventually — not because the system worked, but because a kind nurse at the hospital quietly told her which three communities to actually visit. That conversation, at a hospital cafeteria table, was the first honest one she\'d had in months.', 'html5blank'),
		$page_id
	);
	$story_p3 = md_get_about_field(
		'about_story_p3',
		__('Three years later, Caroline founded Meaningful Direction so that no Vero Beach family would have to find the kind nurse on their own. The principle has not changed since: we are paid only when a placement is made, never by the family, and we will tell you the truth about every community in our county — including the ones we won\'t recommend.', 'html5blank'),
		$page_id
	);
?>

<section class="ct-what md-site-about-story" id="story">
	<div class="container ct-what-grid">
		<div class="ct-what-image-wrap">
			<?php if ($story_has_id) : ?>
				<div class="ct-what-image hero-image">
					<?php
					echo wp_get_attachment_image(
						(int) $story_img['ID'],
						'large',
						false,
						array(
							'class'   => 'hero-image__img',
							'loading' => 'lazy',
							'sizes'   => '(min-width: 880px) 42vw, 100vw',
						)
					);
					?>
				</div>
			<?php elseif ($story_url !== '') : ?>
				<div class="ct-what-image hero-image">
					<?php
					$story_alt = '';
					if (is_array($story_img)) {
						$story_alt = isset($story_img['alt']) ? (string) $story_img['alt'] : '';
						if ($story_alt === '' && isset($story_img['title'])) {
							$story_alt = (string) $story_img['title'];
						}
					}
					?>
					<img class="hero-image__img"
						src="<?php echo esc_url($story_url); ?>"
						alt="<?php echo esc_attr($story_alt); ?>"
						decoding="async"
						loading="lazy" />
				</div>
			<?php else : ?>
				<div class="placeholder ct-what-image hero-image" role="presentation" aria-hidden="true">
					<span class="ph-label"><?php echo esc_html($story_ph); ?></span>
				</div>
			<?php endif; ?>
		</div>
		<div class="ct-what-body">
			<p class="eyebrow"><?php echo esc_html($story_eyebrow); ?></p>
			<h2 class="h2">
				<?php echo esc_html($story_h2_b); ?><span class="em"><?php echo esc_html($story_h2_em); ?></span><?php echo esc_html($story_h2_a); ?>
			</h2>
			<div class="ct-what-paragraphs">
				<p class="p"><?php echo esc_html($story_p1); ?></p>
				<p class="p"><?php echo esc_html($story_p2); ?></p>
				<p class="p"><?php echo esc_html($story_p3); ?></p>
			</div>
		</div>
	</div>
</section>

<?php md_render_road_divider(); ?>

<?php
	$vals_eyebrow = md_get_about_field(
		'about_values_eyebrow',
		__('What we believe', 'html5blank'),
		$page_id
	);
	$vals_h2_b = md_get_about_field(
		'about_values_h2_before',
		__('The four ', 'html5blank'),
		$page_id
	);
	$vals_h2_em = md_get_about_field(
		'about_values_h2_em',
		__('non-negotiables', 'html5blank'),
		$page_id
	);
	$vals_h2_a = md_get_about_field(
		'about_values_h2_after',
		__('.', 'html5blank'),
		$page_id
	);
	$vals_intro = md_get_about_field(
		'about_values_intro',
		__('The principles that have shaped every conversation since 2017 — and that we measure ourselves against, week after week.', 'html5blank'),
		$page_id
	);
?>

<section class="ct-signs md-site-about-values" id="values">
	<div class="container">
		<div class="ct-signs-head">
			<div>
				<p class="eyebrow"><?php echo esc_html($vals_eyebrow); ?></p>
				<h2 class="h2">
					<?php echo esc_html($vals_h2_b); ?><span class="em"><?php echo esc_html($vals_h2_em); ?></span><?php echo esc_html($vals_h2_a); ?>
				</h2>
			</div>
			<p class="p ct-signs-intro"><?php echo esc_html($vals_intro); ?></p>
		</div>
		<div class="ct-signs-grid ct-signs-grid--four md-about-values-grid" role="list">
			<?php foreach ($values_rows as $v) : ?>
			<div class="ct-sign-card" role="listitem">
				<span class="ct-sign-num"><?php echo esc_html($v['n']); ?></span>
				<h4><?php echo esc_html($v['h']); ?></h4>
				<p><?php echo esc_html($v['p']); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php md_render_road_divider(); ?>

<section class="md-site-about-team md-site-about-team-band" id="team">
	<div class="container">
		<div class="process-head">
			<div>
				<p class="eyebrow"><?php echo esc_html(md_get_about_field('about_team_eyebrow', __('Your Advocates', 'html5blank'), $page_id)); ?></p>
				<h2 class="h2"><?php echo wp_kses(
					md_get_about_field(
						'about_team_headline',
						__('The people<br />who pick up the phone.', 'html5blank'),
						$page_id
					),
					array( 'br' => array() )
				); ?></h2>
			</div>
			<p class="about-team-intro p"><?php echo esc_html(md_get_about_field(
				'about_team_intro',
				__('A small team, on purpose. You will know your advocate by name, and they will know your loved one\'s story without being reminded of it.', 'html5blank'),
				$page_id
			)); ?></p>
		</div>

		<?php
		$team_col_count = count($team_rows);
		$team_portrait_sizes = sprintf(
			'(min-width: 881px) %dvw, 100vw',
			(int) max(20, min(100, (int) floor(100 / max($team_col_count, 1))))
		);
		?>
		<div
			class="team-grid md-about-team-grid<?php echo $team_col_count === 4 ? ' team-grid--count-4' : ''; ?>"
			style="--team-cols: <?php echo (int) max($team_col_count, 1); ?>;"
			role="list">
			<?php foreach ($team_rows as $m) : ?>
			<div class="team-card" role="listitem">
				<?php if (! empty($m['image_id'])) : ?>
					<div class="team-portrait team-portrait--img">
						<?php
						echo wp_get_attachment_image(
							$m['image_id'],
							'medium_large',
							false,
							array(
								'class'    => 'team-portrait__img',
								'loading'  => 'lazy',
								'decoding' => 'async',
								'sizes'    => $team_portrait_sizes,
							)
						);
						?>
					</div>
				<?php else : ?>
					<div class="placeholder hero-image team-portrait team-portrait--ph" role="presentation" aria-hidden="true">
						<span class="ph-label"><?php echo esc_html($m['image_label']); ?></span>
					</div>
				<?php endif; ?>
				<div class="team-body">
					<div class="team-cred"><?php echo esc_html($m['cred']); ?></div>
					<h3 class="team-name"><?php echo esc_html($m['name']); ?></h3>
					<div class="team-role"><?php echo esc_html($m['role']); ?></div>
					<p class="team-bio"><?php echo esc_html($m['bio']); ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php md_render_road_divider(); ?>

<section class="about-timeline md-site-about-timeline" id="history">
	<div class="container">
		<div class="process-head">
			<div>
				<p class="eyebrow"><?php echo esc_html(md_get_about_field('about_timeline_eyebrow', __('A short history', 'html5blank'), $page_id)); ?></p>
				<h2 class="h2">
					<?php echo esc_html(md_get_about_field(
						'about_timeline_h2_before',
						__('How we got ', 'html5blank'),
						$page_id
					)); ?><span class="em"><?php echo esc_html(md_get_about_field('about_timeline_h2_em', __('here', 'html5blank'), $page_id)); ?></span><?php echo esc_html(md_get_about_field('about_timeline_h2_after', __('.', 'html5blank'), $page_id)); ?>
				</h2>
			</div>
			<p class="about-timeline-intro p"><?php echo esc_html(md_get_about_field(
				'about_timeline_intro',
				__('Nine years from a daughter\'s search to two hundred families guided. The shape of the work has not changed.', 'html5blank'),
				$page_id
			)); ?></p>
		</div>

		<ol class="timeline md-about-timeline">
			<?php foreach ($timeline_rows as $t) : ?>
			<li class="timeline-item">
				<div class="timeline-yr"><?php echo esc_html($t['yr']); ?></div>
				<div class="timeline-body">
					<h4><?php echo esc_html($t['h']); ?></h4>
					<p><?php echo esc_html($t['p']); ?></p>
				</div>
			</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<?php md_render_road_divider(); ?>

<section class="care-cta md-site-about-footer-cta">

	<div class="container care-cta-grid">

		<div>

			<p class="eyebrow"><?php echo esc_html(md_get_about_field(
				'about_footer_eyebrow',
				__('Begin a conversation', 'html5blank'),
				$page_id
			)); ?></p>

			<h2 class="h2"><?php echo wp_kses(
				md_get_about_field(
					'about_footer_headline',
					__('Whenever you\'re ready,<br />we\'re a phone call away.', 'html5blank'),
					$page_id
				),
				array( 'br' => array() )
			); ?></h2>

			<p class="p"><?php echo esc_html(md_get_about_field(
				'about_footer_body',
				__('The first call is unhurried, confidential, and free. We\'ll listen, ask a few questions, and only then talk about next steps — if there are any to talk about yet.', 'html5blank'),
				$page_id
			)); ?></p>

		</div>

		<div class="ctas">

			<a class="btn btn-primary" href="<?php echo esc_url($bottom_primary['href_raw']); ?>"<?php echo md_hero_link_target_attr_html($bottom_primary['target']); ?>>

				<?php echo esc_html($bottom_primary['title']); ?>

				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<line x1="4" y1="12" x2="20" y2="12" />
					<polyline points="14 6 20 12 14 18" />
				</svg>

			</a>

			<a class="btn btn-ghost" href="<?php echo esc_url($tel_href); ?>"><?php echo esc_html($phone_display); ?></a>

		</div>

	</div>

</section>

<?php
	endwhile;

get_footer();
