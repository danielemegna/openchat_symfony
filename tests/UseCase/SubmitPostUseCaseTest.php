<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\SubmitPostUseCase;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\UnexistingUserError;
use App\Tests\Repository\InMemoryUserRepository;

class SubmitPostUseCaseTest extends TestCase {

  private $userRepository;
  private $usecase;

  protected function setUp() {
    $this->userRepository = new InMemoryUserRepository();
    $this->usecase = new SubmitPostUseCase($this->userRepository);
  }

  public function testReturnsUnexistingUserErrorWithUnexistingUserId() {
    $publishedPost = $this->usecase->run("unexistingUserId", "Post text.");
    $this->assertInstanceOf(UnexistingUserError::class, $publishedPost);
  }

  public function testReturnsPublishedPost() {
    $this->markTestSkipped();
    $storedUser = User::newWithoutId("username1", "about1", "pass1");
    $storedUserId = $this->userRepository->store($storedUser);

    $publishedPost = $this->usecase->run($storedUserId, "Post text.");

    $expected = new Post($storedUserId, "Post text.");
    $this->assertEquals($expected, $publishedPost);
  }
}
