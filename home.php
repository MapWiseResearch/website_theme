<?php
/**
 * Home Page – MapWise Front Page (filters + author + sort)
 */

get_header();

/**
 * Read filter inputs
 */
$paged     = max(1, (int) get_query_var('paged'));
$cat_id    = isset($_GET['cat']) ? (int) $_GET['cat'] : 0;
$tag_slug  = isset($_GET['tag']) ? sanitize_title((string) $_GET['tag']) : '';
$author_id = isset($_GET['author']) ? (int) $_GET['author'] : 0;
$sort      = isset($_GET['sort']) ? sanitize_key((string) $_GET['sort']) : 'new';

/**
 * Sort mapping
 */
$order   = 'DESC';
$orderby = 'date';

switch ($sort) {
  case 'old':
    $order = 'ASC';
    $orderby = 'date';
    break;
  case 'title_az':
    $order = 'ASC';
    $orderby = 'title';
    break;
  case 'title_za':
    $order = 'DESC';
    $orderby = 'title';
    break;
  case 'new':
  default:
    $order = 'DESC';
    $orderby = 'date';
    break;
}

/**
 * Query args
 */
$args = [
  'post_type'           => 'post',
  'posts_per_page'      => 12,
  'paged'               => $paged,
  'ignore_sticky_posts' => true,
  'orderby'             => $orderby,
  'order'               => $order,
];

if ($cat_id > 0) $args['cat'] = $cat_id;
if (!empty($tag_slug)) $args['tag'] = $tag_slug;
if ($author_id > 0) $args['author'] = $author_id;

$query = new WP_Query($args);

/**
 * Dropdown data
 */
$categories = get_categories(['hide_empty' => false]);
$tags       = get_tags(['hide_empty' => false]);
$authors    = get_users(['who' => 'authors']);
?>

<main class="site-main">

  <!-- ======================
       Hero
  ====================== -->
  <section class="home-hero">
    <h1>MapWisePolitics</h1>
    <p>Your one-stop shop for everything elections.</p>
  </section>

  <!-- ======================
       Filters
  ====================== -->
  <section class="search-filters-wrap">
    <form class="search-filters" method="get" action="<?php echo esc_url(home_url('/')); ?>">

      <label class="filter-field">
        <span>Category</span>
        <select name="cat" onchange="this.form.submit()">
          <option value="0">All categories</option>
          <?php foreach ($categories as $c): ?>
            <option value="<?php echo (int) $c->term_id; ?>" <?php selected($cat_id, $c->term_id); ?>>
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

      <label class="filter-field">
        <span>Author</span>
        <select name="author" onchange="this.form.submit()">
          <option value="0">All authors</option>
          <?php foreach ($authors as $a): ?>
            <option value="<?php echo (int) $a->ID; ?>" <?php selected($author_id, $a->ID); ?>>
              <?php echo esc_html($a->display_name); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

      <label class="filter-field">
        <span>Sort</span>
        <select name="sort" onchange="this.form.submit()">
          <option value="new" <?php selected($sort, 'new'); ?>>Newest</option>
          <option value="old" <?php selected($sort, 'old'); ?>>Oldest</option>
          <option value="title_az" <?php selected($sort, 'title_az'); ?>>Title A–Z</option>
          <option value="title_za" <?php selected($sort, 'title_za'); ?>>Title Z–A</option>
        </select>
      </label>

      <button class="filter-button" type="submit">Apply</button>

      <?php if ($cat_id || $tag_slug || $author_id || ($sort && $sort !== 'new')): ?>
        <a class="filter-reset" href="<?php echo esc_url(home_url('/')); ?>">Reset</a>
      <?php endif; ?>

    </form>
  </section>

  <!-- ======================
       Articles Grid
  ====================== -->
  <section class="frontpage-grid">

    <?php if ($query->have_posts()) : while ($query->have_posts()) : $query->the_post();

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
            <h2><?php echo esc_html(get_the_title()); ?></h2>

            <div class="card-meta">
              <span><?php echo esc_html(get_the_date()); ?></span>
              <span class="meta-dot">•</span>
              <span>By <?php echo esc_html($author_name); ?></span>
            </div>

            <div class="card-tax">
              <?php if ($cats_list): ?>
                <div class="tax-row"><span class="tax-label">Categories:</span> <span class="tax-values"><?php echo $cats_list; ?></span></div>
              <?php endif; ?>
              <?php if ($tags_list): ?>
                <div class="tax-row"><span class="tax-label">Tags:</span> <span class="tax-values"><?php echo $tags_list; ?></span></div>
              <?php endif; ?>
            </div>

            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 26)); ?></p>
          </div>

        </a>
      </article>

    <?php endwhile; wp_reset_postdata(); else: ?>

      <p class="search-empty">No posts found.</p>

    <?php endif; ?>

  </section>

  <!-- ======================
       Pagination (preserve filters)
  ====================== -->
  <div class="frontpage-pagination">
    <?php
      echo paginate_links([
        'total'   => $query->max_num_pages,
        'current' => $paged,
        'add_args' => [
          'cat'    => $cat_id,
          'tag'    => $tag_slug,
          'author' => $author_id,
          'sort'   => $sort,
        ],
      ]);
    ?>
  </div>

</main>

<?php get_footer(); ?>
