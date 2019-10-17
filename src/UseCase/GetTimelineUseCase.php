<?php

namespace App\UseCase;

use App\Repository\PostRepository;

class GetTimelineUseCase {

  private $postRepository;

  function __construct(PostRepository $postRepository) {
    $this->postRepository = $postRepository;
  }

  function run($userId) {
    $userPosts = $this->postRepository->getByUserId($userId);
    return $this->sortPostByDateTime($userPosts);
  }

  private function sortPostByDateTime(array $posts) {
    $result = $posts;
    usort($result, function($a, $b) {
      return $a->getDateTime() <=> $b->getDateTime();
    });
    return array_reverse($result);
  }

}
