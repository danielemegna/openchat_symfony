<?php

namespace App\Entity;

final class UsernameAlreadyUsed extends User {

  function __construct(string $username) {
    $this->username = $username;
  }

  public function getId() { return null; }
  public function getAbout() { return null; }
  public function getPassword() { return null; }

}
