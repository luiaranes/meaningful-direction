<?php
/*
 *  Author: Todd Motto | @toddmotto
 *  URL: sperling.com | @sperling
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------*\
    Theme Support
\*------------------------------------*/
if (! isset($content_width)) {
    $content_width = 900;
}
if (function_exists('add_theme_support')) {
    // Add Thumbnail Theme Support.
    add_theme_support('post-thumbnails');
    //add_image_size( 'custom-size', 700, 200, true ); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Enables post and comment RSS feed links to head.
    add_theme_support('automatic-feed-links');
    // Enable HTML5 support.
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));
}

// Localisation Support - Load at init to avoid translation errors
add_action('init', function() {
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
});

/*------------------------------------*\
	Functions
\*------------------------------------*/

require_once get_template_directory() . '/inc/md-care-type-cpt.php';
require_once get_template_directory() . '/inc/md-care-type.php';
require_once get_template_directory() . '/inc/md-front-process.php';
require_once get_template_directory() . '/inc/md-front-care.php';
require_once get_template_directory() . '/inc/md-front-stories.php';
require_once get_template_directory() . '/inc/md-front-contact.php';
require_once get_template_directory() . '/inc/md-about.php';
require_once get_template_directory() . '/inc/md-contact-page.php';
require_once get_template_directory() . '/inc/acf-location-md-page-slug.php';
require_once get_template_directory() . '/inc/md-acf-about-fields.php';
require_once get_template_directory() . '/inc/md-acf-contact-fields.php';
require_once get_template_directory() . '/inc/md-acf-care-type-fields.php';

// Dump & Die (echo provide code to the screen and halt execution of the script to debug)
function dd($code)
{
    echo '<pre>';
    var_dump($code);
    echo '</pre>';
    die();
}

// Move Yoast to bottom
function yoasttobottom()
{
    return 'low';
}
add_filter('wpseo_metabox_prio', 'yoasttobottom');

/** Resolve care-type URL: WP page slug if exists, else home hash for section anchor. */
function md_nav_url_page_or_hash(string $page_slug, string $fragment_without_hash): string
{
    $post = get_page_by_path($page_slug);
    if ($post instanceof WP_Post) {
        return get_permalink($post);
    }

    return trailingslashit(home_url('/')) . '#' . ltrim($fragment_without_hash, '#');
}

/** Static front page ID (Reading → Homepage). */
function md_home_hero_post_id(): int
{
    return (int) get_option('page_on_front');
}

/**
 * Read hero ACF fields from synced front page; empty string returns $fallback.

 */
function md_get_home_hero_field(string $name, $fallback = '')
{
    if (!function_exists('get_field')) {
        return $fallback;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return $fallback;
    }

    $value = get_field($name, $page_id);
    if ($value === null || $value === false || $value === '') {

        return $fallback;
    }
    if (
        is_array($value)
        && str_ends_with($name, '_link')
        && trim((string) ($value['url'] ?? '')) === ''
    ) {
        return $fallback;
    }
    return $value;
}


/** Fragment for home URL hashes (sanitize for output in esc_url()). */
function md_hero_sanitize_fragment(string $fragment): string
{
    return sanitize_title(trim($fragment));
}

/** Resolved CTA href (not HTML-escaped; use esc_url() when printing). */
function md_hero_resolve_cta_href_raw(string $value, string $fragment_fallback): string
{
    $base = trailingslashit(home_url('/'));

    $value = trim($value);

    $fallback_frag = md_hero_sanitize_fragment(ltrim($fragment_fallback, '#'));
    if ($fallback_frag === '') {
        $clean = preg_replace('/[^\w-]/', '', ltrim((string) $fragment_fallback, '#'));
        $fallback_frag = $clean ?: 'contact';
    }

    if ($value === '') {
        return $base . '#' . $fallback_frag;
    }

    $first = $value[0];

    if ($first === '#' && strlen($value) > 1) {
        $frag = md_hero_sanitize_fragment(substr($value, 1));
        if ($frag === '') {
            $frag = $fallback_frag;
        }
        return $base . '#' . $frag;
    }

    if ($first === '#') {
        return $base . '#' . $fallback_frag;
    }

    if ($first === '/') {
        return home_url($value);
    }

    if (strlen($value) > 2 && strpos($value, '//') === 0) {
        return $value;
    }

    if (preg_match('#^[a-z][a-z0-9+.-]*:/#i', $value)) {
        return $value;
    }

    return $base . '#' . md_hero_sanitize_fragment($value);
}

