<footer class="site-footer">
  <?php if (is_active_sidebar('footer-1')) : ?>
    <?php dynamic_sidebar('footer-1'); ?>
  <?php endif; ?>

  <div class="footer-meta">
    © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
