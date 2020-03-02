<?php

namespace App\Repository;

use App\Entity\Following;

class SqlLiteFollowingRepository implements FollowingRepository {

  private $sqlite;

  function __construct($filepath) {
    $this->sqlite = new \SQLite3($filepath);
    $this->sqlite->exec(
      "CREATE TABLE IF NOT EXISTS FOLLOWINGS (
         FOLLOWER_ID VARCHAR(255) NOT NULL,
         FOLLOWEE_ID VARCHAR(255) NOT NULL
      )"
    );
  }


  function getByFollowerId($followerId) {
    $select = $this->sqlite->prepare("SELECT * FROM FOLLOWINGS WHERE FOLLOWER_ID = ?");
    $select->bindValue(1, $followerId);
    return $this->followingsFrom($select->execute());
  }

  function store($following) {
    $insert = $this->sqlite->prepare('INSERT INTO FOLLOWINGS(FOLLOWER_ID, FOLLOWEE_ID) VALUES(?,?)');
    $insert->bindValue(1, $following->followerId);
    $insert->bindValue(2, $following->followeeId);
    $insert->execute();
  }

  private function followingsFrom(\SQLite3Result $result) {
    $followings = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
      $followings[] = new Following($row["FOLLOWER_ID"], $row["FOLLOWEE_ID"]);
    }
    return $followings;
  }

}