/** Escape helper for validated target attribute (acf link). */
function md_hero_link_target_attr_html(string $target): string
{
    $allowed = array('_blank', '_self', '_parent', '_top');
    if ('' === $target || !in_array($target, $allowed, true)) {
        return '';
    }
    $attr = sprintf(' target="%s"', esc_attr($target));

    return '_blank' === $target ? $attr . ' rel="noopener noreferrer"' : $attr;
}

/** See md_hero_resolve_cta_href_raw / esc_url output. */
function md_hero_resolve_cta_url(string $value, string $fragment_fallback): string
{
    return esc_url(md_hero_resolve_cta_href_raw($value, $fragment_fallback));
}

/** ACF link field (array) → href_raw + label + extra attributes for anchors. */
function md_hero_normalize_link($acf_link, string $fallback_title, string $fragment_fallback): array
{
    $url_raw = '';
    $title = $fallback_title;
    $target = '';
    if (is_array($acf_link)) {
        $url_raw = trim((string) ($acf_link['url'] ?? ''));
        $titled = isset($acf_link['title']) ? trim((string) $acf_link['title']) : '';
        if ($titled !== '') {
            $title = $titled;
        }
        $target = isset($acf_link['target']) ? trim((string) $acf_link['target']) : '';
    }

    return array(
        'href_raw' => md_hero_resolve_cta_href_raw($url_raw, $fragment_fallback),
        'title'    => $title,
        'target'   => $target,
    );
}

/**
 * Respect "Show hero" on the front page. If ACF is absent or unset, defaults to visible.
 */
function md_home_hero_is_enabled(): bool
{
    if (!function_exists('get_field')) {
        return true;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return true;
    }
    $raw = get_field('hero_enable', $page_id);
    if ($raw === null || $raw === '') {
        return true;
    }

    return (bool) filter_var($raw, FILTER_VALIDATE_BOOLEAN);
}

/** Mission & vision strip on static front page. Unchecked in ACF hides strip + its road dividers. */
function md_mission_vision_is_enabled(): bool
{
    if (!function_exists('get_field')) {
        return true;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return true;
    }
    $raw = get_field('mission_vision_enable', $page_id);
    if ($raw === null || $raw === '') {
        return true;
    }

    return (bool) filter_var($raw, FILTER_VALIDATE_BOOLEAN);
}

/** Our Process (#process) on static front page. Unchecked in ACF hides section (roads stay consistent). */
function md_process_section_is_enabled(): bool
{
    if (!function_exists('get_field')) {
        return true;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return true;
    }
    $raw = get_field('process_enable', $page_id);
    if ($raw === null || $raw === '') {
        return true;
    }

    return (bool) filter_var($raw, FILTER_VALIDATE_BOOLEAN);
}

/** Levels of care (#care) on static front page. */
function md_care_section_is_enabled(): bool
{
    if (!function_exists('get_field')) {
        return true;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return true;
    }
    $raw = get_field('care_enable', $page_id);
    if ($raw === null || $raw === '') {
        return true;
    }

    return (bool) filter_var($raw, FILTER_VALIDATE_BOOLEAN);
}

/** Family stories (#stories) on static front page. */
function md_stories_section_is_enabled(): bool
{
    if (!function_exists('get_field')) {
        return true;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return true;
    }
    $raw = get_field('stories_enable', $page_id);
    if ($raw === null || $raw === '') {
        return true;
    }

    return (bool) filter_var($raw, FILTER_VALIDATE_BOOLEAN);
}

/** Free consultation (#contact) on static front page. */
function md_contact_section_is_enabled(): bool
{
    if (!function_exists('get_field')) {
        return true;
    }
    $page_id = md_home_hero_post_id();
    if (!$page_id) {
        return true;
    }
    $raw = get_field('contact_enable', $page_id);
    if ($raw === null || $raw === '') {
        return true;
    }

    return (bool) filter_var($raw, FILTER_VALIDATE_BOOLEAN);
}

