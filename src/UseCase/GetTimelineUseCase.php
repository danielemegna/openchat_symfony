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
    usort($userPosts, function($a, $b) {
      return $a->getDateTime() <=> $b->getDateTime();
    });
    return array_reverse($userPosts);
  }

}
