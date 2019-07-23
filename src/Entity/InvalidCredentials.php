<?php

namespace App\Entity;

final class InvalidCredentials extends User {

  function __construct(string $username, string $password) {
    $this->username = $username;
    $this->password = $password;
  }

  public function getId() { return null; }
  public function getAbout() { return null; }

}
