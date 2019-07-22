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

  private function usersFrom(\SQLite3Result $result) {
    $users = [];
    while ($row = $result->fetchArray()) {
      $users[] = User::build($row["ID"], $row["USERNAME"], $row["ABOUT"], $row["PASSWORD"]);
    }
    return $users;
  }

  function getByUsername($username) {
    $select = $this->sqlite->prepare("SELECT * FROM USERS WHERE USERNAME = ?");
    $select->bindValue(1, $username);
    $users = $this->usersFrom($select->execute());
    return array_pop($users);
  }

  function store($user) {
    $newId = uniqid();
    $insert = $this->sqlite->prepare('INSERT INTO USERS(ID, USERNAME, ABOUT, PASSWORD) VALUES(?,?,?,?)');
    $insert->bindValue(1, $newId);
    $insert->bindValue(2, $user->getUsername());
    $insert->bindValue(3, $user->getAbout());
    $insert->bindValue(4, $user->getPassword());
    $insert->execute();
    return $newId;
  }

}
