<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\GetTimelineUseCase;
use App\Entity\User;
use App\Entity\Post;
use App\Tests\Repository\InMemoryUserRepository;
use App\Tests\Repository\InMemoryPostRepository;
use App\UseCase\Error\UnexistingUserError;

class GetTimelineUseCaseTest extends TestCase {

  private $postRepository;
  private $userRepository;
  private $usecase;
  private $storedUserId;

  protected function setUp() {
    $this->postRepository = new InMemoryPostRepository();
    $this->userRepository = new InMemoryUserRepository();
    $this->usecase = new GetTimelineUseCase($this->postRepository, $this->userRepository);

    $this->storedUserId = $this->userRepository->store(User::newWithoutId("shady90", "about", "pass"));
    $anotherUserId = $this->userRepository->store(User::newWithoutId("anotherUser", "about", "pass"));
    $this->postRepository->store(Post::newWithoutId($anotherUserId, "Another user post.", new \DateTime()));
  }

  public function testReturnsUnexistingUserErrorWithUnexistingUserId() {
    $timelinePosts = $this->usecase->run("unexistingUserId");
    $this->assertInstanceOf(UnexistingUserError::class, $timelinePosts);
  }

  public function testUserWithoutPosts() {
    $timelinePosts = $this->usecase->run($this->storedUserId);
    $this->assertEquals([], $timelinePosts);
  }

  public function testReturnsUserPostsSortedByDateDesc() {
    $firstPostDateTime = new \DateTime();
    $firstPostId = $this->postRepository->store(Post::newWithoutId(
      $this->storedUserId, "First user post.", $firstPostDateTime
    ));
    $secondPostDateTime = date_modify(clone $firstPostDateTime, "+1 hour");
    $secondPostId = $this->postRepository->store(Post::newWithoutId(
      $this->storedUserId, "Second user post.", $secondPostDateTime
    ));

    $timelinePosts = $this->usecase->run($this->storedUserId);

    $expected = [
      Post::build($secondPostId, $this->storedUserId, "Second user post.", $secondPostDateTime),
      Post::build($firstPostId, $this->storedUserId, "First user post.", $firstPostDateTime)
    ];
    $this->assertEquals($expected, $timelinePosts);
  }

}
