<?php

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE, function() {

  $this->get('', function ($req, $res) {
    return redirect($req, $res, NOPE_ADMIN_ROUTE . '/');
  });

  $this->get('/', function ($req, $res) {
    foreach(\Nope::getConfig('nope.roles') as $key => $value) {
      $roles[] = [
        'label' => $value['label'],
        'key' => $key
      ];
    }
    return $this->view->adminRender($res, 'index.php', ['request' => $req, 'roles' => $roles]);
  });

  $this->map(['GET', 'POST'], '/install', function ($req, $res) {

    if(\Nope::isAlredyInstalled()) {
      return $res->withStatus(302)->withHeader('Location', $req->getUri()->getBasePath() . NOPE_ADMIN_ROUTE);
    }

    $data = [];
    // PHP version
    $phpVersion = phpversion();
    // @TODO: read from composer.json
    $minimumPhpVersion = '5.5.0';
    $isRightPhpVersion = version_compare($phpVersion, $minimumPhpVersion, 'ge');
    $data['php'] = (object) [
      'actual' => $phpVersion,
      'required' => $minimumPhpVersion,
      'passed' => $isRightPhpVersion
    ];
    // SQLite
    $isConnected = R::testConnection();
    $data['sqlite'] = (object) [
      'dbPath' => NOPE_DATABASE_PATH,
      'isDbPathWriteable' => is_writable(basename(NOPE_DATABASE_PATH)),
      'passed' => $isConnected
    ];
    // Nope salt
    $passedSalt = v::stringType()->length(1)->not(v::nullType())->validate(NOPE_SECURITY_SALT);
    $suggestedSalt = password_hash("nope".microtime(), PASSWORD_BCRYPT, ['cost' => 12]);
    $data['nope'] = (object) [
      'salt' => NOPE_SECURITY_SALT,
      'suggestion' => $suggestedSalt,
      'passed' => $passedSalt
    ];
    // Folders
    $isDataPathWriteable = is_writable(NOPE_STORAGE_PATH);
    $data['folders'] = (object) [
      'passed' => $isDataPathWriteable
    ];
    // Timezone
    $data['timezone'] = (object) [
      'list' => timezone_identifiers_list()
    ];
    $data['step'] = 1;

    $data['ok'] = ($isRightPhpVersion && $isConnected && $passedSalt && $isDataPathWriteable);

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

        $setting = new Setting();
        $setting->group = 'nope';
        $setting->key = 'installation';
        $setting->value = new DateTime();
        $setting->save();

        return redirect($req, $res, NOPE_ADMIN_ROUTE);
      } else if($body) {
        $data['user'] = false;
      }
    }


    return $this->view->adminRender($res, 'install.php', $data);
  });

});
