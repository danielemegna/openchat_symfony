<?php

namespace App\UseCase;

use App\Repository\UserRepository;

class RetrieveUsersUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  public function run() {
    return $this->userRepository->getAll();
  }
}
