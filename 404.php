<?php
/**
 * 404 Template – MapWise Theme
 */

get_header();
?>

<main class="site-main">

  <section class="archive-head">
    <h1>Page not found</h1>
    <p>
      That URL doesn’t exist (or it moved). Try searching, or check the latest articles below.
    </p>
  </section>

    <section class="mw-404-search" aria-label="Search">
      <form class="mw-search-form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <input
          class="mw-search-input"
          type="search"
          name="s"
          placeholder="Search…"
          value="<?php echo esc_attr(get_search_query()); ?>"
          aria-label="Search"
        >
      </form>
    </section>

  <?php
    $recent = new WP_Query([
      'post_type'      => 'post',
      'posts_per_page' => 6,
      'no_found_rows'  => true,
    ]);
  ?>

  <?php if ($recent->have_posts()) : ?>
    <section class="frontpage-grid">
      <?php while ($recent->have_posts()) : $recent->the_post(); ?>
        <article class="frontpage-card">
          <a href="<?php the_permalink(); ?>" aria-label="<?php echo esc_attr(get_the_title()); ?>">

            <div class="card-image">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large'); ?>
              <?php else : ?>
                <img
                  src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 9'%3E%3Crect width='16' height='9' fill='%23eee'/%3E%3C/svg%3E"
                  alt=""
                  loading="lazy"
                />
              <?php endif; ?>
            </div>

            <div class="card-content">
              <h2><?php the_title(); ?></h2>

              <div class="card-meta">
                <span><?php echo esc_html(get_the_date()); ?></span>
                <span class="meta-dot">•</span>
                <span><?php echo esc_html(get_the_author()); ?></span>
              </div>

              <?php if (has_excerpt()) : ?>
                <p><?php echo esc_html(get_the_excerpt()); ?></p>
              <?php else : ?>
                <p><?php echo esc_html(wp_trim_words(get_the_content(null, false), 18)); ?></p>
              <?php endif; ?>
            </div>

          </a>
        </article>
      <?php endwhile; ?>
    </section>

    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
