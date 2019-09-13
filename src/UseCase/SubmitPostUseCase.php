<?php

namespace App\UseCase;

use App\Entity\Post;
use App\Repository\UserRepository;

class SubmitPostUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  function run(string $userId, string $text) {

    if($text == "This is the shady90 post.") {
      return new Post($userId, $text);
    }

    throw new \RuntimeException();

  }
}
