<?php
/**
 * Single Care Type CPT — Assisted Living standalone prototype parity.
 */

get_header();

if (!have_posts()) {
	get_footer();
	return;
}

while (have_posts()) :
	the_post();
	$page_id       = (int) get_the_ID();
	$post_id       = $page_id;
	$D             = md_care_type_resolve($post_id);

	$hero_image_raw = is_array($D['hero_image'] ?? null) ? $D['hero_image'] : array();
	$hero_has_image_id = !empty($hero_image_raw['ID']);
	$hero_image_url    = !empty($hero_image_raw['url']) ? (string) $hero_image_raw['url'] : '';

	$what_image_raw = is_array($D['what_image'] ?? null) ? $D['what_image'] : array();
	$what_has_id = ! empty($what_image_raw['ID']);
	$what_url    = ! empty($what_image_raw['url']) ? (string) $what_image_raw['url'] : '';

	$contact_h2_allow = array( 'br' => array() );

	$back_href_raw = trim((string) ($D['back_url'] ?? ''));
	if ($back_href_raw === '') {
		$back_href_raw = md_care_type_archive_url();
	}

	$primary_cta = md_hero_normalize_link(
		md_get_care_type_field('care_tp_primary_cta_link', array( 'url' => '#contact' ), $post_id),
		__('Talk with an advocate', 'html5blank'),
		'contact'
	);
	$secondary_cta = md_hero_normalize_link(
		md_get_care_type_field('care_tp_secondary_cta_link', array( 'url' => '#how-we-help' ), $post_id),
		__('See how we help', 'html5blank'),
		'how-we-help'
	);

	$contact_eyebrow  = md_get_home_hero_field('contact_eyebrow', __('Free Consultation', 'html5blank'));
	$contact_headline = md_get_home_hero_field(
		'contact_headline',
		__('You don\'t have to carry this<br />without help.', 'html5blank')
	);
	$contact_intro    = md_get_home_hero_field(
		'contact_intro',
		__(
			'Let’s begin with conversation—tell us briefly about your loved one and where you’ve been stuck.',
			'html5blank'
		)
	);
	$contact_phone   = md_get_home_hero_field('contact_phone', '(772) 555-0142');
	$contact_email   = md_get_home_hero_field('contact_email', 'hello@meaningfuldirection.com');
	$contact_serving = md_get_home_hero_field(
		'contact_serving',
		__('Families across Indian River County and the Treasure Coast.', 'html5blank')
	);
?>

<main class="md-single-care-type md-care-type-shell">

<div class="md-care-type-return-strip">
	<div class="container">
		<a class="md-care-type-back" href="<?php echo esc_url($back_href_raw); ?>">
			<svg width="14" height="14" viewBox="0 0 24 24" fill="none"
				stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<line x1="20" y1="12" x2="4" y2="12" />
				<polyline points="10 6 4 12 10 18" />
			</svg>
			<span><?php esc_html_e('Back to all care types', 'html5blank'); ?></span>
		</a>
	</div>
</div>

