<?php include NOPE_THEME_DIR . '_common/header.php'; ?>
<section class="container">
  <article>
    <h2><?php echo $content->title; ?></h2>
    <h3>Published <?php echo $content->startPublishingDate->diffForHumans(); ?></h3>
    <?php echo $content->parsedBody; ?>
  </article>
</section>
<?php include NOPE_THEME_DIR . '_common/footer.php'; ?>
