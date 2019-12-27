<?php

namespace App\Repository;

use App\Entity\Post;

class SqlLitePostRepository implements PostRepository {

  private const DATETIME_STORE_FORMAT = 'Y-m-d\TH:i:s.u';

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
    $newId = $this->generatePostId();
    $insert = $this->sqlite->prepare('INSERT INTO POSTS(ID, USER_ID, TEXT, PUBLISH_DATETIME) VALUES(?,?,?,?)');
    $insert->bindValue(1, $newId);
    $insert->bindValue(2, $post->getUserId());
    $insert->bindValue(3, $post->getText());
    $insert->bindValue(4, $post->getPublishDateTime()->format(self::DATETIME_STORE_FORMAT));
    $insert->execute();
    return $newId;
  }

  private function postsFrom(\SQLite3Result $result) {
    $posts = [];
    while ($row = $result->fetchArray()) {
      $publishDatetime = \DateTime::createFromFormat(self::DATETIME_STORE_FORMAT, $row["PUBLISH_DATETIME"]);
      $posts[] = Post::build($row["ID"], $row["USER_ID"], $row["TEXT"], $publishDatetime);
    }
    return $posts;
  }

  private function generatePostId() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
      mt_rand( 0, 0xffff ),
      mt_rand( 0, 0x0fff ) | 0x4000,
      mt_rand( 0, 0x3fff ) | 0x8000,
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }

}
