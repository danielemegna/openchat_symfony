<?php

namespace App\Repository;

interface FollowingRepository {
  function getByFollowerId($followerId);
  function store($following);
}
