<?php
/**
 * MapWise Theme Footer
 */
?>

<footer class="site-footer">
  <div class="footer-inner">

    <?php
    $logo = '';
    if (function_exists('mapwise_media_url_by_filename')) {
      $logo = mapwise_media_url_by_filename('logo.png');
    }
    ?>

    <!-- Top: Brand -->
    <div class="footer-top">
      <div class="footer-brand">
        <?php if (!empty($logo)) : ?>
          <img
            src="<?php echo esc_url($logo); ?>"
            alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
            class="footer-logo"
            loading="lazy"
            decoding="async"
          >
        <?php endif; ?>

        <div class="footer-brand-text">
          <p class="footer-tagline">Your one-stop shop<br>for everything elections.</p>
        </div>
      </div>
    </div>

    <!-- Links -->
    <div class="footer-links">

      <div class="footer-column">
        <h3>Explore</h3>
        <ul>
          <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
          <li><a href="<?php echo esc_url(home_url('/articles')); ?>">Articles</a></li>
          <li><a href="<?php echo esc_url(home_url('/projects')); ?>">Projects</a></li>
          <li><a href="<?php echo esc_url(home_url('/subscribe')); ?>">Subscribe</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h3>Site</h3>
        <ul>
           <li><a href="<?php echo esc_url(home_url('/account')); ?>">Account</a></li>
           <li><a href="<?php echo esc_url(home_url('/about')); ?>">About</a></li>
           <li><a href="<?php echo esc_url(home_url('/privacy')); ?>">Privacy Policy</a></li>
           <li><a href="<?php echo esc_url(home_url('/terms')); ?>">Terms of Service</a></li>
        </ul>
      </div>

      <div class="footer-column">
        <h3>Contact</h3>
        <ul>
          <li><a href="https://x.com/MapWisePolitics" target="_blank" rel="noopener noreferrer">X (Twitter)</a></li>
          <li><a href="https://www.youtube.com/@MapWisePolitics" target="_blank" rel="noopener noreferrer">YouTube</a></li>
          <li><a href="mailto:mapwiseresearch@gmail.com">Email</a></li>
        </ul>
      </div>

    </div>

    <!-- Bottom -->
    <div class="footer-bottom">
      © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
    </div>

  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
