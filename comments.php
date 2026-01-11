<?php
if ( post_password_required() ) return;
?>

<section id="comments" class="comments-area">
  <?php if ( have_comments() ) : ?>
    <ol class="comment-list">
      <?php
      wp_list_comments([
        'style'      => 'ol',
        'short_ping' => true,
        'avatar_size'=> 36,
        'callback'   => 'mw_comment',
      ]);
      ?>
    </ol>
  <?php endif; ?>

  <?php
  // Comment form
  comment_form([
    'class_form' => 'comment-form',
  ]);
  ?>
</section>

<?php
// Callback that renders EVERY comment the same way
function mw_comment($comment, $args, $depth) {
  $tag = 'li';
  $comment_id = 'comment-' . get_comment_ID();
  ?>
  <<?php echo $tag; ?> <?php comment_class('mw-comment'); ?> id="<?php echo esc_attr($comment_id); ?>">

    <article class="mw-comment-body">
      <div class="mw-comment-avatar">
        <?php echo get_avatar($comment, 36); ?>
      </div>

      <div class="mw-comment-main">
        <header class="mw-comment-head">
          <div class="mw-comment-author">
            <?php echo get_comment_author_link(); ?>
          </div>

          <div class="mw-comment-meta">
            <a href="<?php echo esc_url( get_comment_link($comment) ); ?>">
              <?php echo esc_html( get_comment_date() . ' at ' . get_comment_time() ); ?>
            </a>
            <?php edit_comment_link('(Edit)', ' <span class="mw-comment-edit">', '</span>'); ?>
          </div>
        </header>

        <div class="mw-comment-content">
          <?php comment_text(); ?>
        </div>

        <div class="mw-comment-actions">
          <?php
          comment_reply_link(array_merge($args, [
            'reply_text' => 'Reply',
            'depth'      => $depth,
            'max_depth'  => $args['max_depth'],
          ]));
          ?>
        </div>
      </div>
    </article>

  </<?php echo $tag; ?>>
  <?php
}
