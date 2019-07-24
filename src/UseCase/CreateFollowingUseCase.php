<?php

namespace App\UseCase;

use App\Repository\UserRepository;
use App\Repository\FollowingRepository;

class CreateFollowingUseCase {

  private $userRepository;
  private $followingRepository;

  function __construct(UserRepository $userRepository, FollowingRepository $followingRepository) {
    $this->userRepository = $userRepository;
    $this->followingRepository = $followingRepository;
  }

  function run($followerId, $followeeId) {
  }

}

