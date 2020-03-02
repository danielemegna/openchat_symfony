<?php

namespace App\UseCase;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\FollowingRepository;

class GetWallUseCase {

  private $userRepository;
  private $postRepository;
  private $followingRepository;

  function __construct(UserRepository $userRepository, PostRepository $postRepository, FollowingRepository $followingRepository) {
    $this->userRepository = $userRepository;
    $this->postRepository = $postRepository;
    $this->followingRepository = $followingRepository;
  }

  function run($userId) {
    if(!$this->userRepository->existsById($userId))
      return new UnexistingUserError($userId);

    $wallPosts = $this->postRepository->getByUserId($userId);

    $followings = $this->followingRepository->getByFollowerId($userId);
    foreach($followings as $following) {
      $followeeId = $following->followeeId;
      $followeePosts = $this->postRepository->getByUserId($followeeId);
      $wallPosts = array_merge($wallPosts, $followeePosts);
    }

    return $this->sortPostByDateTimeDesc($wallPosts);
  }

  private function sortPostByDateTimeDesc(array $posts) {
    $result = $posts;
    usort($result, function($a, $b) {
      return $a->publishDateTime <=> $b->publishDateTime;
    });
    return array_reverse($result);
  }

}
