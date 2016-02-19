<?php

namespace Nope;

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class User extends \Nope\Model {

  const MODELTYPE = 'user';

  public function import($body, $fields) {
    if(is_array($fields)) {
      foreach($fields as $f) {
        if($f === 'cover') {
          $this->setCover($body[$f]);
        } else {
          if(array_key_exists($f, $body)) {
            $this->model->$f = $body[$f];
          }
        }
      }
    }
  }

  function jsonSerialize() {
    $obj = parent::jsonSerialize();
    $obj->enabled = (int) $obj->enabled;
    $obj->permissions = $this->getPermissions();
    if($obj->coverId) {
      $cover = Media::findById($obj->coverId);
      if($cover) {
        unset($cover->model->author_id);
      }
      $cover = $cover->toJson();
    }
    $obj->cover = $cover;
    unset($obj->password);
    unset($obj->salt);
    unset($obj->lastLoginDate);
    unset($obj->resetCode);
    unset($obj->coverId);
    return $obj;
  }

  function getCover() {
    if($this->model->cover_id) {
      return $this->model->fetchAs('media')->cover;
    }
    return null;
  }

  private function setCover($value) {
    if($value['id']) {
      $media = new Media($value['id']);
      if($media) {
        $this->model->cover = $media->model;
      } else {
        $this->model->cover = null;
      }
    } else {
      $this->model->cover_id = null;
    }
  }

  function validate() {
    $userValidator = v::attribute('username', v::regex(Utils::USERNAME_REGEX_PATTERN))
      ->attribute('password', v::stringType()->noWhitespace()->notEmpty())
      ->attribute('email', v::regex(Utils::EMAIL_REGEX_PATTERN))
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
    $salt = \Nope\Utils::generateSalt($value);
    $hashedPassword = \Nope\Utils::hashPassword($value,$salt);
    $this->model->salt = $salt;
    $this->model->password = $hashedPassword;
  }

  static function login($username, $password) {
    $user = self::findByUsername($username);
    if($user && v::identical($user->password)->validate(\Nope\Utils::hashPassword($password,$user->salt)) && $user->enabled==1) {
      self::authenticate($user);
      return true;
    } else {
      return false;
    }
  }

  static function authenticate($user) {
    $user->lastLoginDate = new \DateTime();
    $user->resetCode = null;
    $user->save();
    $user->saveInSession();
    return true;
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

  static public function findByUsername($username) {
    return self::__to(R::findOne(self::__getModelType(), 'username = ?', [$username]));
  }

  static public function findByEmail($email) {
    return self::__to(R::findOne(self::__getModelType(), 'email = ?', [$email]));
  }

  static public function findByResetCode($resetCode) {
    return self::__to(R::findOne(self::__getModelType(), 'reset_code = ?', [$resetCode]));
  }

  static function __getSql($filters, &$params=[], $p = null) {
    $sql = parent::__getSql($filters, $params, $p);
    $filters = (object) $filters;
    if($filters->role) {
      $sql[] = 'role = ?';
      $params[] = $filters->role;
    }
    if($filters->text) {
      if(count($sql)) {
        $sql[] = 'and';
      }
      $sql[] = '('.$p.'username like ? OR pretty_name like ?)';
      $like = '%' . $filters->text . '%';
      $params[] = $like;
      $params[] = $like;
    }
    return $sql;
  }

  static public function findAll($filters=[], $limit=-1, $offset=0, &$count=0, $orderBy='id asc') {
    return parent::findAll($filters, $limit, $offset, $count, $orderBy);
  }

}
