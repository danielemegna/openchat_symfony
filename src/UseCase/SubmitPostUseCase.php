<?php

namespace App\UseCase;

use App\Entity\Post;
use App\Repository\UserRepository;
use App\Repository\PostRepository;

class SubmitPostUseCase {

  private $userRepository;
  private $postRepository;

  function __construct(UserRepository $userRepository, PostRepository $postRepository) {
    $this->userRepository = $userRepository;
    $this->postRepository = $postRepository;
  }

  function run(Post $post) {
    if(!$this->userExists($post))
      return new UnexistingUserError($post);
    if($this->hasInappropriateLanguage($post))
      return new InappropriateLanguageError($post);

    $postToBeStored = Post::newWithoutId(
      $post->getUserId(),
      $post->getText(),
      new \DateTime()
    );
    $storedId = $this->postRepository->store($postToBeStored);
    return Post::build(
      $storedId,
      $postToBeStored->getUserId(),
      $postToBeStored->getText(),
      $postToBeStored->getDateTime()
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
