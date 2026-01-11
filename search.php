<?php
get_header();

/**
 * Helpers
 */
function mapwise_highlight_terms($text, $query) {
  $text = (string) $text;
  $query = trim((string) $query);
  if ($query === '' || $text === '') return $text;

  $terms = preg_split('/\s+/', $query);
  $terms = array_values(array_filter($terms, function($t) {
    $t = trim($t);
    return $t !== '' && mb_strlen($t) >= 2;
  }));

  if (empty($terms)) return $text;

  foreach ($terms as $term) {
    $pattern = '/' . preg_quote($term, '/') . '/iu';
    $text = preg_replace($pattern, '<mark class="search-hit">$0</mark>', $text);
  }

  return $text;
}

/**
 * Read filter inputs
 */
$search_q = get_search_query();
$paged    = max(1, (int) get_query_var('paged'));
$cat_id   = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;
$tag_slug = isset($_GET['tag']) ? sanitize_title((string) $_GET['tag']) : '';

/**
 * Build custom query (so filters work reliably)
 */
$args = [
  'post_type'           => 'post',
  's'                   => $search_q,
  'paged'               => $paged,
  'posts_per_page'      => 12,
  'ignore_sticky_posts' => true,
];

if ($cat_id > 0) {
  $args['cat'] = $cat_id;
}
if (!empty($tag_slug)) {
  $args['tag'] = $tag_slug;
}

$q = new WP_Query($args);

/**
 * Build dropdown options
 */
$categories = get_categories(['hide_empty' => false]);
$tags       = get_tags(['hide_empty' => false]);
?>

<main class="site-main">

  <section class="home-hero">
    <h1 class="post-title page-title">Search</h1>
    <p>
      <?php if ($search_q !== ''): ?>
        <?php echo 'Showing results for “' . esc_html($search_q) . '”'; ?>
      <?php else: ?>
        Type a keyword to search MapWisePolitics.
      <?php endif; ?>
    </p>
  </section>

  <!-- ======================
       Search (404 style)
  ====================== -->
  <section class="mw-404-search">
    <form class="mw-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input
        class="mw-search-input"
        type="search"
        name="s"
        placeholder="Search articles…"
        value="<?php echo esc_attr($search_q); ?>"
        aria-label="Search articles"
      >

      <?php if ($cat_id > 0): ?>
        <input type="hidden" name="cat" value="<?php echo (int) $cat_id; ?>">
      <?php endif; ?>

      <?php if ($tag_slug !== ''): ?>
        <input type="hidden" name="tag" value="<?php echo esc_attr($tag_slug); ?>">
      <?php endif; ?>
    </form>
  </section>

  <!-- ======================
       Filters
  ====================== -->
  <section class="search-filters-wrap">
    <form class="search-filters" method="get" action="<?php echo esc_url(home_url('/')); ?>">
      <input type="hidden" name="s" value="<?php echo esc_attr($search_q); ?>">

      <label class="filter-field">
        <span>Category</span>
        <select name="cat" onchange="this.form.submit()">
          <option value="0">All categories</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?php echo (int) $c->term_id; ?>" <?php selected($cat_id, (int) $c->term_id); ?>>
              <?php echo esc_html($c->name); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

      <label class="filter-field">
        <span>Tag</span>
        <select name="tag" onchange="this.form.submit()">
          <option value="">All tags</option>
          <?php foreach ($tags as $t): ?>
            <option value="<?php echo esc_attr($t->slug); ?>" <?php selected($tag_slug, $t->slug); ?>>
              <?php echo esc_html($t->name); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

      <button class="filter-button" type="submit">Apply</button>

      <?php if ($cat_id > 0 || $tag_slug !== ''): ?>
        <a class="filter-reset" href="<?php echo esc_url(add_query_arg(['s' => $search_q, 'cat' => 0, 'tag' => ''], home_url('/'))); ?>">
          Reset
        </a>
      <?php endif; ?>
    </form>
  </section>

  <!-- ======================
       Results
  ====================== -->
  <section class="frontpage-grid">

    <?php if ($q->have_posts()) : while ($q->have_posts()) : $q->the_post(); ?>

      <?php
        $raw_title   = get_the_title();
        $raw_excerpt = get_the_excerpt();

        $title   = mapwise_highlight_terms(esc_html($raw_title), $search_q);
        $excerpt = mapwise_highlight_terms(esc_html(wp_trim_words($raw_excerpt, 26)), $search_q);

        $author_name = get_the_author();
        $cats_list   = get_the_category_list(', ');
        $tags_list   = get_the_tag_list('', ', ');
      ?>

      <article class="frontpage-card">
        <a href="<?php the_permalink(); ?>">

          <?php if (has_post_thumbnail()) : ?>
            <div class="card-image">
              <?php the_post_thumbnail('large'); ?>
            </div>
          <?php endif; ?>

          <div class="card-content">
            <h2><?php echo $title; ?></h2>

            <div class="card-meta">
              <span class="meta-item"><?php echo esc_html(get_the_date()); ?></span>
              <span class="meta-dot">•</span>
              <span class="meta-item">By <?php echo esc_html($author_name); ?></span>
            </div>

            <div class="card-tax">
              <?php if ($cats_list): ?>
                <div class="tax-row"><span class="tax-label">Categories:</span> <span class="tax-values"><?php echo $cats_list; ?></span></div>
              <?php endif; ?>
              <?php if ($tags_list): ?>
                <div class="tax-row"><span class="tax-label">Tags:</span> <span class="tax-values"><?php echo $tags_list; ?></span></div>
              <?php endif; ?>
            </div>

            <p><?php echo $excerpt; ?></p>
          </div>

        </a>
      </article>

    <?php endwhile; wp_reset_postdata(); else: ?>

      <p class="search-empty">No results found.</p>

    <?php endif; ?>

  </section>

  <!-- ======================
       Pagination
  ====================== -->
  <div class="frontpage-pagination">
    <?php
      echo paginate_links([
        'total'   => $q->max_num_pages,
        'current' => $paged,
      ]);
    ?>
  </div>

</main>

<?php get_footer(); ?>
