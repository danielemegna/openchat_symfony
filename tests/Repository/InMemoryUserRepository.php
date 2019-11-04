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
      $this->gen_uuid(),
      $user->getUsername(),
      $user->getAbout(),
      $user->getPassword()
    );
    array_push($this->users, $toBeStored);
    return $toBeStored->getId();
  }

  private function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

      // 16 bits for "time_mid"
      mt_rand( 0, 0xffff ),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand( 0, 0x0fff ) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand( 0, 0x3fff ) | 0x8000,

      // 48 bits for "node"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }
}
