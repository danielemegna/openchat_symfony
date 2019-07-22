<?php

namespace App\Entity;

class User {

  private $id;
  private $username;
  private $about;
  private $password;

  public static function newWithoutId(string $username, string $about, string $password) {
    return new User(null, $username, $about, $password);
  }

  public static function build(string $id, string $username, string $about, string $password) {
    return new User($id, $username, $about, $password);
  }

  private function __construct(?string $id, string $username, string $about, string $password) {
    $this->id = $id;
    $this->username = $username;
    $this->about = $about;
    $this->password = $password;
  }

  public function getId() { return $this->id; }
  public function getUsername() { return $this->username; }
  public function getAbout() { return $this->about; }
  public function getPassword() { return $this->password; }

}
