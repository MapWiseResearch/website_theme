<?php
/**
 * MapWise Theme Footer
 */
?>

<footer class="site-footer">

  <!-- Brand column -->
  <div class="footer-column brand">
      <?php
      if (function_exists('mapwise_media_url_by_filename')) {
          $logo   = mapwise_media_url_by_filename('logo.png');
          $banner = mapwise_media_url_by_filename('banner.png');
      }
      ?>

      <?php if (!empty($logo)) : ?>
        <img src="<?php echo esc_url($logo); ?>" alt="<?php bloginfo('name'); ?>" class="footer-logo">
      <?php endif; ?>

      <p>Your one-stop shop<br>for everything elections.</p>
    </div>


  <!-- Navigation -->
  <div class="footer-column">
    <h3>Explore</h3>
    <ul>
      <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
      <li><a href="<?php echo esc_url(home_url('/articles')); ?>">Articles</a></li>
      <li><a href="<?php echo esc_url(home_url('/about')); ?>">About</a></li>
    </ul>
  </div>

  <!-- Legal -->
  <div class="footer-column">
    <h3>Site</h3>
    <ul>
      <li><a href="<?php echo esc_url(home_url('/privacy')); ?>">Privacy Policy</a></li>
      <li><a href="<?php echo esc_url(home_url('/terms')); ?>">Terms of Service</a></li>
    </ul>
  </div>

  <!-- Social -->
  <div class="footer-column">
    <h3>Contact</h3>
  <ul>
    <li><a href="https://x.com/MapWisePolitics" target="_blank" rel="noopener noreferrer">X (Twitter)</a></li>
    <li><a href="https://www.youtube.com/@MapWisePolitics" target="_blank" rel="noopener noreferrer">YouTube</a></li>
    <li><a href="mailto:mapwiseresearch@gmail.com">Email</a></li>
  </ul>
  </div>

  <!-- Bottom row -->
  <div class="footer-bottom">
    © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
  </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
