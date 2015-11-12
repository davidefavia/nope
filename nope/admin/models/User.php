<?php

use RedBeanPHP\R as R;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class User extends Nope\Model {

  const MODELTYPE = 'user';

  function validate() {
    $userValidator = v::attribute('username', v::alnum()->noWhitespace()->length(1,20))
      ->attribute('password', v::stringType()->noWhitespace()->notEmpty())
      ->attribute('email', v::stringType()->email());
    try {
      // @TODO better validation, obviously, do not create COPY!
      $copy = new stdClass();
      $copy->username = $this->model->username;
      $copy->password = $this->model->password;
      $copy->email = $this->email;
      var_dump($copy);
      #die();
      $userValidator->check($copy);
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
    if($user && v::identical($user->password)->validate(hashPassword($password,$user->salt))) {
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
      if($salt === $user->salt.NOPE_SALT) {
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
    }
    return true;
  }

  function saveInSession() {
    $_SESSION['nope.user'] = implode('|', [$this->username, $this->salt.NOPE_SALT]);
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
    } elseif(!v::email()->length(1,255)->validate($this->email)) {
      $e = new \Exception("Not a valid email.");
      throw $e;
    }
    parent::beforeSave();
  }

  static public function findByUsername($username) {
    return self::__transform(R::findOne(self::MODELTYPE, 'username = ?', [$username]));
  }

  static public function findByEmail($email) {
    return self::__transform(R::findOne(self::MODELTYPE, 'email = ?', [email]));
  }

}
