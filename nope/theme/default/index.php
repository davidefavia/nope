<?php include NOPE_THEME_DIR . '_common/header.php'; ?>
<main class="wrapper">
  <header>
    <section class="container">
      <h1><?php echo $setting->headline; ?></h1>
    </section>
  </header>
  <section class="container">
    <article>
      <h5><?php echo $content->title; ?></h5>
      <h6>Published <?php echo $content->startPublishingDate->diffForHumans(); ?></h6>
      <?php echo $content->body; ?>
    </article>
  </section>
</main>
<?php include NOPE_THEME_DIR . '_common/footer.php'; ?>
