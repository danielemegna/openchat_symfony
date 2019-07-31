<?php

namespace App\UseCase;

use App\Repository\UserRepository;
use App\Repository\FollowingRepository;
use App\Entity\Following;

class CreateFollowingUseCase {

  private $userRepository;
  private $followingRepository;

  function __construct(UserRepository $userRepository, FollowingRepository $followingRepository) {
    $this->userRepository = $userRepository;
    $this->followingRepository = $followingRepository;
  }

  function run($followerId, $followeeId) {
    $this->followingRepository->store(new Following($followerId, $followeeId));
  }

}

