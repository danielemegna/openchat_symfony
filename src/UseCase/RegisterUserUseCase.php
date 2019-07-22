<?php

namespace App\UseCase;

use App\Entity\User;
use App\Entity\UsernameAlreadyUsed;
use App\Repository\UserRepository;

class RegisterUserUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  function run($user) {
    if($this->userRepository->getByUsername($user->getUsername()) !== null) {
      return new UsernameAlreadyUsed($user->getUsername());
    }

    $storedId = $this->userRepository->store($user);
    return User::build(
      $storedId,
      $user->getUsername(),
      $user->getAbout(),
      $user->getPassword()
    );
  }
}
