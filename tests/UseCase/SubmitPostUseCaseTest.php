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
    $storedUser = User::newWithoutId("username", "about", "pass");
    $storedUserId = $this->userRepository->store($storedUser);

    $publishedPost = $this->usecase->run($storedUserId, "Post text.");

    $this->assertIsAValidUUID($publishedPost->getId());
    $this->assertEquals($storedUserId, $publishedPost->getUserId());
    $this->assertEquals("Post text.", $publishedPost->getText());
    $this->assertInstanceOf(\DateTime::class, $publishedPost->getDateTime());
  }

  private function assertIsAValidUUID(string $string) {
    $this->assertRegExp('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $string);
  }
}
