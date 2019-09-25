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
    if(!$this->userExists($userId))
      return new UnexistingUserError($userId, $text);

    return new Post($userId, $text);
  }

  private function userExists($userId) {
    return !is_null($this->userRepository->getById($userId));
  }
}
