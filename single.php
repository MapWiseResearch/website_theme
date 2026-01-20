<?php get_header(); ?>

<main class="site-main">
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

    <article <?php post_class('post post-single'); ?>>

      <header class="post-header">
        <h1 class="post-title"><?php the_title(); ?></h1>

        <div class="post-meta">
          <time class="post-date" datetime="<?php echo esc_attr( get_the_date('c') ); ?>">
            <?php echo esc_html( get_the_date() ); ?>
          </time>

          <span class="post-meta-sep">•</span>

          <span class="post-author">
            By
            <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta('ID') ) ); ?>">
              <?php echo esc_html( get_the_author() ); ?>
            </a>
          </span>

          <?php
            $cats = get_the_category();
            if ( ! empty( $cats ) ) :
          ?>
            <span class="post-meta-sep">•</span>
            <span class="post-categories">
              <?php the_category(', '); ?>
            </span>
          <?php endif; ?>
        </div>

        <?php
          // Optional: show tags under meta line
          $tags = get_the_tags();
          if ( $tags ) :
        ?>
          <div class="post-tags">
            <?php the_tags('<span class="tag-label">Tags:</span> ' ' ' ''); ?>
          </div>
        <?php endif; ?>
      </header>

      <?php if ( has_post_thumbnail() ) : ?>
        <figure class="post-featured">
          <?php the_post_thumbnail('large'); ?>
        </figure>
      <?php endif; ?>

      <div class="post-content entry-content">
        <?php the_content(); ?>
      </div>

      <?php
// Comments
if ( comments_open() || get_comments_number() ) :
  $comment_count = get_comments_number();
?>
  <section class="post-comments">

    <button
      class="comments-toggle"
      type="button"
      aria-expanded="false"
      aria-controls="comments-panel"
    >
      <span class="comments-toggle-title">
        Comments<?php echo $comment_count ? ' (' . intval($comment_count) . ')' : ''; ?>
      </span>
      <span class="comments-toggle-icon" aria-hidden="true">+</span>
    </button>

    <div id="comments-panel" class="comments-panel" hidden>
      <?php comments_template(); ?>
    </div>


    <script>
      (function () {
        const btn = document.querySelector('.comments-toggle');
        const panel = document.getElementById('comments-panel');
        if (!btn || !panel) return;

        btn.addEventListener('click', function () {
          const expanded = btn.getAttribute('aria-expanded') === 'true';
          btn.setAttribute('aria-expanded', String(!expanded));
          panel.hidden = expanded;

          const icon = btn.querySelector('.comments-toggle-icon');
          if (icon) icon.textContent = expanded ? '+' : '–';
        });
      })();
    </script>

  </section>
<?php endif; ?>


    </article>

  <?php endwhile; endif; ?>
</main>

<?php get_footer(); ?>
