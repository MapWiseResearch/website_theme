<?php
get_header();
?>

<main class="site-main">
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h1 class="entry-title">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h1>
        <div class="entry-content">
          <?php the_excerpt(); ?>
        </div>
      </article>
    <?php endwhile; ?>

    <?php the_posts_pagination(); ?>

  <?php else : ?>
    <p>No posts found.</p>
  <?php endif; ?>
</main>

<?php
get_footer();
