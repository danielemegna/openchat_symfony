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

    if($user === null)
      return new InvalidCredentials($username, $password);
    if($user->getPassword() !== $password)
      return new InvalidCredentials($username, $password);

    return $user;
  }
}

final class InvalidCredentials extends User {
  function __construct(string $username, string $password) {
    $this->username = $username;
    $this->password = $password;
  }
  public function getId() { return null; }
  public function getAbout() { return null; }
}
