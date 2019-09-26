<?php

namespace App\UseCase;

use App\Entity\Post;
use App\Repository\UserRepository;

class SubmitPostUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  function run(string $userId, string $text) {
    if(!$this->userExists($userId))
      return new UnexistingUserError($userId, $text);

    return Post::build($this->gen_uuid(), $userId, $text, new \DateTime());
  }

  private function userExists($userId) {
    return !is_null($this->userRepository->getById($userId));
  }

  private function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

      // 16 bits for "time_mid"
      mt_rand( 0, 0xffff ),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand( 0, 0x0fff ) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand( 0, 0x3fff ) | 0x8000,

      // 48 bits for "node"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }
}

final class UnexistingUserError extends Post {
  public function __construct(string $userId, string $text) {
    parent::newWithoutIdAndDate($userId, $text);
  }
}
