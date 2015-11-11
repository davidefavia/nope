<?php

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;

$app->group(NOPE_ADMIN_ROUTE, function() {

  $this->get('', function ($req, $res) {
    return $this->view->adminRender($res, 'index.php', ['request' => $req]);
  });

  $this->get('/', function ($req, $res) {
    return $this->view->adminRender($res, 'index.php', ['request' => $req]);
  });

  $this->get('/install', function ($req, $res) {
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
      'passed' => $isConnected
    ];
    // Nope salt
    $passedSalt = v::stringType()->length(1)->not(v::nullType())->validate(NOPE_SALT);
    $suggestedSalt = password_hash("nope".microtime(), PASSWORD_BCRYPT, ['cost' => 12]);
    $data['nope'] = (object) [
      'salt' => NOPE_SALT,
      'suggestion' => $suggestedSalt,
      'passed' => $passedSalt
    ];
    // Folders
    $isDataPathWriteable = is_writable(NOPE_DATA_PATH);
    $data['folders'] = (object) [
      'passed' => $isDataPathWriteable
    ];
    // Timezone
    $data['timezone'] = (object) [
      'list' => timezone_identifiers_list()
    ];


    return $this->view->adminRender($res, 'install.php', $data);
  });

});
