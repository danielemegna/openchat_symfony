<?php

namespace App\UseCase;

use App\Entity\User;
use App\Repository\UserRepository;

class RegisterUserUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  public function run($user) {
    $storedId = $this->userRepository->store($user);
    return User::build(
      $storedId,
      $user->getUsername(),
      $user->getAbout(),
      $user->getPassword()
    );
  }
}
