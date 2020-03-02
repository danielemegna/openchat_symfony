<?php

namespace App\UseCase\Error;

use App\Entity\Post;

final class InappropriateLanguageError extends Post {
  public function __construct($post) {
    parent::__construct(['userId' => $post->userId, 'text' => $post->text]);
  }
}
