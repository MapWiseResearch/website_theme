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

/**
 * Theme setup
 */
function mapwise_theme_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');

  add_theme_support('custom-logo', [
    'height'      => 80,
    'width'       => 240,
    'flex-height' => true,
    'flex-width'  => true,
  ]);

  register_nav_menus([
    'primary' => 'Primary Navigation',
  ]);
}
add_action('after_setup_theme', 'mapwise_theme_setup');

/**
 * Widgets
 */
function mapwise_widgets_init() {
  register_sidebar([
    'name'          => 'Footer',
    'id'            => 'footer-1',
    'before_widget' => '<div class="footer-widget">',
    'after_widget'  => '</div>',
    'before_title'  => '<h3>',
    'after_title'   => '</h3>',
  ]);
}
add_action('widgets_init', 'mapwise_widgets_init');

/**
 * [page slug="about"]Link[/page]
 */
function page_link_shortcode($atts, $content = null) {
  $atts = shortcode_atts([
    'slug'  => '',
    'class' => ''
  ], $atts);

  $page = get_page_by_path($atts['slug']);
  if (!$page) return '';

  return '<a href="' . get_permalink($page->ID) . '" class="' . esc_attr($atts['class']) . '">' . do_shortcode($content) . '</a>';
}
add_shortcode('page', 'page_link_shortcode');

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
  $tag   = sanitize_title($atts['tag']);

  $q = new WP_Query([
    'post_type'           => ['page', 'post'],
    'posts_per_page'      => $posts_per_page,
    'tax_query'           => [[
      'taxonomy' => 'post_tag',
      'field'    => 'slug',
      'terms'    => $tag,
    ]],
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

  $raw   = (string) $atts['tags'];
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

/**
 * Find a Media Library attachment URL by its filename (e.g. "banner.png").
 * Returns "" if not found.
 */
function mapwise_media_url_by_filename(string $filename): string {
  $filename = trim($filename);
  if ($filename === '') return '';

  $q = new WP_Query([
    'post_type'      => 'attachment',
    'post_status'    => 'inherit',
    'posts_per_page' => 1,
    'fields'         => 'ids',
    'meta_query'     => [[
      'key'     => '_wp_attached_file',
      'value'   => '/' . preg_quote($filename, '/') . '$',
      'compare' => 'REGEXP',
    ]],
  ]);

  if (empty($q->posts)) return '';
  $id = (int) $q->posts[0];

  $url = wp_get_attachment_url($id);
  return $url ? $url : '';
}

/**
 * Enqueue styles/scripts
 */
function mapwise_enqueue_styles() {
  wp_enqueue_style(
    'mapwise-style',
    get_stylesheet_uri(),
    [],
    wp_get_theme()->get('Version')
  );

  wp_enqueue_script(
    'mapwise-nav',
    get_template_directory_uri() . '/js/nav.js',
    [],
    wp_get_theme()->get('Version'),
    true
  );
}
add_action('wp_enqueue_scripts', 'mapwise_enqueue_styles');


/* =========================================================
   Comments: user bar + notes + title
========================================================= */
add_filter('comment_form_defaults', function ($defaults) {

  if (is_user_logged_in()) {
    $user = wp_get_current_user();

    $profile_url = get_edit_profile_url($user->ID);
    $logout_url  = wp_logout_url(get_permalink());

    $avatar = get_avatar($user->ID, 32);

    $defaults['logged_in_as'] = sprintf(
      '<div class="mw-userbar">
         <div class="mw-userbar-main">
           <div class="mw-user-left">
             %s
             <span class="mw-userchip">%s</span>
           </div>
           <div class="mw-userbar-actions">
             <a class="mw-btn" href="%s">Edit profile</a>
             <a class="mw-link" href="%s">Log out</a>
           </div>
         </div>
       </div>',
      $avatar,
      esc_html($user->user_login),
      esc_url($profile_url),
      esc_url($logout_url)
    );
  }

  $defaults['comment_notes_before'] = '<p class="mw-note">Required fields are marked <span class="required">*</span></p>';
  $defaults['title_reply'] = 'Join the discussion';

  return $defaults;
});


/* =========================================================
   Comments: enqueue reply JS + our AJAX JS on single posts
========================================================= */
add_action('wp_enqueue_scripts', function () {
  if (!is_singular('post')) return;

  if (get_option('thread_comments')) {
    wp_enqueue_script('comment-reply');
  }

  wp_enqueue_script(
    'mw-comments',
    get_template_directory_uri() . '/assets/mw-comments.js',
    ['jquery'],
    '1.0.0',
    true
  );

  wp_localize_script('mw-comments', 'MWComments', [
    'ajaxUrl' => admin_url('admin-ajax.php'),
    'nonce'   => wp_create_nonce('mw_comment_nonce'),
    'postId'  => get_the_ID(),
  ]);
});


/* =========================================================
   Comments: YOUR comment HTML (mw-* markup)
   - Used both on normal page load AND AJAX insert
========================================================= */

/**
 * If you already have a callback in comments.php, you can:
 *  - keep this function name, and set wp_list_comments callback to 'mw_comment_callback'
 *  - OR rename this to whatever you already use (just keep it consistent in AJAX render below)
 */
if (!function_exists('mw_comment_callback')) {
  function mw_comment_callback($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;

    // Keep the <li id="comment-123"> wrapper OUTSIDE this function when using wp_list_comments style=li.
    ?>
    <div class="mw-comment-body">
      <div class="mw-comment-avatar">
        <?php echo get_avatar($comment, 28); ?>
      </div>

      <div class="mw-comment-main">
        <div class="mw-comment-head">
          <div class="mw-comment-author">
            <?php echo get_comment_author_link($comment); ?>
          </div>

          <div class="mw-comment-meta">
            <a href="<?php echo esc_url(get_comment_link($comment)); ?>">
              <?php echo esc_html(get_comment_date('', $comment)); ?>
            </a>
            <span>·</span>
            <a href="<?php echo esc_url(get_comment_link($comment)); ?>">
              <?php echo esc_html(get_comment_time('', $comment)); ?>
            </a>

            <?php if (current_user_can('edit_comment', $comment->comment_ID)) : ?>
              <span class="mw-comment-edit"><?php edit_comment_link('(Edit)', '', '', $comment->comment_ID); ?></span>
            <?php endif; ?>
          </div>
        </div>

        <div class="mw-comment-content">
          <?php comment_text($comment); ?>
        </div>

        <div class="mw-comment-actions">
          <?php
            comment_reply_link([
              'reply_text' => 'Reply',
              'depth'      => $depth,
              'max_depth'  => $args['max_depth'],
            ], $comment->comment_ID);
          ?>
        </div>
      </div>
    </div>
    <?php
  }
}

/**
 * Render exactly ONE <li> comment using the SAME mw_comment_callback
 * so AJAX-inserted comments look identical to post-refresh.
 */
function mw_render_single_comment_li($comment_obj): string {
  $comment = get_comment(is_object($comment_obj) ? $comment_obj->comment_ID : $comment_obj);
  if (!$comment) return '';

  ob_start();

  // Make sure core functions know what "current comment" is
  $GLOBALS['comment'] = $comment;

  // IMPORTANT: output a single <li> (NOT <ol>) so your JS can append it safely
  ?>
  <li <?php comment_class('', $comment); ?> id="comment-<?php echo (int) $comment->comment_ID; ?>">
    <?php mw_comment_callback($comment, [
      'style'       => 'li',
      'short_ping'  => true,
      'avatar_size' => 28,
      'max_depth'   => (int) get_option('thread_comments_depth', 5),
    ], 1); ?>
  </li>
  <?php

  return ob_get_clean();
}


/* =========================================================
   AJAX: submit comment (logged-in + guests)
   Returns the SAME mw-* HTML as your theme, not WP default
========================================================= */
add_action('wp_ajax_mw_submit_comment', 'mw_submit_comment_ajax');
add_action('wp_ajax_nopriv_mw_submit_comment', 'mw_submit_comment_ajax');

function mw_submit_comment_ajax() {
  check_ajax_referer('mw_comment_nonce', 'nonce');

  if (empty($_POST['comment'])) {
    wp_send_json_error(['message' => 'Comment cannot be empty.'], 400);
  }

  // Let WordPress validate + insert (same rules as normal submission)
  $result = wp_handle_comment_submission(wp_unslash($_POST));

  if (is_wp_error($result)) {
    wp_send_json_error(['message' => $result->get_error_message()], 400);
  }

  // Normalize to a WP_Comment
  $comment = is_object($result) ? $result : get_comment((int) $result['comment_ID']);
  if (!$comment) {
    wp_send_json_error(['message' => 'Could not load comment after submission.'], 500);
  }

  $html = mw_render_single_comment_li($comment);

  if ($html === '') {
    wp_send_json_error(['message' => 'Failed to render comment HTML.'], 500);
  }

  wp_send_json_success([
    'commentId' => (int) $comment->comment_ID,
    'parentId'  => (int) $comment->comment_parent,
    'html'      => $html,
  ]);
}

