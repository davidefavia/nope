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
        'Installed: <code>' . phpversion() . '</code>',
        'Minimum required: <code>' . $minimumPhpVersion . '</code>'
      ]
    ];
    // SQLite
    $isConnected = R::testConnection();
    $isDatabaseFolderWriteable = Utils::isPathWriteable(basename(NOPE_DATABASE_PATH));
    $isDatabaseOk = $isConnected && $isDatabaseFolderWriteable;
    $requirements['sqlite'] = (object) [
      'passed' => $isDatabaseOk,
      'title' => 'SQLite database',
      'icon' => 'database',
      'lines' => [
        'Connection: <code>' . var_export($isDatabaseOk, true) . '</code>',
        'Path: <code>' . NOPE_DATABASE_PATH . '</code>'
      ],
      'help' => 'You can change <code>NOPE_DATABASE_PATH</code> value inside <code>config.php</code>.'
    ];
    // Folders
    $isStorageFolderWriteable = Utils::isPathWriteable(NOPE_STORAGE_DIR);
    if(!$isStorageFolderWriteable) {
      Utils::makePathWriteable(NOPE_STORAGE_DIR);
    }
    Utils::createFolderIfDoesntExist(NOPE_CACHE_DIR, 0775);
    Utils::createFolderIfDoesntExist(NOPE_UPLOADS_DIR, 0775);
    Utils::createFolderIfDoesntExist(NOPE_BACKUPS_DIR, 0775);
    $isCacheFolderWriteable = Utils::isPathWriteable(NOPE_CACHE_DIR);
    $isUploadsFolderWriteable = Utils::isPathWriteable(NOPE_UPLOADS_DIR);
    $isBackupsFolderWriteable = Utils::isPathWriteable(NOPE_BACKUPS_DIR);
    $areFoldersWriteAble = ($isStorageFolderWriteable && $isCacheFolderWriteable && $isUploadsFolderWriteable && $isBackupsFolderWriteable);
    $requirements['folders'] = (object) [
      'passed' => $areFoldersWriteAble,
      'title' => 'Storage',
      'icon' => 'folder-open',
      'lines' => [
        'Folder path: <code>' . NOPE_STORAGE_DIR . '</code>',
        'Folder writeable: <code>' . var_export($isStorageFolderWriteable, true) . '</code>'
      ],
      'help' => ($areFoldersWriteAble ? '' : 'You need to change <code>' . NOPE_STORAGE_DIR . '</code> folder permissions.')
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
      ],
      'help' => ($passedSalt ?  'You can change <code>NOPE_SECURITY_SALT</code> value inside <code>config.php</code> whenever you want.' : 'You can use the suggested salt <code>' . $suggestedSalt . '</code> as <code>NOPE_SECURITY_SALT</code> value inside <code>config.php</code>.')
    ];
    // Timezone
    $requirements['timezone'] = (object) [
      'passed' => true,
      'title' => 'Timezone',
      'icon' => 'globe',
      'lines' => [
        'Setted timezone: <code>' . date_default_timezone_get() . '</code>'
      ],
      'help' => 'You can change <code>NOPE_DATETIME_TIMEZONE</code> value inside <code>config.php</code>.'
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
