<?php

namespace App\Entity;

final class UnexistingUserError extends Post {

  public function __construct(string $userId, string $text) {
    parent::__construct($userId, $text);
  }

}
