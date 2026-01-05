<?php get_header(); ?>

<main class="container" role="main">
  <section id="highlights">

    <img
      src="<?php echo get_template_directory_uri(); ?>/assets/img/banner.png"
      alt="Banner"
      class="banner"
    >

    <div
      class="highlight-card"
      style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/redandblue.png');"
    >
      <div class="highlight-content">
        <h2><mark>Forecasts</mark></h2>
        <p><mark>View our latest political forecasts and models for the 2026 midterm elections.</mark></p>
        <a href="/2026" class="btn"><mark>View Forecasts</mark></a>
      </div>
    </div>

    <div
      class="highlight-card"
      style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/img/computer.jpeg');"
    >
      <div class="highlight-content">
        <h2><mark>Projects</mark></h2>
        <p><mark>View our latest data projects and experiments.</mark></p>
        <a href="/projects" class="btn"><mark>View Projects</mark></a>
      </div>
    </div>

  </section>
</main>

<?php get_footer(); ?>
