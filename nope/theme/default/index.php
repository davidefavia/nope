<?php include NOPE_THEME_DIR . '_common/header.php'; ?>
<main class="wrapper">
  <header>
    <section class="container">
      <h1><?php echo $setting->headline; ?></h1>
      <?php if($themeSetting->cover) { ?>
        <?php echo doWidget('[n:media template="caption" id="'.$themeSetting->cover->id.'"]'); ?>
      <?php } ?>
    </section>
  </header>
  <section class="container">
    <article>
      <h2><?php echo $content->title; ?></h2>
      <h3>Published <?php echo $content->startPublishingDate->diffForHumans(); ?></h3>
      <?php echo $content->parsedBody; ?>
    </article>
  </section>
</main>
<?php include NOPE_THEME_DIR . '_common/footer.php'; ?>
