<?php

namespace App\UseCase\Error;

use App\Entity\User;

final class InvalidCredentialsError extends User {
  function __construct(string $username, string $password) {
    parent::__construct(['username' => $username, 'about' => 'InvalidCredentialsError', 'password' => $password]);
  }
}
