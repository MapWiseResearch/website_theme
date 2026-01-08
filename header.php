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
  <button class="nav-hamburger" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="primary-menu">
    ☰
  </button>

  <ul id="primary-menu" class="nav-list" hidden>
    <li class="nav-item"><a href="<?php echo esc_url(home_url('/forecasts/')); ?>">Forecasts</a></li>
    <li class="nav-item"><a href="<?php echo esc_url(home_url('/articles/')); ?>">Articles</a></li>

    <li class="nav-item has-dropdown">
      <button class="dropdown-toggle" type="button" aria-haspopup="true" aria-expanded="false">
        Projects <span class="dropdown-caret" aria-hidden="true">▾</span>
      </button>

      <ul class="dropdown-menu" aria-label="Projects submenu" hidden>
        <li><a href="<?php echo esc_url(home_url('/projects/')); ?>">All Projects</a></li>
        <li><a href="<?php echo esc_url(home_url('/projects/mapwise-forecast/')); ?>">MapWise Forecast</a></li>
        <li><a href="<?php echo esc_url(home_url('/projects/data/')); ?>">Data</a></li>
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
  const header = document.querySelector('.site-header');
  if (!header) return;

  const hamburger = header.querySelector('.nav-hamburger');
  const menu = header.querySelector('#primary-menu');

  const dropdown = header.querySelector('.has-dropdown');
  const dropBtn = dropdown ? dropdown.querySelector('.dropdown-toggle') : null;
  const dropMenu = dropdown ? dropdown.querySelector('.dropdown-menu') : null;

  function closeDropdown() {
    if (!dropMenu || !dropBtn) return;
    dropMenu.hidden = true;
    dropBtn.setAttribute('aria-expanded', 'false');
  }

  function toggleDropdown(e) {
    e.preventDefault();
    if (!dropMenu || !dropBtn) return;
    const willOpen = dropMenu.hidden;
    dropMenu.hidden = !willOpen;
    dropBtn.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
  }

  function closeMenu() {
    if (!menu || !hamburger) return;
    menu.hidden = true;
    hamburger.setAttribute('aria-expanded', 'false');
    closeDropdown();
  }

  function toggleMenu() {
    if (!menu || !hamburger) return;
    const willOpen = menu.hidden;
    menu.hidden = !willOpen;
    hamburger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
    if (!willOpen) closeDropdown();
  }

  // Initialize closed (prevents weird “open on load”)
  if (menu) menu.hidden = true;
  closeDropdown();

  if (hamburger && menu) {
    hamburger.addEventListener('click', function (e) {
      e.preventDefault();
      toggleMenu();
    });
  }

  if (dropBtn && dropMenu) {
    dropBtn.addEventListener('click', toggleDropdown);
  }

  document.addEventListener('click', function (e) {
    // close dropdown if click outside it
    if (dropdown && !dropdown.contains(e.target)) closeDropdown();
    // close mobile menu if click outside nav entirely (optional)
    if (menu && hamburger && !header.querySelector('.site-nav').contains(e.target)) {
      // only do this on mobile widths
      if (window.matchMedia('(max-width: 860px)').matches) closeMenu();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeMenu();
  });
})();
</script>
