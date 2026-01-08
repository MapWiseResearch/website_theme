<?php
/**
 * MapWise Theme Functions
 */

/**
 * Enable tags for Pages
 */
function mapwise_enable_tags_for_pages() {
    register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'mapwise_enable_tags_for_pages');

function mapwise_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_theme_support('custom-logo', [
        'height' => 80,
        'width'  => 240,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => 'Primary Navigation',
    ]);
}
add_action('after_setup_theme', 'mapwise_theme_setup');

function mapwise_enqueue_styles() {
    wp_enqueue_style(
        'mapwise-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'mapwise_enqueue_styles');

function mapwise_widgets_init() {
    register_sidebar([
        'name' => 'Footer',
        'id' => 'footer-1',
        'before_widget' => '<div class="footer-widget">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ]);
}
add_action('widgets_init', 'mapwise_widgets_init');


/**
 * Shortcode:
 * [mw_cards posts="9" title="Featured"]
 *
 * Shows ONLY Pages/Posts tagged with: front-page
 * Uses featured image as a "cover" card image.
 */
function mw_cards_shortcode($atts) {
    $atts = shortcode_atts([
        'posts' => 9,
        'title' => '',
        'tag'   => 'front-page',
    ], $atts, 'mw_cards');

    $posts_per_page = max(1, min(30, intval($atts['posts'])));
    $title = sanitize_text_field($atts['title']);
    $tag = sanitize_title($atts['tag']);

    $q = new WP_Query([
        'post_type' => ['page', 'post'],
        'posts_per_page' => $posts_per_page,
        'tax_query' => [
            [
                'taxonomy' => 'post_tag',
                'field'    => 'slug',
                'terms'    => $tag,
            ],
        ],
        'ignore_sticky_posts' => true,
    ]);

    if (!$q->have_posts()) return '';

    ob_start();

    echo '<section class="mw-section">';
    if ($title !== '') {
        echo '<h2 class="mw-section-title">' . esc_html($title) . '</h2>';
    }
    echo '<div class="mw-card-grid">';

    while ($q->have_posts()) {
        $q->the_post();

        $thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
        $style = $thumb_url ? ' style="background-image:url(' . esc_url($thumb_url) . ')"' : '';

        echo '<article class="mw-card">';
        echo '<a class="mw-card-link" href="' . esc_url(get_permalink()) . '">';

        // Cover image area (like old cards)
        echo '<div class="mw-card-cover' . ($thumb_url ? '' : ' is-empty') . '"' . $style . '></div>';

        echo '<div class="mw-card-body">';
        echo '<h3 class="mw-card-title">' . esc_html(get_the_title()) . '</h3>';

        $excerpt = get_the_excerpt();
        if ($excerpt) {
            echo '<p class="mw-card-excerpt">' . esc_html(wp_trim_words($excerpt, 22)) . '</p>';
        }

        echo '</div></a></article>';
    }

    echo '</div></section>';

    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('mw_cards', 'mw_cards_shortcode');



/**
 * Shortcode: [mw_tagbar tags="forecasts,explainers,updates"]
 * Renders a pill tag bar linking to tag archives.
 */
function mw_tagbar_shortcode($atts) {
    $atts = shortcode_atts([
        'tags' => '',
    ], $atts, 'mw_tagbar');

    $raw = (string) $atts['tags'];
    $slugs = array_filter(array_map('trim', explode(',', $raw)));

    $safe = [];
    foreach ($slugs as $s) {
        $s = sanitize_title($s);
        if ($s) $safe[] = $s;
    }

    if (empty($safe)) return '';

    ob_start();
    echo '<div class="mw-tagbar"><div class="mw-tagbar-inner">';

    foreach ($safe as $slug) {
        $tag = get_term_by('slug', $slug, 'post_tag');
        if (!$tag || is_wp_error($tag)) continue;

        echo '<a class="mw-tag-pill" href="' . esc_url(get_tag_link($tag->term_id)) . '">';
        echo '#' . esc_html($tag->name);
        echo '</a>';
    }

    echo '</div></div>';
    return ob_get_clean();
}
add_shortcode('mw_tagbar', 'mw_tagbar_shortcode');
