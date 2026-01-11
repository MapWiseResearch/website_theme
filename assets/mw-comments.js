(function ($) {
  function cancelReplyIfStuck() {
    // If WP reply JS is loaded, cancel reply state on page load
    if (window.addComment && typeof window.addComment.cancelReply === "function") {
      window.addComment.cancelReply();
    }
    // Remove #respond so refresh doesn't jump you back into reply area
    if (location.hash === "#respond") {
      history.replaceState(null, document.title, location.pathname + location.search);
    }
  }

  function ensureCommentsList() {
    // WP usually uses .comment-list (ol). Ensure it exists.
    let $list = $('.comment-list');
    if ($list.length) return $list;

    // If there are no comments yet, WP might not output a list.
    // Create one inside #comments if present, else inside .comments-panel.
    let $container = $('#comments');
    if (!$container.length) $container = $('.comments-panel');
    if (!$container.length) return $();

    $container.append('<ol class="comment-list"></ol>');
    return $('.comment-list');
  }

  function insertNewComment(html, parentId) {
    const $list = ensureCommentsList();
    if (!$list.length) return;

    const $new = $(html);

    if (parentId && parentId > 0) {
      // Insert as a child under the parent comment
      const $parent = $('#comment-' + parentId);
      if ($parent.length) {
        let $children = $parent.children('.children');
        if (!$children.length) {
          $parent.append('<ol class="children"></ol>');
          $children = $parent.children('.children');
        }
        $children.append($new);
        return;
      }
    }

    // Otherwise append to top level
    $list.append($new);
  }

  $(document).ready(function () {
    cancelReplyIfStuck();

    // Smooth scroll when clicking "Reply"
    $(document).on('click', '.comment-reply-link', function () {
      // allow WP moveForm to run first
      setTimeout(() => {
        const el = document.getElementById('respond');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 50);
    });

    // AJAX submit
    $(document).on('submit', '#commentform', function (e) {
      e.preventDefault();

      const $form = $(this);
      const data = $form.serializeArray();

      // Add our AJAX action + nonce
      data.push({ name: 'action', value: 'mw_submit_comment' });
      data.push({ name: 'nonce', value: MWComments.nonce });

      const $submit = $form.find('[type="submit"]');
      const oldText = $submit.val();
      $submit.prop('disabled', true).val('Posting…');

      $.ajax({
        url: MWComments.ajaxUrl,
        method: 'POST',
        data: $.param(data),
        success: function (res) {
          if (!res || !res.success) {
            alert((res && res.data && res.data.message) ? res.data.message : 'Failed to post comment.');
            return;
          }

          const { html, parentId } = res.data;

          // inside the existing reply click handler (before scrollIntoView)
          const panel = document.getElementById('comments-panel');
          const btn = document.querySelector('.comments-toggle');
          if (panel && btn) {
            panel.hidden = false;
            btn.setAttribute('aria-expanded', 'true');
            const icon = btn.querySelector('.comments-toggle-icon');
            if (icon) icon.textContent = '–';
          }
          insertNewComment(html, parentId);

          // Clear textarea + cancel reply so you’re not stuck replying
          $form.find('textarea[name="comment"]').val('');
          $form.find('input[name="comment_parent"]').val('0');

          if (window.addComment && typeof window.addComment.cancelReply === "function") {
            window.addComment.cancelReply();
          }

          // Remove #respond (prevents refresh from “still replying”)
          history.replaceState(null, document.title, location.pathname + location.search);
        },
        error: function (xhr) {
          const msg = (xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message)
            ? xhr.responseJSON.data.message
            : 'Failed to post comment.';
          alert(msg);
        },
        complete: function () {
          $submit.prop('disabled', false).val(oldText);
        }
      });
    });

    // Also cancel if user hits Esc while replying
    $(document).on('keydown', function (e) {
      if (e.key === 'Escape' && window.addComment && typeof window.addComment.cancelReply === "function") {
        window.addComment.cancelReply();
        history.replaceState(null, document.title, location.pathname + location.search);
      }
    });
  });
})(jQuery);
