<?php

class Auth {
  private $user_storage;
  private $user = NULL;

  public function __construct(IStorage $user_storage) {
    $this->user_storage = $user_storage;

    if (isset($_SESSION["user"])) {
      $this->user = $_SESSION["user"];
    }
  }

  public function register($data) {
    $user = [
      "fullname"  => $data["fullname"],
      "address" => $data["address"],
      "SSN" => $data["SSN"],
      "email" => $data["email"],
      "time" => "",
      "password"  => password_hash($data["password"], PASSWORD_DEFAULT),     
      "roles"     => ["user"],
    ];
    if( !is_null( $this->authenticate($user["email"], $user["password"])) || $this->user_exists($user["email"])){
      return NULL;
    }
    return $this->user_storage->add($user);
  }

  public function user_exists($email) {
    $users = $this->user_storage->findOne(["email" => $email]);
    return !is_null($users);
  }

  public function authenticate($email, $password) {
    $users = $this->user_storage->findMany(function ($user) use ($email, $password) {
      return $user["email"] === $email &&
             password_verify($password, $user["password"]);
    });
    
    return count($users) === 1 ? array_shift($users) : NULL;
  }
  
  public function is_authenticated() {
    return !is_null($this->user);
  }

  public function authorize($roles = []) {
    if (!$this->is_authenticated()) {
      return FALSE;
    }
    foreach ($roles as $role) {
      if (in_array($role, $this->user["roles"])) {
        return TRUE;
      }
    }
    return FALSE;
  }

  public function login($user) {
    $this->user = $user;
    $_SESSION["user"] = $user;
  }

  public function logout() {
    $this->user = NULL;
    unset($_SESSION["user"]);
  }

  public function authenticated_user() {
    return $this->user;
  }

  // public function update_user($user){
  //   unset($_SESSION["user"]); 
  //   $this->user = $user;
  //   $_SESSION["user"] = $user;
  // }
}