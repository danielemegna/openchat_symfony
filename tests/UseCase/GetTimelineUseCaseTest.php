<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\GetTimelineUseCase;
use App\UseCase\UnexistingUserError;
use App\Entity\User;
use App\Entity\Post;
use App\Tests\Repository\InMemoryUserRepository;
use App\Tests\Repository\InMemoryPostRepository;

class GetTimelineUseCaseTest extends TestCase {

  private $postRepository;
  private $userRepository;
  private $usecase;

  protected function setUp() {
    $this->postRepository = new InMemoryPostRepository();
    $this->userRepository = new InMemoryUserRepository();
    $this->usecase = new GetTimelineUseCase($this->postRepository, $this->userRepository);
  }

  public function testReturnsUnexistingUserErrorWithUnexistingUserId() {
    $publishedPost = $this->usecase->run("unexistingUserId");
    $this->assertInstanceOf(UnexistingUserError::class, $publishedPost);
  }

}