/** Decorative wavy road lines (prototype RoadDivider). Unique SVG gradient IDs per call. */
function md_render_road_divider(): void
{
    if (function_exists('wp_unique_id')) {
        $uid_gold = esc_attr(wp_unique_id('md-road-g-'));
        $uid_blue = esc_attr(wp_unique_id('md-road-b-'));
    } else {
        $uid_gold = esc_attr('md-road-g-' . uniqid('', true));
        $uid_blue = esc_attr('md-road-b-' . uniqid('', true));
    }
    ?>
<div class="road road-section md-road-divider" aria-hidden="true">
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120" preserveAspectRatio="none">
		<defs>
			<linearGradient id="<?php echo $uid_gold; ?>" x1="0" x2="1" y1="0" y2="0">
				<stop offset="0" stop-color="#D6A254" stop-opacity="0"/>
				<stop offset="0.15" stop-color="#D6A254" stop-opacity="1"/>
				<stop offset="0.85" stop-color="#D6A254" stop-opacity="1"/>
				<stop offset="1" stop-color="#D6A254" stop-opacity="0"/>
			</linearGradient>
			<linearGradient id="<?php echo $uid_blue; ?>" x1="0" x2="1" y1="0" y2="0">
				<stop offset="0" stop-color="#8BA8C4" stop-opacity="0"/>
				<stop offset="0.15" stop-color="#8BA8C4" stop-opacity="1"/>
				<stop offset="0.85" stop-color="#8BA8C4" stop-opacity="1"/>
				<stop offset="1" stop-color="#8BA8C4" stop-opacity="0"/>
			</linearGradient>
		</defs>
		<path
			d="M 0 70 C 180 30, 360 100, 540 60 S 900 20, 1080 70 S 1260 100, 1440 50"
			fill="none"
			stroke="url(#<?php echo $uid_gold; ?>)"
			stroke-width="2.5"
			stroke-linecap="round"
			stroke-dasharray="1 8"
		/>
		<path
			d="M 0 80 C 180 40, 360 110, 540 70 S 900 30, 1080 80 S 1260 110, 1440 60"
			fill="none"
			stroke="url(#<?php echo $uid_blue; ?>)"
			stroke-width="2.5"
			stroke-linecap="round"
			stroke-dasharray="1 8"
		/>
	</svg>
</div>
	<?php
}

// Sperling navigation
function sperling_nav()
{
    wp_nav_menu(
        array(
            'theme_location'  => 'header-menu',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul>%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        )
    );
}

// Load Sperling scripts (header.php)
function sperling_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
        wp_enqueue_script('sperling/js', asset_path('js/app.min.js'), ['jquery'], null, true);
    }
}

// Load conditional scripts
function sperling_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        // Font Awesome: https://fontawesome.com/
        wp_register_script('fontawesome', 'https://kit.fontawesome.com/27172d085a.js', array('jquery'));
        wp_enqueue_script('fontawesome'); // Enqueue it!
    }
}

/** Single-open accordion for contact page FAQ (<details>). */
function md_enqueue_contact_faq_accordion(): void
{
    if (is_admin() || !is_page('contact-us')) {
        return;
    }
    wp_enqueue_script(
        'md-contact-faq-accordion',
        get_template_directory_uri() . '/assets/js/md-contact-faq-accordion.js',
        array(),
        '1.0.0',
        true
    );
}

add_action('wp_enqueue_scripts', 'md_enqueue_contact_faq_accordion');

// Enables Google Maps API access in Wordpress Backend
function my_acf_google_map_api($api)
{
    $api['key'] = 'AIzaSyD6z4Jo2z4ongqx8njcstKYNJHNdHDh0FQ';
    return $api;
}

/**
 * Copied this code from Sage for getting assets from manifest.json
 *
 */
class JsonManifest
{
    private $manifest;

    public function __construct($manifest_path)
    {
        if (file_exists($manifest_path)) {
            $this->manifest = json_decode(file_get_contents($manifest_path), true);
        } else {
            $this->manifest = [];
        }
    }

    public function get()
    {
        return $this->manifest;
    }

    public function getPath($key = '', $default = null)
    {
        $collection = $this->manifest;
        if (is_null($key)) {
            return $collection;
        }
        if (isset($collection[$key])) {
            return $collection[$key];
        }
        foreach (explode('.', $key) as $segment) {
            if (!isset($collection[$segment])) {
                return $default;
            } else {
                $collection = $collection[$segment];
            }
        }
        return $collection;
    }
}

function asset_path($filename)
{
    $dist_path = get_template_directory_uri() . '/dist/';
    $directory = dirname($filename) . '/';
    $file = basename($filename);
    static $manifest;

    if (empty($manifest)) {
        $manifest_path = get_template_directory() . '/manifest.json';
        $manifest = new JsonManifest($manifest_path);
    }

    if ($manifest->get() != null) {
        if (array_key_exists($file, $manifest->get())) {
            return $dist_path . $directory . $manifest->get()[$file];
        } else {
            return $dist_path . $directory . $file;
        }
    } else {
        return $dist_path . $directory . $file;
    }
}

