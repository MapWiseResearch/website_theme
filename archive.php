<?php
/**
 * Archive Template – MapWise Theme
 * Covers: category, tag, author, date archives (fallback)
 */

get_header();
?>

<main class="site-main">

  <section class="archive-head">
    <h1 class="archive-title">
      <?php the_archive_title(); ?>
    </h1>

    <?php
      $desc = get_the_archive_description();
      if (!empty($desc)) :
    ?>
      <div class="archive-description" style="opacity: .75;">
        <?php echo wp_kses_post($desc); ?>
      </div>
    <?php endif; ?>
  </section>

  <?php if (have_posts()) : ?>

    <section class="frontpage-grid">
      <?php while (have_posts()) : the_post(); ?>
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

              <div class="card-tax">
                <?php
                  $cats = get_the_category();
                  if (!empty($cats)) :
                ?>
                  <div class="tax-row">
                    <span class="tax-label">Category:</span>
                    <span class="tax-values">
                      <?php
                        $cat_links = [];
                        foreach ($cats as $c) {
                          $cat_links[] = '<a href="' . esc_url(get_category_link($c->term_id)) . '">' . esc_html($c->name) . '</a>';
                        }
                        echo wp_kses_post(implode(', ', $cat_links));
                      ?>
                    </span>
                  </div>
                <?php endif; ?>

                <?php
                  $tags = get_the_tags();
                  if (!empty($tags)) :
                ?>
                  <div class="tax-row">
                    <span class="tax-label">Tags:</span>
                    <span class="tax-values">
                      <?php
                        $tag_links = [];
                        foreach ($tags as $t) {
                          $tag_links[] = '<a href="' . esc_url(get_tag_link($t->term_id)) . '">' . esc_html($t->name) . '</a>';
                        }
                        echo wp_kses_post(implode(', ', $tag_links));
                      ?>
                    </span>
                  </div>
                <?php endif; ?>
              </div>

              <?php if (has_excerpt()) : ?>
                <p><?php echo esc_html(get_the_excerpt()); ?></p>
              <?php else : ?>
                <p><?php echo esc_html(wp_trim_words(get_the_content(null, false), 22)); ?></p>
              <?php endif; ?>
            </div>

          </a>
        </article>
      <?php endwhile; ?>
    </section>

    <nav class="frontpage-pagination" aria-label="Pagination">
      <?php
        echo paginate_links([
          'prev_text' => '← Prev',
          'next_text' => 'Next →',
        ]);
      ?>
    </nav>

  <?php else : ?>

    <p class="search-empty" style="max-width: var(--container); margin: 2rem auto; padding: 0 1rem;">
      No posts found.
    </p>

  <?php endif; ?>

</main>

<?php get_footer(); ?>
