<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\GetWallUseCase;
use App\UseCase\UnexistingUserError;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Following;
use App\Tests\Repository\InMemoryUserRepository;
use App\Tests\Repository\InMemoryPostRepository;
use App\Tests\Repository\InMemoryFollowingRepository;

class GetWallUseCaseTest extends TestCase {

  private $postRepository;
  private $userRepository;
  private $usecase;
  private $storedUserId;

  protected function setUp() {
    $this->postRepository = new InMemoryPostRepository();
    $this->userRepository = new InMemoryUserRepository();
    $this->followingRepository = new InMemoryFollowingRepository();
    $this->usecase = new GetWallUseCase($this->userRepository, $this->postRepository, $this->followingRepository);

    $this->storedUserId = $this->userRepository->store(User::newWithoutId("shady90", "about", "pass"));
  }

  public function testReturnsUnexistingUserErrorWithUnexistingUserId() {
    $wallPosts = $this->usecase->run("unexistingUserId");
    $this->assertInstanceOf(UnexistingUserError::class, $wallPosts);
  }

  public function testEmptyWall() {
    $wallPosts = $this->usecase->run($this->storedUserId);
    $this->assertEquals([], $wallPosts);
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

    $wallPosts = $this->usecase->run($this->storedUserId);

    $expected = [
      Post::build($secondPostId, $this->storedUserId, "Second user post.", $secondPostDateTime),
      Post::build($firstPostId, $this->storedUserId, "First user post.", $firstPostDateTime)
    ];
    $this->assertEquals($expected, $wallPosts);
  }

}
