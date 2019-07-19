<?php

namespace App\Repository;

class InMemoryUserRepository implements UserRepository {

  private static $users = [];

  function getAll() {
    return self::$users;
  }

  function store($user) {
    array_push(self::$users, $user);
  }
}
