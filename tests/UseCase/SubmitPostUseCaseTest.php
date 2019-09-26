<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\SubmitPostUseCase;
use App\UseCase\UnexistingUserError;
use App\UseCase\InappropriateLanguageError;
use App\Entity\User;
use App\Entity\Post;
use App\Tests\Repository\InMemoryUserRepository;

class SubmitPostUseCaseTest extends TestCase {

  private $userRepository;
  private $usecase;
  private $storedUserId;

  protected function setUp() {
    $this->userRepository = new InMemoryUserRepository();
    $this->usecase = new SubmitPostUseCase($this->userRepository);
    $this->storedUserId = $this->userRepository->store(User::newWithoutId("username", "about", "pass"));
  }

  public function testReturnsUnexistingUserErrorWithUnexistingUserId() {
    $publishedPost = $this->usecase->run("unexistingUserId", "Post text.");
    $this->assertInstanceOf(UnexistingUserError::class, $publishedPost);
  }

  public function testReturnsInappropriateLanguageErrorWithInappropriatePostTexts() {
    $publishedPost = $this->usecase->run($this->storedUserId, "I do not like elephants.");
    $this->assertInstanceOf(InappropriateLanguageError::class, $publishedPost);
    $publishedPost = $this->usecase->run($this->storedUserId, "I hate orange juice.");
    $this->assertInstanceOf(InappropriateLanguageError::class, $publishedPost);
    $publishedPost = $this->usecase->run($this->storedUserId, "I would like an ice cream.");
    $this->assertInstanceOf(InappropriateLanguageError::class, $publishedPost);
    $publishedPost = $this->usecase->run($this->storedUserId, "ORANGE IS A BAD FRUIT!");
    $this->assertInstanceOf(InappropriateLanguageError::class, $publishedPost);
  }

  public function testReturnsPublishedPost() {
    $publishedPost = $this->usecase->run($this->storedUserId, "Post text.");

    $this->assertIsAValidUUID($publishedPost->getId());
    $this->assertEquals($this->storedUserId, $publishedPost->getUserId());
    $this->assertEquals("Post text.", $publishedPost->getText());
    $this->assertInstanceOf(\DateTime::class, $publishedPost->getDateTime());
  }

  private function assertIsAValidUUID(string $string) {
    $this->assertRegExp('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $string);
  }
}
