<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header class="site-header">
  <div class="site-branding">
    <?php if (has_custom_logo()) : ?>
      <?php the_custom_logo(); ?>
    <?php else : ?>
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <strong><?php bloginfo('name'); ?></strong>
      </a>
    <?php endif; ?>
  </div>

  <nav class="site-nav">
    <?php
      wp_nav_menu([
        'theme_location' => 'primary',
        'container' => false,
        'fallback_cb' => false,
      ]);
    ?>
  </nav>
</header>
