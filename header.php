<?php
/**
 * Header template
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php
// Pull images from Media Library by filename
$banner  = mapwise_media_url_by_filename('banner.png');
$x_logo  = mapwise_media_url_by_filename('x_logo.png');
$yt_logo = mapwise_media_url_by_filename('youtube_logo.png');
$d_logo  = mapwise_media_url_by_filename('discord_logo.png');
?>

<header class="site-header">
  <div class="topbar-inner">

    <!-- Brand -->
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <?php if ($banner): ?>
        <img
          src="<?php echo esc_url($banner); ?>"
          alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
          class="brand-img"
          loading="eager"
          decoding="async"
        >
      <?php else: ?>
        <strong><?php bloginfo('name'); ?></strong>
      <?php endif; ?>
    </a>

    <!-- Navigation -->
    <nav class="site-nav" aria-label="Primary">
      <ul class="nav-list">
        <li class="nav-item">
          <a href="<?php echo esc_url(home_url('/forecasts/')); ?>">Forecasts</a>
        </li>

        <li class="nav-item">
          <a href="<?php echo esc_url(home_url('/articles/')); ?>">Articles</a>
        </li>

        <li class="nav-item has-dropdown">
          <button
            class="dropdown-toggle"
            type="button"
            aria-haspopup="true"
            aria-expanded="false"
          >
            Projects <span class="dropdown-caret" aria-hidden="true">▾</span>
          </button>

          <ul class="dropdown-menu" aria-label="Projects submenu">
            <li>
              <a href="<?php echo esc_url(home_url('/projects/')); ?>">
                All Projects
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url('/projects/mapwise-forecast/')); ?>">
                MapWise Forecast
              </a>
            </li>
            <li>
              <a href="<?php echo esc_url(home_url('/projects/data/')); ?>">
                Data
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </nav>

    <!-- Social -->
    <div class="topbar-social" aria-label="Social links">

      <a class="social-link"
         href="https://x.com/"
         target="_blank"
         rel="noopener noreferrer"
         aria-label="X">
        <?php if ($x_logo): ?>
          <img src="<?php echo esc_url($x_logo); ?>" alt="" class="social-icon">
        <?php endif; ?>
      </a>

      <a class="social-link"
         href="https://www.youtube.com/"
         target="_blank"
         rel="noopener noreferrer"
         aria-label="YouTube">
        <?php if ($yt_logo): ?>
          <img src="<?php echo esc_url($yt_logo); ?>" alt="" class="social-icon">
        <?php endif; ?>
      </a>

      <a class="social-link"
         href="https://discord.com/"
         target="_blank"
         rel="noopener noreferrer"
         aria-label="Discord">
        <?php if ($d_logo): ?>
          <img src="<?php echo esc_url($d_logo); ?>" alt="" class="social-icon">
        <?php endif; ?>
      </a>

    </div>

  </div>
</header>

<!-- Dropdown support for touch devices -->
<script>
(function () {
  const dropdown = document.querySelector('.has-dropdown');
  if (!dropdown) return;

  const btn  = dropdown.querySelector('.dropdown-toggle');
  const menu = dropdown.querySelector('.dropdown-menu');

  btn.addEventListener('click', function (e) {
    e.preventDefault();
    const open = menu.style.display === 'block';
    menu.style.display = open ? 'none' : 'block';
    btn.setAttribute('aria-expanded', open ? 'false' : 'true');
  });

  document.addEventListener('click', function (e) {
    if (!dropdown.contains(e.target)) {
      menu.style.display = 'none';
      btn.setAttribute('aria-expanded', 'false');
    }
  });
})();
</script>
