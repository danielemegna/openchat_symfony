<?php

namespace App\Entity;

class Following {

  private $followerId;
  private $followeeId;

  function __construct(string $followerId, string $followeeId) {
    $this->followerId = $followerId;
    $this->followeeId = $followeeId;
  }

  public function getFollowerId() { return $this->followerId; }
  public function getFolloweeId() { return $this->followeeId; }

}
