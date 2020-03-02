<?php

namespace App\Tests\Repository;

use App\Repository\PostRepository;
use App\Entity\Post;

class InMemoryPostRepository implements PostRepository {

  private $posts = [];

  function getByUserId($userId) {
    return array_filter($this->posts, function($p) use ($userId) {
      return $p->userId === $userId;
    });
  }

  function store($post) {
    $toBeStored = $post->with(['id' => $this->generatePostId()]);
    array_push($this->posts, $toBeStored);
    return $toBeStored->id;
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
