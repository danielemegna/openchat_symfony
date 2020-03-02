<?php

namespace App\UseCase\Error;

final class UnexistingUserError {
  private $userId;
  public function __construct($userId) {
    $this->userId = $userId;
  }
}
