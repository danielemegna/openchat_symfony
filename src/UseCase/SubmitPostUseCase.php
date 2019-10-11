<?php

namespace App\UseCase;

use App\Entity\Post;
use App\Repository\UserRepository;

class SubmitPostUseCase {

  private $userRepository;

  function __construct(UserRepository $userRepository) {
    $this->userRepository = $userRepository;
  }

  function run(Post $post) {
    if(!$this->userExists($post))
      return new UnexistingUserError($post);
    if($this->hasInappropriateLanguage($post))
      return new InappropriateLanguageError($post);

    return Post::build(
      $this->gen_uuid(),
      $post->getUserId(),
      $post->getText(),
      new \DateTime()
    );
  }

  private function userExists($post) {
    return !is_null($this->userRepository->getById($post->getUserId()));
  }

  private function hasInappropriateLanguage($post) {
    $inappropriateWords = ['elephants', 'orange', 'ice cream'];

    foreach($inappropriateWords as $inappropriateWord) {
      if(strpos(strtolower($post->getText()), $inappropriateWord) !== false)
        return true;
    }

    return false;
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
  public function __construct($post) {
    parent::newWithoutIdAndDate($post->getUserId(), $post->getText());
  }
}

final class InappropriateLanguageError extends Post {
  public function __construct($post) {
    parent::newWithoutIdAndDate($post->getUserId(), $post->getText());
  }
}
