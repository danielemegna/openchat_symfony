<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use App\Entity\User;

class InMemoryUserRepository implements UserRepository {

  private $users = [];

  function getAll() {
    return $this->users;
  }

  function store($user) {
    $toBeStored = User::build(
      uniqid(),
      $user->getUsername(),
      $user->getAbout(),
      $user->getPassword()
    );
    array_push($this->users, $toBeStored);
    return $toBeStored->getId();
  }
}
