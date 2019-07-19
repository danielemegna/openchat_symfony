<?php

namespace App\UseCase;

class RetrieveUsersUseCase {

  private $userRepository;

  function __construct($userRepository) {
    $this->userRepository = $userRepository;
  }

  public function run() {
    return $this->userRepository->getAll();
  }
}
