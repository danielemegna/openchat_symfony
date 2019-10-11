<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\SubmitPostUseCase;
use App\UseCase\UnexistingUserError;
use App\UseCase\InappropriateLanguageError;
use App\Entity\User;
use App\Entity\Post;
use App\Tests\Repository\InMemoryUserRepository;
use App\Repository\PostRepository;

class SubmitPostUseCaseTest extends TestCase {

  private $userRepository;
  private $postRepository;
  private $usecase;
  private $storedUserId;

  protected function setUp() {
    $this->userRepository = new InMemoryUserRepository();
    $this->postRepository = $this->createMock(PostRepository::class);
    $this->postRepository->method('store')->willReturn('7c74136e-edc8-4c6e-ad0b-f94b0770e18c');
    $this->usecase = new SubmitPostUseCase($this->userRepository, $this->postRepository);
    $this->storedUserId = $this->userRepository->store(User::newWithoutId("username", "about", "pass"));
  }

  public function testReturnsUnexistingUserErrorWithUnexistingUserId() {
    $publishedPost = $this->runUseCaseWith("unexistingUserId", "Post text.");
    $this->assertInstanceOf(UnexistingUserError::class, $publishedPost);
  }

  public function testReturnsInappropriateLanguageErrorWithInappropriatePostTexts() {
    $publishedPost = $this->runUseCaseWith($this->storedUserId, "I do not like elephants.");
    $this->assertInstanceOf(InappropriateLanguageError::class, $publishedPost);
    $publishedPost = $this->runUseCaseWith($this->storedUserId, "I hate orange juice.");
    $this->assertInstanceOf(InappropriateLanguageError::class, $publishedPost);
    $publishedPost = $this->runUseCaseWith($this->storedUserId, "I would like an ice cream.");
    $this->assertInstanceOf(InappropriateLanguageError::class, $publishedPost);
    $publishedPost = $this->runUseCaseWith($this->storedUserId, "ORANGE IS A BAD FRUIT!");
    $this->assertInstanceOf(InappropriateLanguageError::class, $publishedPost);
  }

  public function testReturnsPublishedPost() {
    $publishedPost = $this->runUseCaseWith($this->storedUserId, "Post text.");

    $this->assertEquals('7c74136e-edc8-4c6e-ad0b-f94b0770e18c', $publishedPost->getId());
    $this->assertEquals($this->storedUserId, $publishedPost->getUserId());
    $this->assertEquals("Post text.", $publishedPost->getText());
    $this->assertInstanceOf(\DateTime::class, $publishedPost->getDateTime());
  }

  public function testStoresPostUsingPostRepository() {
    $this->postRepository
      ->expects($this->once())->method('store')
      ->with($this->callback(function ($post) {
        $this->assertNull($post->getId());
        $this->assertEquals($this->storedUserId, $post->getUserId());
        $this->assertEquals("Post text.", $post->getText());
        $this->assertNotNull($post->getDateTime());
        return true;
      }));

    $this->runUseCaseWith($this->storedUserId, "Post text.");
  }

  private function runUseCaseWith($userId, $postText) {
    $postToSubmit = Post::newWithoutIdAndDate($userId, $postText);
    return $this->usecase->run($postToSubmit);
  }

  private function assertIsAValidUUID(string $string) {
    $this->assertRegExp('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $string);
  }
}