// Load Sperling styles
function sperling_styles()
{
    /*
      INCLUDES MINIFIED AND CONCATINATED COPIES OF:
        Normalize: https://necolas.github.io/normalize.css/
        Slick slider's base CSS and theme: http://kenwheeler.github.io/slick/
        Bulma's grid (and nothing else): https://bulma.io/documentation/columns/basics/
        Font Awesome: http://fontawesome.io/
    */
    wp_enqueue_style('sperling/css', asset_path('css/app.min.css'), false, null);
}

// Load Google Fonts from CDN. 
function sperling_add_google_fonts()
{
    // Enter the URL of your Google Fonts generated from https://fonts.google.com/ here.
    $google_fonts_url = 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Jost:wght@300;400;500;600&display=swap';
?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="<?php echo $google_fonts_url; ?>" rel="stylesheet">
<?php }


// Register Sperling Navigation
function register_menus()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'sperling'), // Main Navigation
        // duplicate if needed
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_ellipses()
{
    return '...';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

function custom_login_css_file()
{
    wp_enqueue_style('login-styles', get_template_directory_uri() . '/css/login.css');
}

// Removes comments from admin menu
function my_remove_admin_menus()
{
    remove_menu_page('edit-comments.php');
}

// Removes comments from post and pages
function remove_comment_support()
{
    remove_post_type_support('post', 'comments');
    remove_post_type_support('page', 'comments');
}
// Removes comments from admin bar
function mytheme_admin_bar_render()
{
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function sperlingcomments($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
        <?php if ('div' != $args['style']) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <?php endif; ?>
            <div class="comment-author vcard">
                <?php if ($args['avatar_size'] != 0) echo get_avatar($comment, $args['180']); ?>
                <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
            </div>
            <?php if ($comment->comment_approved == '0') : ?>
                <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
                <br />
            <?php endif; ?>

            <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
                    <?php
                    printf(__('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'), '  ', '');
                                                                                                ?>
            </div>

            <?php comment_text() ?>

            <div class="reply">
                <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>
            <?php if ('div' != $args['style']) : ?>
            </div>
        <?php endif; ?>
    <?php }

// Change wp-login logo to site url
function custom_loginlogo_url($url)
{
    return get_bloginfo('url');
}

// Remove old events from sitemap as well as noindex old events
/* add nofollow metatag to header of past events */
add_filter("wpseo_robots", function ($robots) {
    if (is_singular('tribe_events') && tribe_is_past_event() == true) {
        return "noindex,follow";
    }
    return $robots;
});

/* exclude past events from the sitemap */
function my_find_expired_events($ids)
{
    $args = array(
        'post_type'     => 'tribe_events',
        'nopaging'      => true,
        'fields'        => 'ids',
        'meta_query'    => array(
            array(
                'key'       => '_EventEndDate',
                'value'     => date('Y-m-d H:i:s'),
                'compare'   => '<',
                'type'      => 'DATETIME',
            ),
        ),
    );
    $expired_events = get_posts($args);
    $ids = array_merge($ids, $expired_events);
    $ids = array_map('absint', $ids);
    $ids = array_unique($ids);
    return $ids;
}

// Disable Author Pages (Archives)
function disable_author_archives()
{
    if (is_author()) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
    } else {
        redirect_canonical();
    }
}

// Small Function to retrieve the Alt of any Post Thumbnail 
// Just pass the ID of the post for the post thumbnail alt that you need
function get_the_post_thumbnail_alt($ids)
{
    $thumbnail_id = get_post_thumbnail_id($ids);
    $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
    return $alt;
}

/**
 * Adds a responsive embed wrapper around oEmbed content
 * @param  string $html The oEmbed markup
 * @param  string $url  The URL being embedded
 * @param  array  $attr An array of attributes
 * @return string       Updated embed markup
 */

function setup_theme()
{
    // Filters the oEmbed process to run the responsive_embed() function
    add_filter('embed_oembed_html', 'responsive_embed', 10, 3);
}
function responsive_embed($html, $url, $attr)
{
    return $html !== '' ? '<div class="embed-container">' . $html . '</div>' : '';
}

//// Strip author oEmbed data (primarily used when sharing on Discord)
function disable_embeds_filter_oembed_response_data_($data)
{
    unset($data['author_url']);
    unset($data['author_name']);
    return $data;
}

//// Disable confirm weak password
function disable_confirm_weak_passwords()
{
    echo "<style>.pw-weak{display:none!important}</style>";
    echo '<script>document.getElementById("pw-checkbox").disabled = true;</script>';
}

// Disable specific REST API endpoints
function disable_rest_endpoints($endpoints)
{
    // Remove access to the users endpoint
    if (isset($endpoints['/wp/v2/users'])) {
        unset($endpoints['/wp/v2/users']);
    }
    return $endpoints;
}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'sperling_header_scripts'); // Add Custom Scripts to wp_head
//add_action('wp_print_scripts', 'sperling_conditional_scripts'); // Add Conditional Page Scripts
add_action('wp_enqueue_scripts', 'sperling_styles'); // Add Theme Stylesheet
add_action('init', 'register_menus'); // Add Sperling Menu
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination
add_action('template_redirect', 'disable_author_archives'); // Disable Author Pages (Archives)
add_action('login_enqueue_scripts', 'custom_login_css_file');
add_action('init', 'remove_comment_support', 100); // Removes comments from post and pages
add_action('admin_menu', 'my_remove_admin_menus'); // Removes comments from admin menu
add_action('wp_before_admin_bar_render', 'mytheme_admin_bar_render'); // Removes comments from admin bar
add_action('after_setup_theme', 'setup_theme'); // Adds responsive wrapper to oEmbeds
add_action('admin_head', 'disable_confirm_weak_passwords'); //// Disable confirm weak password
add_action('login_head', 'disable_confirm_weak_passwords'); //// Disable confirm weak password

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
remove_action('wp_head', 'rsd_link'); // Remove the XML-RPC header

