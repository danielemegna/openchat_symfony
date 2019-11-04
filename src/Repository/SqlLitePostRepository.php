<?php

namespace App\Repository;

use App\Entity\Post;

class SqlLitePostRepository implements PostRepository {

  private $sqlite;

  function __construct($filepath) {
    $this->sqlite = new \SQLite3($filepath);
    $this->sqlite->exec(
      "CREATE TABLE IF NOT EXISTS POSTS (
         ID VARCHAR(255) NOT NULL PRIMARY KEY,
         USER_ID VARCHAR(255) NOT NULL,
         TEXT VARCHAR(255) NOT NULL,
         PUBLISH_DATETIME TEXT NOT NULL
      )"
    );
  }

  function getByUserId($userId) {
    $select = $this->sqlite->prepare("SELECT * FROM POSTS WHERE USER_ID = ?");
    $select->bindValue(1, $userId);
    return $this->postsFrom($select->execute());
  }

  function store($post) {
    $newId = $this->gen_uuid();
    $insert = $this->sqlite->prepare('INSERT INTO POSTS(ID, USER_ID, TEXT, PUBLISH_DATETIME) VALUES(?,?,?,?)');
    $insert->bindValue(1, $newId);
    $insert->bindValue(2, $post->getUserId());
    $insert->bindValue(3, $post->getText());
    $insert->bindValue(4, $post->getPublishDateTime()->format(\DateTime::ISO8601));
    $insert->execute();
    return $newId;
  }

  private function postsFrom(\SQLite3Result $result) {
    $posts = [];
    while ($row = $result->fetchArray()) {
      $publishDatetime = \DateTime::createFromFormat(\DateTime::ISO8601, $row["PUBLISH_DATETIME"]);
      $posts[] = Post::build($row["ID"], $row["USER_ID"], $row["TEXT"], $publishDatetime);
    }
    return $posts;
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
