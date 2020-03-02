<?php

namespace App\UseCase;

use App\Entity\Post;
use App\Repository\UserRepository;
use App\Repository\PostRepository;
use App\UseCase\Error\UnexistingUserError;
use App\UseCase\Error\InappropriateLanguageError;

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

    $postToBeStored = $post->with(['publishDateTime' => new \DateTime()]);
    $storedId = $this->postRepository->store($postToBeStored);
    return $postToBeStored->with(['id' => $storedId]);
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
