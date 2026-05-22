<?php get_header(); ?>

<?php
if (have_posts()) :
	while (have_posts()) :
		the_post();
		if (md_home_hero_is_enabled()) :
			$hero_image_raw = '';
			if (function_exists('get_field')) {
				$page_id = md_home_hero_post_id() ?: get_the_ID();
				$hero_image_raw = get_field('hero_image', $page_id);
			}
			$hero_has_image_id = is_array($hero_image_raw)
				&& !empty($hero_image_raw['ID']);
			$hero_image_url = (is_array($hero_image_raw) && ! empty($hero_image_raw['url']))
				? (string) $hero_image_raw['url']
				: '';

			$eyebrow = md_get_home_hero_field('hero_eyebrow', __('Vero Beach · Indian River County', 'html5blank'));
			$hl_before = md_get_home_hero_field('hero_headline_before_break', __('A senior living search', 'html5blank'));
			$hl_prefix = md_get_home_hero_field('hero_headline_guided_prefix', __('guided by ', 'html5blank'));
			$hl_emphasis = md_get_home_hero_field('hero_headline_emphasis', __('love', 'html5blank'));
			$hl_suffix = md_get_home_hero_field('hero_headline_suffix', __(', not lists.', 'html5blank'));
			$subcopy = md_get_home_hero_field(
				'hero_subcopy',
				__('We help Vero Beach families find the right assisted living, memory care, or independent community — with the care, calm, and certainty this decision deserves.', 'html5blank')
			);
			$primary_cta = md_hero_normalize_link(
				md_get_home_hero_field('hero_primary_cta_link', array()),
				__('Book a Discovery Session', 'html5blank'),
				'contact'
			);
			$secondary_cta = md_hero_normalize_link(
				md_get_home_hero_field('hero_secondary_cta_link', array()),
				__('See how it works', 'html5blank'),
				'process'
			);

			// Stat 1 default uses a NBSP between words (prototype used two spaced strings).
			$st1n = md_get_home_hero_field('hero_stat_1_num', __('No', 'html5blank') . "\xc2\xa0" . __('cost', 'html5blank'));
			$st1l = md_get_home_hero_field('hero_stat_1_lbl', __('to families', 'html5blank'));
			$st2n = md_get_home_hero_field('hero_stat_2_num', '25+');
			$st2l = md_get_home_hero_field('hero_stat_2_lbl', __('vetted communities', 'html5blank'));
			$st3n = md_get_home_hero_field('hero_stat_3_num', '1:1');
			$st3l = md_get_home_hero_field('hero_stat_3_lbl', __('advocate guidance', 'html5blank'));

			$ph_lab = md_get_home_hero_field('hero_image_placeholder_label', __('hero / sunrise porch · vero beach', 'html5blank'));
			$quote = md_get_home_hero_field('hero_quote', __('They didn\'t show us listings. They listened to our family.', 'html5blank'));
			$quote_src = md_get_home_hero_field('hero_quote_src', __('— The Halpern Family', 'html5blank'));
?>

<section class="hero md-site-hero" id="top">

	<div class="container hero-grid">

		<div class="hero-copy">

			<p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>

			<h1 class="h1">
				<?php echo esc_html($hl_before); ?><br />
				<?php echo esc_html($hl_prefix); ?><span class="em"><?php echo esc_html($hl_emphasis); ?></span><?php echo esc_html($hl_suffix); ?>
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

		<?php endif; ?>

<?php
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

		if (md_process_section_is_enabled()) :
			$process_steps      = md_get_front_page_process_steps();
			$process_h2_allowed = array( 'br' => array() );
			$process_eyebrow    = md_get_home_hero_field( 'process_eyebrow', __( 'Our Process', 'html5blank' ) );
			$process_headline   = md_get_home_hero_field(
				'process_headline',
				__( 'Meaningful Direction —<br />a path to peace of mind.', 'html5blank' )
			);
			$process_intro      = md_get_home_hero_field(
				'process_intro',
				__(
					'Four steps, never rushed. Built around the family — not the timeline of a community looking to fill a room.',
					'html5blank'
				)
			);
?>

