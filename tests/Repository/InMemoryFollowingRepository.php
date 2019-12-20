<?php

namespace App\Tests\Repository;

use App\Repository\FollowingRepository;
use App\Entity\Following;

class InMemoryFollowingRepository implements FollowingRepository {

  private $followings = [];

  function getByFollowerId($followerId) {
    return array_filter($this->followings, function($f) use ($followerId) {
      return $f->getFollowerId() === $followerId;
    });
  }

  function store($toBeStored) {
    array_push($this->followings, $toBeStored);
  }

}
