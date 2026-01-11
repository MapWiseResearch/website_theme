<?php
/**
 * Page Template – MapWise Theme
 */

get_header();
?>

<main class="site-main">
  <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <article class="post-single page-single" id="page-<?php the_ID(); ?>">

      <header class="post-header page-header">
        <h1 class="post-title page-title"><?php the_title(); ?></h1>

        <?php
          $subtitle = get_post_meta(get_the_ID(), 'subtitle', true);
          if (!empty($subtitle)) :
        ?>
          <p class="page-subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>

      </header>

      <?php if (has_post_thumbnail()) : ?>
        <figure class="post-featured page-featured">
          <?php the_post_thumbnail('large'); ?>
        </figure>
      <?php endif; ?>

      <div class="post-content page-content">
        <?php the_content(); ?>
      </div>

      <?php
        wp_link_pages([
          'before' => '<nav class="page-links">',
          'after'  => '</nav>',
        ]);
      ?>

      <?php
        if (comments_open() || get_comments_number()) {
          comments_template();
        }
      ?>

    </article>

  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
