<?php
/**
 * Front Page – MapWise (old-style blocks + latest 3 posts)
 */

get_header();

/**
 * Pull images from Media Library by filename (same pattern as header.php)
 * Make sure these filenames exist in Media:
 * - banner.png
 * - redandblue.png
 * - computer.jpeg
 */
$banner_img   = mapwise_media_url_by_filename('banner.png');
$forecast_img = mapwise_media_url_by_filename('redandblue.png');
$projects_img = mapwise_media_url_by_filename('computer-scaled.jpeg');
?>

<main class="site-main">

  <!-- ======================
       Banner (old homepage vibe)
  ====================== -->
  <?php if (!empty($banner_img)) : ?>
    <img
      src="<?php echo esc_url($banner_img); ?>"
      alt="MapWise banner"
      style="width: 100%; height: auto; display: block; border-radius: var(--radius-lg);"
      loading="lazy"
    >
  <?php endif; ?>

  <!-- ======================
       Old homepage blocks (Forecasts + Projects)
       Uses your existing MW card system:
       .mw-section / .mw-card-grid / .mw-card / .mw-card-thumb / .mw-card-body ...
  ====================== -->
  <section class="mw-section">

    <div class="mw-card-grid">

      <article class="mw-card">
        <a class="mw-card-link" href="<?php echo esc_url(home_url('/forecasts')); ?>">
          <?php if (!empty($forecast_img)) : ?>
            <div class="mw-card-thumb">
              <img src="<?php echo esc_url($forecast_img); ?>" alt="Forecasts" loading="lazy">
            </div>
          <?php endif; ?>
          <div class="mw-card-body">
            <h3 class="mw-card-title">Forecasts</h3>
            <p class="mw-card-excerpt">View our latest political forecasts and election models.</p>
          </div>
        </a>
      </article>

      <article class="mw-card">
        <a class="mw-card-link" href="<?php echo esc_url(home_url('/projects')); ?>">
          <?php if (!empty($projects_img)) : ?>
            <div class="mw-card-thumb">
              <img src="<?php echo esc_url($projects_img); ?>" alt="Projects" loading="lazy">
            </div>
          <?php endif; ?>
          <div class="mw-card-body">
            <h3 class="mw-card-title">Projects</h3>
            <p class="mw-card-excerpt">Explore our latest data and visualization projects.</p>
          </div>
        </a>
      </article>

    </div>
  </section>

  <!-- ======================
       Latest Articles (9 most recent)
       Uses your existing .frontpage-* styles
  ====================== -->
  <section class="mw-section">
    <section class="frontpage-grid">
      <?php
      $latest = new WP_Query([
        'post_type'           => 'post',
        'posts_per_page'      => 9,
        'ignore_sticky_posts' => true,
      ]);

      if ($latest->have_posts()) :
        while ($latest->have_posts()) : $latest->the_post();

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
                  <div class="tax-row">
                    <span class="tax-label">Categories:</span>
                    <span class="tax-values"><?php echo $cats_list; ?></span>
                  </div>
                <?php endif; ?>

                <?php if ($tags_list): ?>
                  <div class="tax-row">
                    <span class="tax-label">Tags:</span>
                    <span class="tax-values"><?php echo $tags_list; ?></span>
                  </div>
                <?php endif; ?>
              </div>

              <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 26)); ?></p>
            </div>

          </a>
        </article>

      <?php
        endwhile;
        wp_reset_postdata();
      else:
      ?>
        <p class="search-empty">No posts found.</p>
      <?php endif; ?>
    </section>

  </section>

</main>

<?php get_footer(); ?>
