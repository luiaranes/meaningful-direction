<?php
$display_banner = get_field('display_banner', 'option');
$bg_color = get_field('banner_background_color', 'option');
$font_color = get_field('font_color', 'option');
$banner_content = get_field('banner_content', 'option');

$md_home_base = trailingslashit(home_url('/'));
$logo_src = asset_path('images/logo.png');
$home_url = esc_url(home_url('/'));

$md_care_types = [
	[
		'page_slug' => 'assisted-living',
		'frag' => 'care-assisted',
		'label' => __('Assisted Living', 'html5blank'),
		'desc' => __('Daily support, with dignity.', 'html5blank'),
	],
	[
		'page_slug' => 'memory-care',
		'frag' => 'care-memory',
		'label' => __('Memory Care', 'html5blank'),
		'desc' => __('Specialized, secure programming.', 'html5blank'),
	],
	[
		'page_slug' => 'independent-living',
		'frag' => 'care-independent',
		'label' => __('Independent Living', 'html5blank'),
		'desc' => __('Active, social communities.', 'html5blank'),
	],
	[
		'page_slug' => 'skilled-nursing',
		'frag' => 'care-skilled',
		'label' => __('Skilled Nursing', 'html5blank'),
		'desc' => __('24-hour clinical care.', 'html5blank'),
	],
];
?>
<!doctype html>
<html <?php language_attributes(); ?> class="no-js">

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<title><?php wp_title(''); ?></title>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="wrapper">
		<?php if ($display_banner) : ?>
			<div class="notification-banner" style="background-color: <?php echo esc_attr($bg_color); ?>; color: <?php echo esc_attr($font_color); ?>">
				<?php echo $banner_content; ?>
				<span class="close-button"><i class="fas fa-times"></i></span>
			</div>
		<?php endif; ?>

		<nav class="nav md-site-nav is-initial-mount" aria-label="<?php esc_attr_e('Primary', 'html5blank'); ?>">
			<div class="nav-inner">
				<a href="<?php echo $home_url; ?>" class="logo logo-mark md-site-logo" aria-label="<?php esc_attr_e('Meaningful Direction Senior Placement', 'html5blank'); ?>">
					<img src="<?php echo esc_url($logo_src); ?>" alt="<?php esc_attr_e('Meaningful Direction Senior Placement', 'html5blank'); ?>">
				</a>

				<div class="nav-links">
					<a href="<?php echo esc_url($md_home_base . '#process'); ?>"><?php esc_html_e('Our Process', 'html5blank'); ?></a>

					<div class="nav-dropdown" data-md-dropdown>

						<button type="button" class="nav-dropdown-trigger md-dropdown-trigger"
							data-md-dropdown-trigger aria-expanded="false" aria-haspopup="true">

							<?php esc_html_e('Care Types', 'html5blank'); ?>

							<svg width="10" height="6" viewBox="0 0 10 6" fill="none"
								stroke="currentColor" stroke-width="1.4"
								stroke-linecap="round" stroke-linejoin="round"
								class="nav-dropdown-trigger-chevron" aria-hidden="true">

								<polyline points="1 1 5 5 9 1" />
							</svg>

						</button>

						<div class="nav-dropdown-panel md-dropdown-panel"
							id="care-types-submenu" role="menu" aria-hidden="true">

							<div class="nav-dropdown-eyebrow"><?php esc_html_e('Levels of Care', 'html5blank'); ?></div>

							<?php foreach ($md_care_types as $c) : ?>
								<a href="<?php echo esc_url(md_care_type_nav_url($c['page_slug'], $c['frag'])); ?>" class="nav-dropdown-item" role="menuitem">
									<span class="nav-dropdown-item-label"><?php echo esc_html($c['label']); ?></span>

									<span class="nav-dropdown-item-desc"><?php echo esc_html($c['desc']); ?></span>

								</a>
							<?php endforeach; ?>

							<?php
								$_md_care_arc = '';
								if (function_exists('md_care_type_post_type')) {
									$_ar           = get_post_type_archive_link(md_care_type_post_type());
									$_md_care_arc = is_string($_ar) && $_ar !== '' ? $_ar : '';
								}
								if ($_md_care_arc === '') {
									$_md_care_arc = $md_home_base . '#care';
								}
							?>

							<a href="<?php echo esc_url($_md_care_arc); ?>" class="nav-dropdown-all md-dropdown-see-all">

								<?php esc_html_e('See all care types', 'html5blank'); ?>

								<svg width="12" height="12" viewBox="0 0 24 24" fill="none"
									stroke="currentColor" stroke-width="1.4"
									stroke-linecap="round" stroke-linejoin="round"
									aria-hidden="true">

									<line x1="4" y1="12" x2="20" y2="12" />
									<polyline points="14 6 20 12 14 18" />
								</svg>

							</a>

						</div>

					</div>

					<a href="<?php echo esc_url($md_home_base . '#stories'); ?>"><?php esc_html_e('Stories', 'html5blank'); ?></a>

					<a href="<?php echo esc_url(md_nav_url_page_or_hash('about', 'about')); ?>"><?php esc_html_e('About', 'html5blank'); ?></a>

					<a class="btn btn-primary" href="<?php echo esc_url(md_nav_url_page_or_hash('contact-us', 'contact-us')); ?>"><?php esc_html_e('Free Consultation', 'html5blank'); ?></a>

				</div>

				<button type="button"
					class="nav-mobile-toggle md-nav-mobile-toggle"
					aria-expanded="false"
					aria-controls="md-primary-mobile-panel">

					<svg class="nav-icon-menu" width="22" height="22" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="1.4"
						stroke-linecap="round" aria-hidden="true">

						<line x1="4" y1="8" x2="20" y2="8" />

						<line x1="4" y1="16" x2="20" y2="16" />
					</svg>

					<svg class="nav-icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none"
						stroke="currentColor" stroke-width="1.4"
						stroke-linecap="round" aria-hidden="true">

						<line x1="6" y1="6" x2="18" y2="18" />

						<line x1="18" y1="6" x2="6" y2="18" />
					</svg>

				</button>

			</div>

			<div class="nav-mobile-panel md-mobile-panel fade-in"
				id="md-primary-mobile-panel" hidden aria-hidden="true">

				<a href="<?php echo esc_url($md_home_base . '#process'); ?>" class="md-mobile-link"><?php esc_html_e('Our Process', 'html5blank'); ?></a>

				<div class="md-mobile-section-label"><?php esc_html_e('Care Types', 'html5blank'); ?></div>

				<?php foreach ($md_care_types as $c) : ?>
					<a href="<?php echo esc_url(md_care_type_nav_url($c['page_slug'], $c['frag'])); ?>" class="md-mobile-care-link">&mdash;

						<?php echo esc_html($c['label']); ?>

					</a>

				<?php endforeach; ?>

				<a href="<?php echo esc_url($md_home_base . '#stories'); ?>" class="md-mobile-link"><?php esc_html_e('Stories', 'html5blank'); ?></a>

				<a href="<?php echo esc_url(md_nav_url_page_or_hash('about', 'about')); ?>" class="md-mobile-link"><?php esc_html_e('About', 'html5blank'); ?></a>

				<a href="<?php echo esc_url(md_nav_url_page_or_hash('contact-us', 'contact-us')); ?>" class="btn btn-primary md-mobile-cta"><?php esc_html_e('Free Consultation', 'html5blank'); ?></a>

			</div>

		</nav>
