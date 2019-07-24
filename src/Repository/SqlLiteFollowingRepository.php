<?php

namespace App\Repository;

use App\Entity\Following;

class SqlLiteFollowingRepository implements FollowingRepository {

  private $sqlite;

  function __construct($filepath) {
  }


  function getByFollowerId($followerId) {
    return [];
  }

  function store($following) {
  }

}
