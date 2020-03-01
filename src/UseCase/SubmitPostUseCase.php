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
    if(!$this->userRepository->existsById($post->userId))
      return new UnexistingUserError($post->userId);
    if($this->hasInappropriateLanguage($post))
      return new InappropriateLanguageError($post);

    $postToBeStored = Post::newWithoutId(
      $post->userId,
      $post->text,
      new \DateTime()
    );
    $storedId = $this->postRepository->store($postToBeStored);
    return Post::build(
      $storedId,
      $postToBeStored->userId,
      $postToBeStored->text,
      $postToBeStored->publishDateTime
    );
  }

  private function hasInappropriateLanguage($post) {
    $inappropriateWords = ['elephant', 'orange', 'ice cream'];

    foreach($inappropriateWords as $inappropriateWord) {
      if(strpos(strtolower($post->text), $inappropriateWord) !== false)
        return true;
    }

    return false;
  }
}

final class UnexistingUserError {
  private $userId;
  public function __construct($userId) {
    $this->userId = $userId;
  }
}

final class InappropriateLanguageError extends Post {
  public function __construct($post) {
    parent::__construct(['userId' => $post->userId, 'text' => $post->text]);
  }
}
