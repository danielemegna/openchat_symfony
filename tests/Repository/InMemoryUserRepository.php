<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use App\Entity\User;

class InMemoryUserRepository implements UserRepository {

  private $users = [];

  function getAll() {
    return $this->users;
  }

  function getByUsername($username) {
    $results = array_filter($this->users, function($u) use ($username) {
      return $u->getUsername() === $username;
    });
    return array_pop($results);
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
