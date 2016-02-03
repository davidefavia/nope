<?php include NOPE_THEME_DIR . '_common/header.php'; ?>
<header>
  <section class="container">
    <?php if($themeSetting->cover) {
      $cover = $themeSetting->cover;
    ?>
      <img src="<?php echo $cover->preview->thumb; ?>" title="<?php echo $cover->title; ?>" class="rounded" />
    <?php } ?>
    <h1><?php echo $setting->headline; ?></h1>
  </section>
</header>
<section class="container">
  <article>
    <h2><?php echo $content->title; ?></h2>
    <h3>Published <?php echo $content->startPublishingDate->diffForHumans(); ?></h3>
    <?php echo $content->parsedBody; ?>
  </article>
</section>
<?php include NOPE_THEME_DIR . '_common/footer.php'; ?>
