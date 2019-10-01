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
    if($this->userRepository->getByUsername($user->getUsername()) !== null) {
      return new UsernameAlreadyUsedError($user->getUsername());
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

final class UsernameAlreadyUsedError extends User {
  function __construct(string $username) {
    $this->username = $username;
  }
  public function getId() { return null; }
  public function getAbout() { return null; }
  public function getPassword() { return null; }
}
