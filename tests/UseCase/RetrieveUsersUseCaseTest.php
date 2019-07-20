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
    $firstStoredUser = User::newWithoutId("username", "about", "pass");
    $secondStoredUser = User::newWithoutId("username", "about", "pass");
    $firstUserId = $this->userRepository->store($firstStoredUser);
    $secondUserId = $this->userRepository->store($secondStoredUser);

    $actual = $this->usecase->run();

    $expected = [
      User::build($firstUserId, "username", "about", "pass"),
      User::build($secondUserId, "username", "about", "pass")
    ];

    $this->assertEquals($expected, $actual);
  }
}
