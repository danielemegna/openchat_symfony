<?php

namespace App\UseCase;

use App\Entity\User;
use App\Repository\UserRepository;

class LoginUserUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  function run($username, $password) {
    $user = $this->userRepository->getByUsername($username);

    if($user === null || $user->password !== $password)
      return new InvalidCredentialsError($username, $password);

    return $user;
  }
}

final class InvalidCredentialsError extends User {
  function __construct(string $username, string $password) {
    parent::__construct(['username' => $username, 'about' => 'InvalidCredentialsError', 'password' => $password]);
  }
}
