<?php

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class User extends Nope\Model {

  const MODELTYPE = 'user';

  function jsonSerialize() {
    $obj = parent::jsonSerialize();
    $obj->id = (int) $obj->id;
    $obj->enabled = (int) $obj->enabled;
    $obj->permissions = $this->getPermissions();
    unset($obj->password);
    unset($obj->salt);
    unset($obj->last_login_date);
    unset($obj->reset_code);
    return $obj;
  }

  function validate() {
    $userValidator = v::attribute('username', v::alnum()->noWhitespace()->length(1,20))
      ->attribute('password', v::stringType()->noWhitespace()->notEmpty())
      ->attribute('email', v::optional(v::email()))
      ->attribute('role', v::noWhitespace()->notEmpty())
      ;
    try {
      $userValidator->check((object) $this->model->export());
    } catch(NestedValidationException $exception) {
      throw $exception;
    }
    return true;
  }

  function setPassword($value) {
    $salt = generateSalt($value);
    $hashedPassword = hashPassword($value,$salt);
    $this->model->salt = $salt;
    $this->model->password = $hashedPassword;
  }

  static function authenticate($username, $password) {
    $user = self::findByUsername($username);
    if($user && v::identical($user->password)->validate(hashPassword($password,$user->salt)) && $user->enabled==1) {
      $user->lastLoginDate = new \DateTime();
      $user->resetCode = null;
      $user->save();
      $user->saveInSession();
      return true;
    } else {
      return false;
    }
  }

  static function getAuthenticated() {
    $data = explode('|',$_SESSION['nope.user']);
    $username = (string) $data[0];
    $salt = (string) $data[1];
    $user = self::findByUsername($username);
    if($user) {
      if($salt === $user->salt.NOPE_SECURITY_SALT) {
        return $user;
      } else {
        $user->deleteFromSession();
        return null;
      }
      return $user;
    } else {
      return null;
    }
  }

  function deauthenticate() {
    $this->deleteFromSession();
  }

  static function logout() {
    $user = self::getAuthenticated();
    if($user) {
      $user->deleteFromSession();
      $user = self::getAuthenticated();
      return is_null($user);
    }
    return false;
  }

  function saveInSession() {
    $_SESSION['nope.user'] = implode('|', [$this->username, $this->salt.NOPE_SECURITY_SALT]);
  }

  function deleteFromSession() {
    // Unset all session values
    $_SESSION = array();
    // get session parameters
    $params = session_get_cookie_params();
    // Delete the actual cookie.
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    // Destroy session
    session_unset();
  }

  function beforeSave() {
    $userCheck = self::findByUsername($this->username);
    $emailCheck = self::findByEmail($this->email);
    if(!$this->id && $userCheck) {
      $e = new \Exception("Error saving user due to existing username.");
      throw $e;
    } elseif(!$this->id && $emailCheck) {
      $e = new \Exception("Error saving user due to existing email.");
      throw $e;
    }
    parent::beforeSave();
  }

  function is($role) {
    $role = explode(',',$role);
    return in_array($this->role, $role);
  }

  function isAdmin() {
    return $this->is('admin');
  }

  function can($permission) {
    if($this->isAdmin()) {
      return true;
    } else {
      return $this->hasPermission($permission);
    }
  }

  private function hasPermission($permission) {
    $permissionsList = $this->getPermissions();
    $pieces = explode('.', $permission);
    $section = $pieces[0];
    return in_array($permission, $permissionsList) || in_array($section.'.*', $permissionsList);
  }

  function getPermissions() {
    $permissions = \Nope::getConfig('nope.roles');
    return $permissions[$this->role]['permissions'];
  }

  function delete() {
    $this->beforeDelete();
    parent::delete();
  }

  function beforeDelete() {
    if($this->isAdmin()) {
      $e = new \Exception("Error deleting administrator.");
      throw $e;
    }
  }

  static public function findById($id) {
    return self::__to(R::findOne(self::MODELTYPE, 'id = ?', [$id]));
  }

  static public function findByUsername($username) {
    return self::__to(R::findOne(self::MODELTYPE, 'username = ?', [$username]));
  }

  static public function findByEmail($email) {
    return self::__to(R::findOne(self::MODELTYPE, 'email = ?', [$email]));
  }

  static public function findAll($filters=null, $limit=-1, $offset=0, &$count=0, $orderBy='id asc') {
    $filters = (object) $filters;
    $params = [];
    if($filters->role) {
      $sql[] = 'role = ?';
      $params[] = $filters->role;
    }
    if($orderBy) {
      $sql[] = 'order by '.$orderBy;
    }
    $users = R::findAll(self::MODELTYPE, implode(' ',$sql),$params);
    return self::__to($users, $limit, $offset, $count);
  }

}
