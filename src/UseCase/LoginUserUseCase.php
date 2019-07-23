<?php

namespace App\UseCase;

use App\Entity\User;
use App\Entity\InvalidCredentials;
use App\Repository\UserRepository;

class LoginUserUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  function run($username, $password) {
    $user = $this->userRepository->getByUsername($username);

    if($user === null)
      return new InvalidCredentials($username, $password);
    if($user->getPassword() !== $password)
      return new InvalidCredentials($username, $password);

    return $user;
  }
}
