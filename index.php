<?php get_header(); ?>

<main class="site-main">
<?php
if (have_posts()) :
  while (have_posts()) : the_post();
?>
    <article <?php post_class('post'); ?>>
      <?php if (is_singular()) : ?>
        <h1 class="post-title"><?php the_title(); ?></h1>
        <div class="post-content"><?php the_content(); ?></div>
      <?php else : ?>
        <h2 class="post-title">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h2>
        <div class="post-excerpt"><?php the_excerpt(); ?></div>
      <?php endif; ?>
    </article>
<?php
  endwhile;

  the_posts_navigation();

else :
  echo '<p>No content found.</p>';
endif;
?>
</main>

<?php get_footer(); ?>
