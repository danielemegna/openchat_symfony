<?php

namespace App\Repository;

use App\Entity\User;

class SqlLiteUserRepository implements UserRepository {

  private $sqlite;

  function __construct($filepath) {
    $this->sqlite = new \SQLite3($filepath);
    $this->sqlite->exec(
      "CREATE TABLE IF NOT EXISTS USERS (
         ID VARCHAR(255) NOT NULL PRIMARY KEY,
         USERNAME VARCHAR(255) NOT NULL,
         ABOUT VARCHAR(255) NOT NULL,
         PASSWORD VARCHAR(255) NOT NULL
      )"
    );
  }

  function getAll() {
    return $this->usersFrom($this->sqlite->query("SELECT * FROM USERS"));
  }

  function getByUsername($username) {
    $select = $this->sqlite->prepare("SELECT * FROM USERS WHERE USERNAME = ?");
    $select->bindValue(1, $username);
    $users = $this->usersFrom($select->execute());
    return array_pop($users);
  }

  function getById($id) {
    $select = $this->sqlite->prepare("SELECT * FROM USERS WHERE ID = ?");
    $select->bindValue(1, $id);
    $users = $this->usersFrom($select->execute());
    return array_pop($users);
  }

  function existsById($id) {
    return !is_null($this->getById($id));
  }

  function store($user) {
    $newId = $this->generateUserId();
    $insert = $this->sqlite->prepare('INSERT INTO USERS(ID, USERNAME, ABOUT, PASSWORD) VALUES(?,?,?,?)');
    $insert->bindValue(1, $newId);
    $insert->bindValue(2, $user->getUsername());
    $insert->bindValue(3, $user->getAbout());
    $insert->bindValue(4, $user->getPassword());
    $insert->execute();
    return $newId;
  }

  private function usersFrom(\SQLite3Result $result) {
    $users = [];
    while ($row = $result->fetchArray()) {
      $users[] = User::build($row["ID"], $row["USERNAME"], $row["ABOUT"], $row["PASSWORD"]);
    }
    return $users;
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
