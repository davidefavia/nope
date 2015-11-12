<!DOCTYPE html>
<html ng-app="nope">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link href="<?php echo path('admin/assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />
  </head>
  <body>
    <div class="container">
      <form method="POST">
        <h3>Installation</h3>
        <div class="list-group">
          <?php if($step === 1 || !$ok ) { ?>
          <div class="list-group-item list-group-item-<?php echo ($php->passed?'success':'danger'); ?>">
            <p>PHP version: <?php echo $php->actual; ?><br>PHP minimum required version: <?php echo $php->required; ?></p>
          </div>
          <div class="list-group-item list-group-item-<?php echo ($sqlite->passed?'success':'danger'); ?>">
            <p>SQLite connection: <?php echo $sqlite->passed; ?></p>
            <p>SQLite path: <?php echo $sqlite->dbPath; ?></p>
            <p>SQLite path writeable: <?php echo $sqlite->isDbPathWriteable; ?></p>
          </div>
          <div class="list-group-item list-group-item-<?php echo ($folders->passed?'success':'warning'); ?>">
            <p><?php echo NOPE_STORAGE_PATH; ?> writeable: <?php echo $folders->passed; ?></p>
          </div>
          <div class="list-group-item list-group-item-<?php echo ($nope->passed?'success':'danger'); ?>">
            <p>Security salt</p>
            <?php if(!$nope->passed) { ?>
              <div class="form-group">
                <label>Suggestion:</label>
                <input type="text" class="form-control" value="<?php echo $nope->suggestion; ?>" readonly />
              </div>
            <?php } ?>
          </div>
          <?php } ?>
          <?php if($step === 2 && $ok ) { ?>
          <div class="list-group-item <?php if($user===false) { ?>list-group-item-warning<?php } ?>">
            <?php if($user===false) { ?>
            <p>Something wrong.</p>
            <?php } ?>
            <div class="form-group">
              <label>Username:</label>
              <input type="text" name="username" class="form-control" value="" placeholder="Username" />
            </div>
            <div class="form-group">
              <label>Password:</label>
              <input type="password" name="password" class="form-control" value="" placeholder="Password" />
            </div>
            <div class="form-group">
              <label>Confirm password:</label>
              <input type="password" name="confirm" class="form-control" value="" placeholder="Confirm password" />
            </div>
            <div class="form-group">
              <label>Email:</label>
              <input type="text" name="email" class="form-control" value="" placeholder="Email" />
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
          </div>
          <?php } ?>
        </div>
        <div class="form-group">
          <?php if($step === 2 ) { ?>
          <button class="btn">Install</button>
          <?php } else { ?>
            <?php if($ok) { ?>
              <button class="btn">Go forward</button>
            <?php } else { ?>
              <a href="" class="button">Check again</a>
            <?php } ?>
          <?php } ?>
        </div>
      </div>
    </form>
  </body>
</html>
