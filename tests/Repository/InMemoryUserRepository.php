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

  function getById($id) {
    $results = array_filter($this->users, function($u) use ($id) {
      return $u->getId() === $id;
    });
    return array_pop($results);
  }

  function existsById($id) {
    return !is_null($this->getById($id));
  }

  function store($user) {
    $toBeStored = User::build(
      $this->generateUserId(),
      $user->getUsername(),
      $user->getAbout(),
      $user->getPassword()
    );
    array_push($this->users, $toBeStored);
    return $toBeStored->getId();
  }

  private function generateUserId() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
      mt_rand( 0, 0xffff ),
      mt_rand( 0, 0x0fff ) | 0x4000,
      mt_rand( 0, 0x3fff ) | 0x8000,
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }
}
