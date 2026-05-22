<?php

$md_footer_base = trailingslashit(home_url('/'));

$footer_logo_src = asset_path('images/logo.png');


$privacy_url = (function_exists('get_privacy_policy_url') && get_privacy_policy_url())
	? get_privacy_policy_url()
	: '#';

/** Care service links — CPT singles via {@see md_care_type_nav_url()}; same order as primary nav. */
$md_footer_services = [
	['slug' => 'assisted-living', 'frag' => 'care-assisted', 'label' => __('Assisted Living', 'html5blank')],
	['slug' => 'memory-care', 'frag' => 'care-memory', 'label' => __('Memory Care', 'html5blank')],
	['slug' => 'independent-living', 'frag' => 'care-independent', 'label' => __('Independent Living', 'html5blank')],
	['slug' => 'skilled-nursing', 'frag' => 'care-skilled', 'label' => __('Skilled Nursing', 'html5blank')],
];

$md_footer_see_all_care = function_exists('md_care_type_archive_url')
	? md_care_type_archive_url()
	: $md_footer_base . '#care';

/** Dedicated pages / section fallbacks {@see md_nav_url_page_or_hash()} — aligns with primary nav where applicable. */
$md_footer_contact_url = md_nav_url_page_or_hash('contact-us', 'contact-us');
$md_footer_phone       = function_exists('md_get_home_hero_field')
	? md_get_home_hero_field('contact_phone', '(772) 555-0142')
	: '(772) 555-0142';
$md_footer_email = function_exists('md_get_home_hero_field')
	? md_get_home_hero_field('contact_email', 'hello@meaningfuldirection.com')
	: 'hello@meaningfuldirection.com';
$md_footer_serving = function_exists('md_get_home_hero_field')
	? md_get_home_hero_field(
		'contact_serving',
		__('Vero Beach · Sebastian · Indian River County', 'html5blank')
	)
	: __('Vero Beach · Sebastian · Indian River County', 'html5blank');

$md_footer_mail_href = 'mailto:' . sanitize_email($md_footer_email);
if (!is_email($md_footer_email)) {
	$md_footer_mail_href = '#';
}
$md_footer_tel_href = function_exists('md_contact_tel_href')
	? md_contact_tel_href($md_footer_phone)
	: '#';

$md_footer_about_page = get_page_by_path('about');
$md_footer_mission_url = ($md_footer_about_page instanceof WP_Post)
	? trailingslashit(get_permalink($md_footer_about_page)) . '#about'
	: $md_footer_base . '#about';
?>
<!-- footer -->

<footer class="footer md-site-footer" role="contentinfo">

	<div class="container">

		<div class="footer-grid">

			<div class="footer-brand">

				<a href="<?php echo esc_url(home_url('/')); ?>" class="logo logo-mark logo-footer" aria-label="<?php esc_attr_e('Meaningful Direction Senior Placement', 'html5blank'); ?>">

					<img src="<?php echo esc_url($footer_logo_src); ?>" alt="<?php esc_attr_e('Meaningful Direction Senior Placement', 'html5blank'); ?>">

				</a>

				<p class="footer-tag"><?php esc_html_e('A senior living search guided by love, not lists.', 'html5blank'); ?></p>

			</div>

			<div class="footer-col">

				<div class="ttl"><?php esc_html_e('Services', 'html5blank'); ?></div>

				<ul>

					<?php foreach ($md_footer_services as $svc) :
						$svc_href = function_exists('md_care_type_nav_url')
							? md_care_type_nav_url($svc['slug'], $svc['frag'])
							: md_nav_url_page_or_hash($svc['slug'], $svc['frag']);
						?>
						<li>

							<a href="<?php echo esc_url($svc_href); ?>"><?php echo esc_html($svc['label']); ?></a>

						</li>
					<?php endforeach; ?>

					<li>
						<a href="<?php echo esc_url($md_footer_see_all_care); ?>"><?php esc_html_e('See all care types', 'html5blank'); ?></a>
					</li>
				</ul>

			</div>

			<div class="footer-col">

				<div class="ttl"><?php esc_html_e('About', 'html5blank'); ?></div>

				<ul>

					<li><a href="<?php echo esc_url(md_nav_url_page_or_hash('about', 'about')); ?>"><?php esc_html_e('About Us', 'html5blank'); ?></a></li>

					<li><a href="<?php echo esc_url($md_footer_mission_url); ?>"><?php esc_html_e('Our Mission', 'html5blank'); ?></a></li>

					<li><a href="<?php echo esc_url($md_footer_base . '#process'); ?>"><?php esc_html_e('Our Process', 'html5blank'); ?></a></li>

					<li><a href="<?php echo esc_url($md_footer_base . '#stories'); ?>"><?php esc_html_e('Family Stories', 'html5blank'); ?></a></li>

					<li><a href="<?php echo esc_url($md_footer_contact_url); ?>"><?php esc_html_e('Contact', 'html5blank'); ?></a></li>

				</ul>

			</div>

			<div class="footer-col">

				<div class="ttl"><?php esc_html_e('Get in Touch', 'html5blank'); ?></div>

				<ul>

					<li><a href="<?php echo esc_url($md_footer_tel_href); ?>"><?php echo esc_html($md_footer_phone); ?></a></li>

					<li><a href="<?php echo esc_url($md_footer_mail_href); ?>"><?php echo esc_html($md_footer_email); ?></a></li>

					<li><?php echo esc_html($md_footer_serving); ?></li>

				</ul>

			</div>

		</div>

		<div class="footer-base">

			<div>
				<?php
				printf(
					/* translators: 1: year, 2: site name */
					esc_html__('© %1$s %2$s. All rights reserved.', 'html5blank'),

					esc_html(gmdate('Y')),

					esc_html(get_bloginfo('name'))
				);
				?>
			</div>

			<div class="links">

				<a href="<?php echo esc_url($privacy_url); ?>"><?php esc_html_e('Privacy', 'html5blank'); ?></a>

				<a href="<?php echo esc_url(md_nav_url_page_or_hash('terms', 'terms')); ?>"><?php esc_html_e('Terms', 'html5blank'); ?></a>

				<a href="<?php echo esc_url(md_nav_url_page_or_hash('accessibility', 'accessibility')); ?>"><?php esc_html_e('Accessibility', 'html5blank'); ?></a>

			</div>

		</div>

	</div>

</footer>

<!-- /footer -->

</div>
<!-- /wrapper -->

<?php wp_footer(); ?>

</body>

</html>