<section class="hero md-site-hero" id="top">

	<div class="container hero-grid">

		<div class="hero-copy">

			<p class="eyebrow"><?php echo esc_html($D['hero_eyebrow']); ?></p>

			<h1 class="h1">
				<?php echo esc_html($D['hero_headline_before']); ?><span class="em"><?php echo esc_html($D['hero_headline_emphasis']); ?></span><?php echo esc_html($D['hero_headline_after']); ?>
			</h1>

			<p class="hero-sub"><?php echo nl2br(esc_html($D['hero_subcopy'])); ?></p>

			<div class="hero-ctas">
				<a class="btn btn-primary" href="<?php echo esc_url($primary_cta['href_raw']); ?>"<?php echo md_hero_link_target_attr_html($primary_cta['target']); ?>>
					<?php echo esc_html($primary_cta['title']); ?>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<line x1="4" y1="12" x2="20" y2="12" />
						<polyline points="14 6 20 12 14 18" />
					</svg>
				</a>
				<a class="btn btn-ghost" href="<?php echo esc_url($secondary_cta['href_raw']); ?>"<?php echo md_hero_link_target_attr_html($secondary_cta['target']); ?>>
					<?php echo esc_html($secondary_cta['title']); ?>
				</a>
			</div>

			<div class="hero-meta">
				<?php foreach ($D['stats'] as $st) : ?>
					<div class="hero-meta-item">
						<div class="num"><?php echo esc_html($st['num']); ?></div>
						<div class="lbl"><?php echo esc_html($st['lbl']); ?></div>
					</div>
				<?php endforeach; ?>
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
					$h_alt = '';
					if (!empty($hero_image_raw['alt'])) {
						$h_alt = (string) $hero_image_raw['alt'];
					} elseif (! empty($hero_image_raw['title'])) {
						$h_alt = (string) $hero_image_raw['title'];
					}
					?>
					<img class="hero-image__img" src="<?php echo esc_url($hero_image_url); ?>"
						alt="<?php echo esc_attr($h_alt); ?>"
						decoding="async" loading="lazy" />
				</div>
			<?php else : ?>
				<div class="placeholder hero-image" role="presentation" aria-hidden="true">
					<span class="ph-label"><?php echo esc_html($D['hero_placeholder']); ?></span>
				</div>
			<?php endif; ?>

			<div class="accent-card">
				<p class="quote"><?php echo nl2br(esc_html($D['hero_quote_text'])); ?></p>
				<div class="src"><?php echo esc_html($D['hero_quote_src']); ?></div>
			</div>

		</div>

	</div>

</section>

<?php md_render_road_divider(); ?>

<section class="mission md-site-mission md-care-type-pillars" aria-label="<?php esc_attr_e('Care overview', 'html5blank'); ?>">
	<div class="container mission-grid">
		<?php foreach ($D['pillars'] as $pill) :
			$kses = isset($pill['_kses']) && is_array($pill['_kses']) ? $pill['_kses'] : md_care_type_m_allowed();
			?>
		<div class="mission-block">
			<div class="mission-label"><?php echo esc_html($pill['label']); ?></div>
			<p class="mission-text"><?php echo wp_kses((string) $pill['text'], $kses); ?></p>
		</div>
			<?php
		endforeach; ?>
	</div>
</section>

<?php md_render_road_divider(); ?>

