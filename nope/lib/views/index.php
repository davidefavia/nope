<!DOCTYPE html>
<html ng-app="nope">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Admin</title>
    <link href="<?php echo path('lib/assets/css/font-awesome.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo path('lib/assets/css/simplemde.min.css'); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <link href="<?php echo path('lib/assets/css/app.min.css'); ?>" rel="stylesheet" />
    <link href="<?php echo path('lib/assets/css/photoswipe.css'); ?>" rel="stylesheet" />
    <link href="<?php echo path('lib/assets/css/default-skin/default-skin.css'); ?>" rel="stylesheet" />
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
    <script src="<?php echo path('lib/assets/js/lib/photoswipe.min.js'); ?>"></script>
    <script src="<?php echo path('lib/assets/js/lib/photoswipe-ui-default.min.js'); ?>"></script>
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
    <!-- Root element of PhotoSwipe. Must have class pswp. -->
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <!-- Background of PhotoSwipe.
             It's a separate element as animating opacity is faster than rgba(). -->
        <div class="pswp__bg"></div>

        <!-- Slides wrapper with overflow:hidden. -->
        <div class="pswp__scroll-wrap">

            <!-- Container that holds slides.
                PhotoSwipe keeps only 3 of them in the DOM to save memory.
                Don't modify these 3 pswp__item elements, data is added later on. -->
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">

                    <!--  Controls are self-explanatory. Order can be changed. -->

                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>

                    <!--<button class="pswp__button pswp__button--share" title="Share"></button>-->

                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                    <!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
                    <!-- element will get class pswp__preloader--active when preloader is running -->
                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                          <div class="pswp__preloader__cut">
                            <div class="pswp__preloader__donut"></div>
                          </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
                </button>

                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
                </button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>

            </div>

        </div>

    </div>
  </body>
</html>
