<?php

namespace App\UseCase;

use App\Repository\UserRepository;
use App\Repository\FollowingRepository;

class RetrieveFolloweesUseCase {

  private $userRepository;
  private $followingRepository;

  function __construct(UserRepository $userRepository, FollowingRepository $followingRepository) {
    $this->userRepository = $userRepository;
    $this->followingRepository = $followingRepository;
  }

  function run($followerId) {
    $followings = $this->followingRepository->getByFollowerId($followerId);
    return array_map(function($f) {
      return $this->userRepository->getById($f->followeeId);
    }, $followings);
  }

}
