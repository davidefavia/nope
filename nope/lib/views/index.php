<!DOCTYPE html>
<html ng-app="nope">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Admin</title>
    <link href="<?php echo path('lib/assets/css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo path('lib/assets/css/simplemde.min.css'); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap-flex.min.css">
    <link href="<?php echo path('lib/assets/css/app.min.css'); ?>" rel="stylesheet" />
    <script>
      window.NOPE_BASE_PATH = "<?php echo rtrim(NOPE_BASE_PATH, '/') . NOPE_ADMIN_ROUTE . '/' ?>";
      window.NOPE_IFRAME = <?php echo $isIframe; ?>;
      window.NOPE_TEMPLATES_PATH = "<?php echo path('lib/'); ?>";
      window.NOPE_USER_ROLES = <?php echo json_encode($userRoles); ?>;
      window.NOPE_TEXT_FORMATS = <?php echo json_encode($textFormats); ?>;
      window.NOPE_DEFAULT_TEXT_FORMAT = '<?php echo $defaultTextFormat; ?>';
    </script>
    <?php if(file_exists(NOPE_APP_VIEWS_PATH . '_common/header.php')) {
      include_once NOPE_APP_VIEWS_PATH . '_common/header.php';
    } ?>
  </head>
  <body <?php if($isIframe==='true') { ?>class="is-iframe"<?php } ?>>
    <ui-view></ui-view>
    <script src="<?php echo path('lib/assets/js/lib/angular.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/angular-messages.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/angular-resource.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/angular-sanitize.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/angular-ui-router.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/ng-file-upload.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/angular-drag-and-drop-lists.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/moment.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/simplemde.min.js'); ?>"></script>
    <?php foreach($js as $file) { ?>
    <script src="<?php echo path($file); ?>"></script>
    <?php } ?>
    <?php if(file_exists(NOPE_APP_VIEWS_PATH . '_common/footer.php')) {
      include_once NOPE_APP_VIEWS_PATH . '_common/footer.php';
    } ?>
    <?php if(NOPE_DEVELOPMENT===true) { ?>
    <script>
      document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] +
      ':35729/livereload.js?snipver=1"></' + 'script>')
    </script>
    <?php } ?>
  </body>
</html>
