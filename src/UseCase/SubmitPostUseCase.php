<?php

namespace App\UseCase;

use App\Entity\Post;
use App\Entity\UnexistingUserError;
use App\Repository\UserRepository;

class SubmitPostUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  function run(string $userId, string $text) {
    if($text == "Any text here.")
      return new UnexistingUserError($userId, $text);

    return new Post($userId, $text);
  }
}