<section id="process" class="md-site-process" aria-label="<?php esc_attr_e( 'Our process', 'html5blank' ); ?>">
	<div class="container">
		<div class="process-head">
			<div>
				<p class="eyebrow"><?php echo esc_html( $process_eyebrow ); ?></p>
				<h2 class="h2 process-h2"><?php echo wp_kses( $process_headline, $process_h2_allowed ); ?></h2>
			</div>
			<p class="process-intro"><?php echo nl2br( esc_html( $process_intro ) ); ?></p>
		</div>

		<div class="process-tabs">
			<div class="step-rail" role="tablist" aria-label="<?php esc_attr_e( 'Process steps', 'html5blank' ); ?>">
				<?php
				foreach ( $process_steps as $i => $step ) :
					$tab_id = 'md-process-tab-' . $i;
					$panel_id = 'md-process-panel-' . $i;
					?>
				<button
					type="button"
					class="step-tab<?php echo 0 === $i ? ' active' : ''; ?>"
					id="<?php echo esc_attr( $tab_id ); ?>"
					role="tab"
					aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"
					aria-controls="<?php echo esc_attr( $panel_id ); ?>"
					data-md-process-tab="<?php echo (int) $i; ?>"
				>
					<span class="num"><?php echo esc_html( $step['n'] ); ?></span>
					<span class="ttl"><?php echo esc_html( $step['title'] ); ?></span>
				</button>
					<?php
				endforeach;
				?>
			</div>

			<div class="process-panels">
				<?php
				foreach ( $process_steps as $i => $step ) :
					$tab_id = 'md-process-tab-' . $i;
					$panel_id = 'md-process-panel-' . $i;
					?>
				<div
					class="step-panel"
					id="<?php echo esc_attr( $panel_id ); ?>"
					role="tabpanel"
					aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
					data-md-process-panel="<?php echo (int) $i; ?>"
					<?php echo 0 !== $i ? 'hidden' : ''; ?>
				>
					<div class="step-panel-head">
						<span class="pn"><?php
							/* translators: %s: step number e.g. 01 */
							printf( esc_html__( 'Step %s', 'html5blank' ), esc_html( $step['n'] ) );
						?></span>
						<span class="pl"><?php echo esc_html( $step['label'] ); ?></span>
					</div>
					<h3><?php echo esc_html( $step['title'] ); ?></h3>
					<p class="p desc"><?php echo esc_html( $step['desc'] ); ?></p>
					<div class="bullets">
						<?php foreach ( $step['bullets'] as $bullet ) : ?>
						<div class="step-bullet">
							<span class="dot" aria-hidden="true"></span>
							<div>
								<h4><?php echo esc_html( $bullet['h'] ); ?></h4>
								<p><?php echo esc_html( $bullet['p'] ); ?></p>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
					<?php
				endforeach;
				?>
			</div>
		</div>
	</div>
</section>

<?php
			md_render_road_divider();
		endif;

		if ( md_care_section_is_enabled() ) :
			$care_levels = md_get_front_page_care_levels();
			$care_h2_allow = array( 'br' => array() );
			$care_eyebrow = md_get_home_hero_field( 'care_eyebrow', __( 'Levels of Care', 'html5blank' ) );
			$care_headline = md_get_home_hero_field(
				'care_headline',
				__( 'Right care.<br />Right community.', 'html5blank' )
			);
			$care_intro = md_get_home_hero_field(
				'care_intro',
				__(
					'We work across every level of senior living in Indian River County — and we\'ll help you understand which is right today, and which to plan for tomorrow.',
					'html5blank'
				)
			);
?>

