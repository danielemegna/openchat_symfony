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
      return $u->username === $username;
    });
    return array_pop($results);
  }

  function getById($id) {
    $results = array_filter($this->users, function($u) use ($id) {
      return $u->id === $id;
    });
    return array_pop($results);
  }

  function existsById($id) {
    return !is_null($this->getById($id));
  }

  function store($user) {
    $toBeStored = $user->with(['id' => $this->generateUserId()]);
    array_push($this->users, $toBeStored);
    return $toBeStored->id;
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
