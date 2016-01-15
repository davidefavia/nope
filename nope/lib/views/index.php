<!DOCTYPE html>
<html ng-app="nope">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link href="<?php echo path('lib/assets/css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo path('lib/assets/css/app.min.css'); ?>" rel="stylesheet" />
    <script>
      window.NOPE_BASE_PATH = "<?php echo $request->getUri()->getBasePath() . '/' . $request->getUri()->getPath(); ?>";
      window.NOPE_IFRAME = <?php echo $isIframe; ?>;
      window.NOPE_TEMPLATES_PATH = "<?php echo path('lib/'); ?>";
      window.NOPE_USER_ROLES = <?php echo json_encode($userRoles); ?>;
      window.NOPE_TEXT_FORMATS = <?php echo json_encode($textFormats); ?>;
    </script>
  </head>
  <body>
    <ui-view></ui-view>
    <script src="<?php echo path('lib/assets/js/lib/angular.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/angular-messages.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/angular-resource.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/angular-sanitize.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/angular-ui-router.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/ng-file-upload.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/moment.min.js'); ?>"></script>
    <?php foreach($js as $file) { ?>
    <script src="<?php echo path($file); ?>"></script>
    <?php } ?>
    <script>
      document.write('<script src="http://' + (location.host || 'localhost').split(':')[0] +
      ':35729/livereload.js?snipver=1"></' + 'script>')
    </script>
  </body>
</html>
