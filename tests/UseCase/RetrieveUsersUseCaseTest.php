<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\RetrieveUsersUseCase;
use App\Entity\User;
use App\Tests\Repository\InMemoryUserRepository;

class RetrieveUsersUseCaseTest extends TestCase {

  private $userRepository;
  private $usecase;

  protected function setUp() {
    $this->userRepository = new InMemoryUserRepository();
    $this->usecase = new RetrieveUsersUseCase($this->userRepository);
  }

  public function testReturnEmptyArrayWithoutUsers() {
    $actual = $this->usecase->run();

    $this->assertEquals([], $actual);
  }

  public function testReturnUsersStoredInRepository() {
    $firstStoredUser = User::newWithoutId("username1", "about1", "pass1");
    $secondStoredUser = User::newWithoutId("username2", "about2", "pass2");
    $firstUserId = $this->userRepository->store($firstStoredUser);
    $secondUserId = $this->userRepository->store($secondStoredUser);

    $actual = $this->usecase->run();

    $expected = [
      User::build($firstUserId, "username1", "about1", "pass1"),
      User::build($secondUserId, "username2", "about2", "pass2")
    ];

    $this->assertEquals($expected, $actual);
  }
}
