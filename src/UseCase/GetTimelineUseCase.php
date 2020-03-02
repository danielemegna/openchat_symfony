<?php

namespace App\UseCase;

use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\UseCase\Error\UnexistingUserError;

class GetTimelineUseCase {

  private $postRepository;
  private $userRepository;

  function __construct(PostRepository $postRepository, UserRepository $userRepository) {
    $this->postRepository = $postRepository;
    $this->userRepository = $userRepository;
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
      return $a->publishDateTime <=> $b->publishDateTime;
    });
    return array_reverse($result);
  }

}
