<?php

namespace App\UseCase;

use App\Entity\User;
use App\Repository\UserRepository;

class RegisterUserUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  function run($user) {
    if($this->userRepository->getByUsername($user->username) !== null) {
      return new UsernameAlreadyUsedError($user->username);
    }

    $storedId = $this->userRepository->store($user);
    return $user->with(['id' => $storedId]);
  }
}

final class UsernameAlreadyUsedError extends User {
  function __construct(string $username) {
    return new parent(['username' => $username, 'about' => 'UsernameAlreadyUsedError', 'password' => 'InvalidCredentialsError']);
  }
}
