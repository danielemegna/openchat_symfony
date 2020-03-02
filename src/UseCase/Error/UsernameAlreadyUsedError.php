<?php

namespace App\UseCase\Error;

use App\Entity\User;

final class UsernameAlreadyUsedError extends User {
  function __construct(string $username) {
    return new parent(['username' => $username, 'about' => 'UsernameAlreadyUsedError', 'password' => 'InvalidCredentialsError']);
  }
}
