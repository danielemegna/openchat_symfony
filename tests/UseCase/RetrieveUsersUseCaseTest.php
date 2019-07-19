<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\RetrieveUsersUseCase;
use App\Entity\User;
use App\Repository\InMemoryUserRepository;

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
    $firstStoredUser = User::build("id", "username", "about", "pass");
    $secondStoredUser = User::build("id", "username", "about", "pass");
    $this->userRepository->store($firstStoredUser);
    $this->userRepository->store($secondStoredUser);

    $actual = $this->usecase->run();

    $this->assertEquals([$firstStoredUser, $secondStoredUser], $actual);
  }
}
