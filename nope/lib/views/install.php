<!DOCTYPE html>
<html ng-app>
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link href="<?php echo path('lib/assets/css/app.min.css'); ?>" rel="stylesheet" />
  </head>
  <body>
    <div class="container front">
      <div class="row">
        <div class="col-md-offset-3 col-md-6">
          <div class="logo">
            <img src="<?php echo path('lib/assets/img/nope.png'); ?>" />
          </div>
          <div class="panel panel-default">
            <div class="panel-body">
              <form method="POST" action="<?php echo adminRoute('install'); ?>" name="installationForm">
                <ol class="breadcrumb">
                  <li <?php if($step===1) { ?>class="active"<?php } ?>>1. Check requirements</li>
                  <li <?php if($step===2) { ?>class="active"<?php } ?>>2. User info</li>
                  <li>3. Install</li>
                </ol>
                <div class="list-group">
                  <?php if($step === 1 || !$ok ) { ?>
                  <div class="list-group-item list-group-item-<?php echo ($php->passed?'success':'danger'); ?>">
                    <h4 class="list-group-item-heading">PHP version</h4>
                    <p class="list-group-item-text">Installed version: <?php echo $php->actual; ?><br>Minimum required version: <?php echo $php->required; ?></p>
                  </div>
                  <div class="list-group-item list-group-item-<?php echo ($sqlite->passed?'success':'danger'); ?>">
                    <h4 class="list-group-item-heading">SQLite database</h4>
                    <p class="list-group-item-text">Connection: <?php echo $sqlite->passed; ?><br>Path: <?php echo $sqlite->dbPath; ?><br>Writeable path: <?php echo $sqlite->isDbPathWriteable; ?></p>
                  </div>
                  <div class="list-group-item list-group-item-<?php echo ($folders->passed?'success':'warning'); ?>">
                    <h4 class="list-group-item-heading">Storage</h4>
                    <p class="list-group-item-text"><?php echo NOPE_STORAGE_DIR; ?> writeable: <?php echo $folders->passed; ?></p>
                  </div>
                  <div class="list-group-item list-group-item-<?php echo ($nope->passed?'success':'danger'); ?>">
                    <h4 class="list-group-item-heading">Security</h4>
                    <?php if(!$nope->passed) { ?>
                      <div class="form-group">
                        <label>Salt suggestion:</label>
                        <input type="text" class="form-control input-sm" value="<?php echo $nope->suggestion; ?>" readonly />
                      </div>
                    <?php } else { ?>
                      <p class="list-group-item-text">Salt: <?php echo NOPE_SECURITY_SALT; ?></p>
                    <?php } ?>
                  </div>
                  <?php } ?>
                </div>
                <?php if($step === 2 && $ok ) { ?>
                  <?php if($user===false) { ?>
                  <p>Something wrong.</p>
                  <?php } ?>
                  <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" ng-model="user.username" class="form-control" value="" placeholder="Username" required />
                  </div>
                  <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" ng-model="user.password" class="form-control" value="" placeholder="Password" require />
                  </div>
                  <div class="form-group">
                    <label>Confirm password:</label>
                    <input type="password" name="confirm" ng-model="user.confirm" class="form-control" value="" placeholder="Confirm password" required />
                  </div>
                  <div class="form-group">
                    <label>Email:</label>
                    <input type="email" name="email"  ng-model="user.email" class="form-control" value="" placeholder="Email" required />
                  </div>
                  <!--
                  <div class="form-group">
                    <label>Timezone:</label>
                    <select class="form-control">
                      <option></option>
                      <?php foreach ($timezone->list as $key => $value) { ?>
                      <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                -->
                <?php } ?>
                <div class="form-group">
                  <?php if($step === 2 ) { ?>
                  <button type="submit" class="btn btn-success btn-block btn-lg" ng-disabled="installationForm.$invalid">Install</button>
                  <?php } else { ?>
                    <?php if($ok) { ?>
                      <button class="btn btn-primary btn-block">Go forward</button>
                    <?php } else { ?>
                      <a href="" class="btn btn-warning btn-block">Check again</a>
                    <?php } ?>
                  <?php } ?>
                </div>
              </div>
            </form>
            </div>
          </div>
        </div>
      </div>
      <script src="<?php echo path('lib/assets/js/lib/angular.min.js'); ?>"></script>
  </body>
</html>
