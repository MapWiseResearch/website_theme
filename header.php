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
<?php wp_body_open(); ?>

<?php
$banner   = mapwise_media_url_by_filename('banner.png');
$x_logo   = mapwise_media_url_by_filename('x_logo.png');
$yt_logo  = mapwise_media_url_by_filename('yt_logo.png');
$acct_logo= mapwise_media_url_by_filename('account2-1.png');
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
      <button
        class="nav-hamburger"
        type="button"
        aria-label="Open menu"
        aria-expanded="false"
        aria-controls="primary-menu"
      >☰</button>

      <ul id="primary-menu" class="nav-list">
        <li class="nav-item"><a href="<?php echo esc_url(home_url('/subscribe/')); ?>">Subscribe</a></li>
        <li class="nav-item"><a href="<?php echo esc_url(home_url('/forecasts/')); ?>">Forecasts</a></li>
        <li class="nav-item"><a href="<?php echo esc_url(home_url('/articles/')); ?>">Articles</a></li>

        <li class="nav-item has-dropdown">
          <button class="dropdown-toggle" type="button" aria-haspopup="true" aria-expanded="false">
            Projects <span class="dropdown-caret" aria-hidden="true">▾</span>
          </button>

          <ul class="dropdown-menu" aria-label="Projects submenu">
            <li><a href="<?php echo esc_url(home_url('/projects/')); ?>">All Projects</a></li>
            <li><a href="<?php echo esc_url(home_url('/projects/mapwise-forecast/')); ?>">MapWise Forecast</a></li>
            <li><a href="<?php echo esc_url(home_url('/projects/data/')); ?>">Data</a></li>
          </ul>
        </li>

        <!-- Mobile-only icons inside menu -->
        <li class="nav-item nav-social mobile-only">
          <a class="social-in-menu" href="<?php echo esc_url(home_url('/account/')); ?>" aria-label="Account">
            Account
          </a>
        </li>
        <li class="nav-item nav-social mobile-only">
          <a class="social-in-menu" href="https://x.com/" target="_blank" rel="noopener" aria-label="X">
            <?php if ($x_logo): ?><img src="<?php echo esc_url($x_logo); ?>" alt="X" class="social-icon"><?php endif; ?>
          </a>
        </li>
        <li class="nav-item nav-social mobile-only">
          <a class="social-in-menu" href="https://www.youtube.com/" target="_blank" rel="noopener" aria-label="YouTube">
            <?php if ($yt_logo): ?><img src="<?php echo esc_url($yt_logo); ?>" alt="YouTube" class="social-icon"><?php endif; ?>
          </a>
        </li>
      </ul>

      <div class="topbar-search">
        <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
          <input
            type="search"
            name="s"
            placeholder="Search…"
            value="<?php echo get_search_query(); ?>"
            aria-label="Search"
          >
        </form>
      </div>
    </nav>

    <!-- Desktop socials (far right) -->
    <div class="topbar-social" aria-label="Social links">
      <a class="social-link" href="<?php echo esc_url(home_url('/account/')); ?>" aria-label="Account">
        <?php if ($acct_logo): ?><img src="<?php echo esc_url($acct_logo); ?>" alt="" class="social-icon"><?php endif; ?>
      </a>
      <a class="social-link" href="https://x.com/" target="_blank" rel="noopener" aria-label="X">
        <?php if ($x_logo): ?><img src="<?php echo esc_url($x_logo); ?>" alt="" class="social-icon"><?php endif; ?>
      </a>
      <a class="social-link" href="https://www.youtube.com/" target="_blank" rel="noopener" aria-label="YouTube">
        <?php if ($yt_logo): ?><img src="<?php echo esc_url($yt_logo); ?>" alt="" class="social-icon"><?php endif; ?>
      </a>
    </div>

  </div>
</header>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const header = document.querySelector(".site-header");
  const inner  = header?.querySelector(".topbar-inner");
  const nav    = header?.querySelector(".site-nav");
  const btn    = header?.querySelector(".nav-hamburger");
  if (!header || !inner || !nav || !btn) return;

  btn.addEventListener("click", (e) => {
    e.preventDefault();
    const open = nav.classList.toggle("is-open");
    inner.classList.toggle("nav-open", open);
    btn.setAttribute("aria-expanded", open ? "true" : "false");
  });

  header.querySelectorAll(".has-dropdown > .dropdown-toggle").forEach((toggle) => {
    toggle.addEventListener("click", (e) => {
      e.preventDefault();
      const li = toggle.closest(".has-dropdown");
      if (!li) return;
      const open = li.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });
  });

  document.addEventListener("click", (e) => {
    if (!nav.contains(e.target) && nav.classList.contains("is-open")) {
      nav.classList.remove("is-open");
      inner.classList.remove("nav-open");
      btn.setAttribute("aria-expanded", "false");
    }
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      nav.classList.remove("is-open");
      inner.classList.remove("nav-open");
      btn.setAttribute("aria-expanded", "false");
      header.querySelectorAll(".has-dropdown.is-open").forEach((li) => {
        li.classList.remove("is-open");
        li.querySelector(".dropdown-toggle")?.setAttribute("aria-expanded", "false");
      });
    }
  });
});
</script>
