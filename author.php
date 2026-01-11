<?php
/**
 * Author Template – MapWise Theme
 */

get_header();

$author = get_queried_object();
?>

<main class="site-main">

  <section class="archive-head" style="max-width: var(--container); margin: 2rem auto 1.25rem; padding: 0 1rem;">

    <div style="display:flex; align-items:center; gap:1rem;">
      <?php echo get_avatar($author->ID, 72, '', '', ['style' => 'border-radius:999px;']); ?>

      <div>
        <h1 style="margin:0;"><?php echo esc_html($author->display_name); ?></h1>

        <?php if (!empty($author->description)) : ?>
          <p style="margin:.25rem 0 0 0; opacity:.75; max-width:60ch;">
            <?php echo esc_html($author->description); ?>
          </p>
        <?php endif; ?>
      </div>
    </div>

  </section>

  <?php if (have_posts()) : ?>

    <section class="frontpage-grid">
      <?php while (have_posts()) : the_post(); ?>
        <article class="frontpage-card">
          <a href="<?php the_permalink(); ?>">

            <div class="card-image">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large'); ?>
              <?php endif; ?>
            </div>

            <div class="card-content">
              <h2><?php the_title(); ?></h2>

              <div class="card-meta">
                <span><?php echo esc_html(get_the_date()); ?></span>
                <span class="meta-dot">•</span>
                <span><?php echo esc_html(get_the_author()); ?></span>
              </div>

              <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
            </div>

          </a>
        </article>
      <?php endwhile; ?>
    </section>

    <nav class="frontpage-pagination">
      <?php
        echo paginate_links([
          'prev_text' => '← Prev',
          'next_text' => 'Next →',
        ]);
      ?>
    </nav>

  <?php else : ?>

    <p class="search-empty">No posts by this author yet.</p>

  <?php endif; ?>

</main>

<?php get_footer(); ?>