// Add Filters
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
// add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('excerpt_more', 'html5_blank_view_ellipses'); // Add ... instead of [...] for Excerpts
add_filter('login_headerurl', 'custom_loginlogo_url'); // Change wp-login logo to site url
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

/** Local ACF JSON (sync field groups under theme/acf-json). */
function md_theme_acf_json_folder(): string
{
    return get_stylesheet_directory() . '/acf-json';
}

add_filter('acf/settings/save_json', function () {
    return md_theme_acf_json_folder();
});
add_filter('acf/settings/load_json', function ($paths) {
    if (! is_array($paths)) {
        $paths = [];
    }
    $dir = md_theme_acf_json_folder();
    if (! in_array($dir, $paths, true)) {
        $paths[] = $dir;
    }

    return $paths;
});
add_filter('wpseo_exclude_from_sitemap_by_post_ids', 'my_find_expired_events');
add_filter('oembed_response_data', 'disable_embeds_filter_oembed_response_data_'); //// Strip author oEmbed data (primarily used when sharing on Discord)
add_filter('xmlrpc_enabled', '__return_false'); // Disable XML-RPC in WordPress
add_filter('rest_endpoints', 'disable_rest_endpoints'); // Disable specific REST API endpoints

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether
remove_filter('template_redirect', 'redirect_canonical'); // Disable Author Pages (Archives)

// Notification Banner - Register ACF options at init to avoid translation errors
add_action('init', function() {
    if (function_exists('acf_add_options_page')) {
        acf_add_options_page(array(
            'page_title'    => 'Theme Settings',
            'menu_title'    => 'Theme Settings',
            'menu_slug'     => 'theme-settings',
            'capability'    => 'edit_posts',
            'position'      => 30,
            'icon_url'      => 'dashicons-admin-generic',
            'redirect'      => false
        ));

        acf_add_local_field_group(array(
            'key' => 'notification_banner',
            'title' => 'Notification Banner',
            'fields' => array(
                array(
                    'key' => 'field_display_banner',
                    'label' => 'Display Banner',
                    'name' => 'display_banner',
                    'type' => 'true_false',
                    'default_value' => 0,
                ),
                array(
                    'key' => 'field_banner_background_color',
                    'label' => 'Background Color',
                    'name' => 'banner_background_color',
                    'type' => 'color_picker',
                ),
                array(
                    'key' => 'field_font_color',
                    'label' => 'Font Color',
                    'name' => 'font_color',
                    'type' => 'color_picker',
                ),
                array(
                    'key' => 'field_banner_content',
                    'label' => 'Banner Content',
                    'name' => 'banner_content',
                    'type' => 'wysiwyg',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'options_page',
                        'operator' => '==',
                        'value' => 'theme-settings',
                    ),
                ),
            ),
        ));
    }
});
