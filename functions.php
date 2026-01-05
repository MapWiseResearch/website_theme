<?php
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'mapwise-styles',
    get_template_directory_uri() . '/assets/css/styles.css',
    [],
    '1.0'
  );
});

add_theme_support('title-tag');

register_nav_menus([
  'primary' => 'Primary Navigation',
]);