<section class="care md-site-care" id="care" aria-label="<?php esc_attr_e( 'Levels of care', 'html5blank' ); ?>">
	<div class="container">
		<div class="care-head">
			<div>
				<p class="eyebrow"><?php echo esc_html( $care_eyebrow ); ?></p>
				<h2 class="h2 care-h2"><?php echo wp_kses( $care_headline, $care_h2_allow ); ?></h2>
			</div>
			<p class="care-intro"><?php echo nl2br( esc_html( $care_intro ) ); ?></p>
		</div>

		<div class="care-grid" role="list">
			<?php foreach ( $care_levels as $c ) : ?>
			<div class="care-card" id="<?php echo esc_attr( $c['id'] ); ?>" role="listitem">
				<?php md_render_care_icon( $c['icon'] ); ?>
				<h4><?php echo esc_html( $c['title'] ); ?></h4>
				<p><?php echo esc_html( $c['desc'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
		endif;

		if ( md_stories_section_is_enabled() ) :
			if ( md_care_section_is_enabled() ) {
				md_render_road_divider();
			}
			$stories           = md_get_front_page_stories();
			$stories_total     = count( $stories );
			$stories_h2_allow  = array( 'br' => array() );
			$stories_eyebrow   = md_get_home_hero_field( 'stories_eyebrow', __( 'Family Stories', 'html5blank' ) );
			$stories_headline  = md_get_home_hero_field(
				'stories_headline',
				__( 'The families we\'ve walked beside.', 'html5blank' )
			);
?>

<section class="stories md-site-stories" id="stories" aria-label="<?php esc_attr_e( 'Family stories', 'html5blank' ); ?>">
	<div class="container">
		<div class="stories-head">
			<p class="eyebrow stories-eyebrow"><?php echo esc_html( $stories_eyebrow ); ?></p>
			<h2 class="h2 stories-h2"><?php echo wp_kses( $stories_headline, $stories_h2_allow ); ?></h2>
		</div>

		<div
			class="testimonial-grid md-stories-grid"
			data-md-stories
			data-md-stories-total="<?php echo (int) $stories_total; ?>"
		>
			<div class="md-stories-visual-col">
				<?php
				foreach ( $stories as $i => $story ) :
					$is_first = 0 === $i;
					?>
				<div
					class="md-story-visual"
					id="md-story-photo-<?php echo (int) $i; ?>"
					data-md-story="<?php echo (int) $i; ?>"
					<?php echo $is_first ? '' : 'hidden'; ?>
				>
					<?php if ( ! empty( $story['image_id'] ) ) : ?>
					<div class="testimonial-image">
						<?php
						echo wp_get_attachment_image(
							(int) $story['image_id'],
							'large',
							false,
							array(
								'class'    => 'testimonial-image__img',
								'loading'  => 'lazy',
								'decoding' => 'async',
								'sizes'    => '(min-width: 880px) 36vw, 100vw',
							)
						);
						?>
					</div>
					<?php else : ?>
					<div class="testimonial-image testimonial-image--placeholder" role="presentation" aria-hidden="true">
						<span class="ph-label"><?php echo esc_html( $story['image_label'] ); ?></span>
					</div>
					<?php endif; ?>
				</div>
					<?php
				endforeach;
				?>
			</div>

			<div class="md-stories-copy-col">
				<?php
				foreach ( $stories as $i => $story ) :
					$is_first = 0 === $i;
					?>
				<div
					class="md-story-text"
					id="md-story-panel-<?php echo (int) $i; ?>"
					data-md-story="<?php echo (int) $i; ?>"
					<?php echo $is_first ? '' : 'hidden'; ?>
					role="group"
					aria-roledescription="<?php esc_attr_e( 'Slide', 'html5blank' ); ?>"
					aria-label="<?php echo esc_attr( sprintf( /* translators: %d: slide number */ __( 'Story %d', 'html5blank' ), $i + 1 ) ); ?>"
				>
					<p class="testimonial-quote"><?php echo esc_html( $story['quote'] ); ?></p>
					<div class="testimonial-attr">
						<?php if ( ! empty( $story['avatar_id'] ) ) : ?>
						<span class="av av--has-img">
							<?php
							echo wp_get_attachment_image(
								(int) $story['avatar_id'],
								'thumbnail',
								false,
								array(
									'class'    => 'av__img',
									'loading'  => 'lazy',
									'decoding' => 'async',
								)
							);
							?>
						</span>
						<?php else : ?>
						<span class="av av--placeholder" role="presentation" aria-hidden="true">
							<span class="av__initials"><?php echo esc_html( md_story_avatar_initials( $story['name'] ) ); ?></span>
						</span>
						<?php endif; ?>
						<div>
							<div class="nm"><?php echo esc_html( $story['name'] ); ?></div>
							<?php if ( $story['role'] !== '' ) : ?>
							<div class="rl"><?php echo esc_html( $story['role'] ); ?></div>
							<?php endif; ?>
						</div>
					</div>
				</div>
					<?php
				endforeach;
				?>

				<div class="testimonial-controls">
					<button type="button" class="md-story-prev" data-md-story-prev aria-controls="md-story-panel-0" aria-label="<?php esc_attr_e( 'Previous story', 'html5blank' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<line x1="20" y1="12" x2="4" y2="12" />
							<polyline points="10 6 4 12 10 18" />
						</svg>
					</button>
					<button type="button" class="md-story-next" data-md-story-next aria-controls="md-story-panel-0" aria-label="<?php esc_attr_e( 'Next story', 'html5blank' ); ?>">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<line x1="4" y1="12" x2="20" y2="12" />
							<polyline points="14 6 20 12 14 18" />
						</svg>
					</button>
					<span class="md-stories-counter" aria-live="polite">
						<?php
						printf(
							'%1$s / %2$s',
							esc_html( str_pad( (string) ( 1 ), 2, '0', STR_PAD_LEFT ) ),
							esc_html( str_pad( (string) $stories_total, 2, '0', STR_PAD_LEFT ) )
						);
						?>
					</span>
				</div>
			</div>
		</div>
	</div>
</section>

<?php
		endif;

		if ( md_contact_section_is_enabled() ) :
			if ( md_stories_section_is_enabled() || md_care_section_is_enabled() ) {
				md_render_road_divider();
			}
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
		endif;

	endwhile;
endif;

get_footer();
