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
    if($username !== "shady90")
      return new InvalidCredentials($username, $password);

    return User::build(
      uniqid(),
      $username,
      "About shady90 here.",
      "not important"
    );
  }
}
