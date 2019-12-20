<?php

namespace App\UseCase;

use App\Repository\PostRepository;
use App\Repository\UserRepository;

class GetWallUseCase {

  private $userRepository;
  private $postRepository;

  function __construct(UserRepository $userRepository, PostRepository $postRepository) {
    $this->userRepository = $userRepository;
    $this->postRepository = $postRepository;
  }

  function run($userId) {
    if(!$this->userRepository->existsById($userId))
      return new UnexistingUserError($userId);

    $userPosts = $this->postRepository->getByUserId($userId);
    return $this->sortPostByDateTimeDesc($userPosts);
  }

  private function sortPostByDateTimeDesc(array $posts) {
    $result = $posts;
    usort($result, function($a, $b) {
      return $a->getPublishDateTime() <=> $b->getPublishDateTime();
    });
    return array_reverse($result);
  }

}