<section class="ct-what md-site-about-story md-care-type-story" id="what-it-is">
	<div class="container ct-what-grid">

		<div class="ct-what-image-wrap">
			<?php if ($what_has_id) : ?>
				<div class="ct-what-image hero-image">
					<?php
					echo wp_get_attachment_image(
						(int) $what_image_raw['ID'],
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
			<?php elseif ($what_url !== '') : ?>
				<div class="ct-what-image hero-image">
					<?php
					$wat = '';
					if (!empty($what_image_raw['alt'])) {
						$wat = (string) $what_image_raw['alt'];
					} elseif (!empty($what_image_raw['title'])) {
						$wat = (string) $what_image_raw['title'];
					}
					?>
					<img class="hero-image__img" src="<?php echo esc_url($what_url); ?>"
						alt="<?php echo esc_attr($wat); ?>"
						decoding="async" loading="lazy" />
				</div>
			<?php else : ?>
				<div class="placeholder ct-what-image hero-image" role="presentation" aria-hidden="true">
					<span class="ph-label"><?php echo esc_html($D['what']['placeholder']); ?></span>
				</div>
			<?php endif; ?>
		</div>

		<div class="ct-what-body">
			<p class="eyebrow"><?php echo esc_html($D['what']['eyebrow']); ?></p>

			<h2 class="h2">
				<?php echo esc_html($D['what']['h2_before']); ?><span class="em"><?php echo esc_html($D['what']['h2_em']); ?></span><?php echo esc_html($D['what']['h2_after']); ?>
			</h2>

			<div class="ct-what-paragraphs md-care-type-story__paragraphs">
				<p class="p"><?php echo nl2br(esc_html($D['what']['p1'])); ?></p>
				<p class="p"><?php echo nl2br(esc_html($D['what']['p2'])); ?></p>
				<?php
				$care_body_extra = trim((string) get_post_field('post_content', $post_id));
				if ($care_body_extra !== '') :
					?>
				<div class="md-care-type-extra-content"><?php echo apply_filters('the_content', $care_body_extra); ?></div>
					<?php
				endif;
				?>
			</div>

			<div class="md-care-type-facets md-care-type-facets-grid" role="list">
				<?php foreach ($D['what']['facets'] as $fx) : ?>
					<div class="md-care-type-facet-card" role="listitem">
						<h4><?php echo esc_html($fx['h']); ?></h4>
						<p><?php echo esc_html($fx['p']); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

	</div>

</section>

<?php md_render_road_divider(); ?>

<section class="ct-signs md-site-about-values md-care-type-signs" id="signals" aria-label="<?php esc_attr_e('When to rethink care needs', 'html5blank'); ?>">
	<div class="container">
		<div class="ct-signs-head">
			<div>
				<p class="eyebrow"><?php echo esc_html($D['signs_head']['eyebrow']); ?></p>
				<h2 class="h2">
					<?php echo esc_html($D['signs_head']['h2_before']); ?><span class="em"><?php echo esc_html($D['signs_head']['h2_em']); ?></span><?php echo esc_html($D['signs_head']['h2_after']); ?>
				</h2>
			</div>
			<p class="p ct-signs-intro"><?php echo nl2br(esc_html($D['signs_intro'])); ?></p>
		</div>

		<div class="ct-signs-grid" role="list">
			<?php foreach ($D['sign_items'] as $it) : ?>
				<div class="ct-sign-card" role="listitem">
					<span class="ct-sign-num"><?php echo esc_html($it['n']); ?></span>
					<h4><?php echo esc_html($it['h']); ?></h4>
					<p><?php echo esc_html($it['p']); ?></p>
				</div>
			<?php endforeach; ?>
		</div>

	</div>
</section>

<?php md_render_road_divider(); ?>

<section id="how-we-help" class="md-site-process md-care-type-help" aria-label="<?php esc_attr_e('How we guide families through this decision', 'html5blank'); ?>">
	<div class="container">
		<div class="process-head">
			<div>
				<p class="eyebrow"><?php echo esc_html($D['help_head']['eyebrow']); ?></p>
				<h2 class="h2 process-h2">
					<?php echo esc_html($D['help_head']['h2_before']); ?><span class="em"><?php echo esc_html($D['help_head']['h2_em']); ?></span><?php echo esc_html($D['help_head']['h2_after']); ?>
				</h2>
			</div>
			<p class="process-intro"><?php echo nl2br(esc_html($D['help_intro'])); ?></p>
		</div>

		<div class="process-tabs">

			<div class="step-rail" role="tablist" aria-label="<?php esc_attr_e('How we help tabs', 'html5blank'); ?>">
				<?php
				foreach ($D['help_steps'] as $i => $step) :
					$tab_id   = 'md-ct-help-tab-' . $i;
					$panel_id = 'md-ct-help-panel-' . $i;
					?>
				<button
					type="button"
					class="step-tab<?php echo $i === 0 ? ' active' : ''; ?>"
					id="<?php echo esc_attr($tab_id); ?>"
					role="tab"
					aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
					aria-controls="<?php echo esc_attr($panel_id); ?>"
					data-md-process-tab="<?php echo (int) $i; ?>"
				>
					<span class="num"><?php echo esc_html($step['n']); ?></span>
					<span class="ttl"><?php echo esc_html($step['title']); ?></span>
				</button>
					<?php
				endforeach;
				?>
			</div>

			<div class="process-panels">
				<?php foreach ($D['help_steps'] as $i => $step) : ?>
					<?php
						$tab_id   = 'md-ct-help-tab-' . $i;
						$panel_id = 'md-ct-help-panel-' . $i;
						$bulls    = isset($step['bullets']) && is_array($step['bullets']) ? $step['bullets'] : array();
					?>
				<div
					class="step-panel"
					id="<?php echo esc_attr($panel_id); ?>"
					role="tabpanel"
					aria-labelledby="<?php echo esc_attr($tab_id); ?>"
					data-md-process-panel="<?php echo (int) $i; ?>"
					<?php echo $i !== 0 ? 'hidden' : ''; ?>
				>
					<div class="step-panel-head">
						<span class="pn"><?php printf( esc_html__('Step %s', 'html5blank'), esc_html($step['n']) ); ?></span>
						<span class="pl"><?php echo esc_html($step['label']); ?></span>
					</div>
					<h3><?php echo esc_html($step['title']); ?></h3>
					<p class="p desc"><?php echo esc_html($step['desc']); ?></p>
					<div class="bullets">
						<?php foreach ($bulls as $bullet) : ?>
						<div class="step-bullet">
							<span class="dot" aria-hidden="true"></span>
							<div>
								<h4><?php echo esc_html($bullet['h']); ?></h4>
								<p><?php echo esc_html($bullet['p']); ?></p>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endforeach; ?>

			</div>

		</div>

	</div>

</section>

<?php md_render_road_divider(); ?>

<?php
$other_q = new WP_Query(array(
	'post_type'      => md_care_type_post_type(),
	'posts_per_page' => -1,
	'post__not_in'   => array($post_id),
	'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
	'no_found_rows'  => true,
));

if ($other_q->have_posts()) :

	?>

<section class="ct-other" aria-label="<?php esc_attr_e('Other levels of care', 'html5blank'); ?>">
	<div class="container">
		<div class="ct-other-head">
			<p class="eyebrow"><?php echo esc_html($D['other_eyebrow']); ?></p>
			<h2 class="h2">
				<?php echo esc_html($D['other_h2_bf']); ?><span class="em"><?php echo esc_html($D['other_h2_em']); ?></span><?php echo esc_html($D['other_h2_af']); ?>
			</h2>
			<p class="p ct-other-intro"><?php echo nl2br(esc_html($D['other_intro'])); ?></p>
		</div>

		<div class="ct-other-grid" role="list">
			<?php while ($other_q->have_posts()) : $other_q->the_post();
				$oid    = get_the_ID();
				$blurb  = '';
				if (function_exists('get_field')) {
					$blurb_raw = get_field('care_tp_cross_short_desc', $oid);
					$blurb     = is_string($blurb_raw) ? trim($blurb_raw) : '';
				}
				if ($blurb === '') {
					$blurb = wp_strip_all_tags(get_the_excerpt($other_q->post));
				}
				?>
				<a class="ct-other-card" role="listitem" href="<?php echo esc_url(get_permalink()); ?>">
					<div class="ct-other-card-body">
						<h4><?php the_title(); ?></h4>
						<p><?php echo esc_html($blurb !== '' ? $blurb : __('Explore this level further.', 'html5blank')); ?></p>
					</div>
					<span class="ct-other-card-arrow" aria-hidden="true">
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none"
							stroke="currentColor" stroke-width="1.4"
							stroke-linecap="round" stroke-linejoin="round">

							<line x1="4" y1="12" x2="20" y2="12" />
							<polyline points="14 6 20 12 14 18" />
						</svg>
					</span>
				</a>

				<?php
				endwhile;
				wp_reset_postdata();
				?>

		</div>
	</div>
</section>

	<?php
	md_render_road_divider();
endif;

include get_template_directory() . '/template-parts/front-contact.php';

?>

</main>

<?php
endwhile;
get_footer();
