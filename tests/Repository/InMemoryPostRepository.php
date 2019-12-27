<?php

namespace App\Tests\Repository;

use App\Repository\PostRepository;
use App\Entity\Post;

class InMemoryPostRepository implements PostRepository {

  private $posts = [];

  function getByUserId($userId) {
    return array_filter($this->posts, function($p) use ($userId) {
      return $p->getUserId() === $userId;
    });
  }

  function store($post) {
    $toBeStored = Post::build(
      $this->generatePostId(),
      $post->getUserId(),
      $post->getText(),
      $post->getPublishDateTime()
    );
    array_push($this->posts, $toBeStored);
    return $toBeStored->getId();
  }

  private function generatePostId() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
      mt_rand( 0, 0xffff ),
      mt_rand( 0, 0x0fff ) | 0x4000,
      mt_rand( 0, 0x3fff ) | 0x8000,
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }
}
