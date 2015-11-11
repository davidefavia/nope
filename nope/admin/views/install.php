<!DOCTYPE html>
<html ng-app="nope">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
    <link href="<?php echo path('admin/assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />
  </head>
  <body>
    <div class="container">
      <form>
        <h3>Installation</h3>
        <div class="list-group">
          <div class="list-group-item list-group-item-<?php echo ($php->passed?'success':'danger'); ?>">
            <p>PHP version: <?php echo $php->actual; ?><br>PHP minimum required version: <?php echo $php->required; ?></p>
          </div>
          <div class="list-group-item list-group-item-<?php echo ($sqlite->passed?'success':'danger'); ?>">
            <p>SQLite connection: <?php echo $sqlite->passed; ?></p>
          </div>
          <div class="list-group-item list-group-item-<?php echo ($folders->passed?'success':'warning'); ?>">
            <p><?php echo NOPE_DATA_PATH; ?> writeable: <?php echo $folders->passed; ?></p>
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
          <div class="list-group-item">
            <div class="form-group">
              <label>Username:</label>
              <input type="text" class="form-control" value="" placeholder="Username" />
            </div>
            <div class="form-group">
              <label>Password:</label>
              <input type="text" class="form-control" value="" placeholder="Password" />
            </div>
            <div class="form-group">
              <label>Timezone:</label>
              <select class="form-control">
                <option></option>
                <?php foreach ($timezone->list as $key => $value) { ?>
                <option value="<?php echo key; ?>"><?php echo $value; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </ul>
      </div>
    </form>
  </body>
</html>
