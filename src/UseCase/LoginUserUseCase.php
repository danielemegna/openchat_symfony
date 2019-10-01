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

    if($user === null || $user->getPassword() !== $password)
      return new InvalidCredentialsError($username, $password);

    return $user;
  }
}

final class InvalidCredentialsError extends User {
  function __construct(string $username, string $password) {
    $this->username = $username;
    $this->password = $password;
  }
  public function getId() { return null; }
  public function getAbout() { return null; }
}
