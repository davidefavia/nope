<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE, function() {


  $this->map(['GET', 'POST'], '/install', function ($req, $res) {

    if(\Nope::isAlredyInstalled()) {
      return $res->withStatus(302)->withHeader('Location', $req->getUri()->getBaseFolder() . NOPE_ADMIN_ROUTE);
    }
    $data = [];
    $requirements = [];
    // PHP version
    $phpVersion = phpversion();
    // @TODO: read from composer.json
    $minimumPhpVersion = '5.5.0';
    $isRightPhpVersion = version_compare($phpVersion, $minimumPhpVersion, 'ge');
    $requirements['php'] = (object) [
      'passed' => $isRightPhpVersion,
      'title' => 'PHP version',
      'icon' => 'code',
      'lines' => [
        'Installed version: ' . phpversion(),
        'Minimum required version: ' . $minimumPhpVersion
      ]
    ];
    // SQLite
    $isConnected = R::testConnection();
    $isDatabaseFolderWriteable = is_writable(basename(NOPE_DATABASE_PATH));
    $isDatabaseOk = $isConnected && $isDatabaseFolderWriteable;
    $requirements['sqlite'] = (object) [
      'passed' => $isDatabaseOk,
      'title' => 'SQLite database',
      'icon' => 'database',
      'lines' => [
        'Connection: <code>' . var_export($isDatabaseOk, true) . '</code>',
        'Database path: <code>' . NOPE_DATABASE_PATH . '</code>',
        'Database folder writeable: <code>' . var_export($isDatabaseFolderWriteable, true) . '</code>'
      ]
    ];
    // Folders
    $isStorageFolderWriteable = is_writable(NOPE_STORAGE_DIR);
    $isCacheFolderWriteable = is_writable(NOPE_CACHE_DIR);
    $isUploadsFolderWriteable = is_writable(NOPE_UPLOADS_DIR);
    $isBackupsFolderWriteable = is_writable(NOPE_BACKUPS_DIR);
    $areFoldersWriteAble = ($isStorageFolderWriteable && $isCacheFolderWriteable && $isUploadsFolderWriteable && $isBackupsFolderWriteable);
    $requirements['folders'] = (object) [
      'passed' => $areFoldersWriteAble,
      'title' => 'Storage',
      'icon' => 'folder-open',
      'lines' => [
        #'Storage folder: <code>' . NOPE_STORAGE_DIR . '</code>',
        'Storage folder writeable: <code>' . var_export($isStorageFolderWriteable, true) . '</code>',
        #'Cache folder: <code>' . NOPE_CACHE_DIR . '</code>',
        'Cache folder writeable: <code>' . var_export($isCacheFolderWriteable, true) . '</code>',
        #'Uploads folder: <code>' . NOPE_UPLOADS_DIR . '</code>',
        'Uploads folder writeable: <code>' . var_export($isUploadsFolderWriteable, true) . '</code>',
        #'Backups folder: <code>' . NOPE_BACKUPS_DIR . '</code>',
        'Backups folder writeable: <code>' . var_export($isBackupsFolderWriteable, true) . '</code>'
      ]
    ];
    // Security
    $passedSalt = v::stringType()->length(1)->not(v::nullType())->validate(NOPE_SECURITY_SALT);
    $suggestedSalt = password_hash("nope".microtime(), PASSWORD_BCRYPT, ['cost' => 12]);
    $requirements['security'] = (object) [
      'passed' => $passedSalt,
      'title' => 'Security',
      'icon' => 'certificate',
      'lines' => [
        'Salt: <code>' . NOPE_SECURITY_SALT . '</code>'
      ]
    ];
    // Timezone
    $requirements['timezone'] = (object) [
      'passed' => true,
      'title' => 'Timezone',
      'icon' => 'globe',
      'lines' => [
        'Setted timezone: ' => date_default_timezone_get()
      ]
    ];
    $data['step'] = 1;

    $data['ok'] = ($isRightPhpVersion && $isDatabaseOk && $passedSalt && $areFoldersWriteAble);

    $data['requirements'] = $requirements;

    if($req->isPost() && $data['ok']) {
      $data['step'] = 2;
      $body = $req->getParsedBody();
      if($body['username'] && $body['password'] && v::identical($body['password'])->validate($body['confirm']) && v::email()->validate($body['email'])) {
        $user = new User();
        $user->username = $body['username'];
        $user->setPassword($body['password']);
        $user->email = $body['email'];
        $user->enabled = 1;
        $user->prettyName = null;
        $user->description = null;
        $user->role = 'admin';
        $user->save();
        $user->saveInSession();

        $setting = new Setting();
        $setting->group = 'nope';
        $setting->key = 'installation';
        $setting->value = new \DateTime();
        $setting->save();

        return redirect($req, $res, NOPE_ADMIN_ROUTE);
      } else if($body) {
        $data['user'] = false;
      }
    }


    return $this->view->adminRender($res, 'install.php', $data);
  });

});
