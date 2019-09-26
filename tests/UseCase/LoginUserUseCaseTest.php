<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\LoginUserUseCase;
use App\UseCase\InvalidCredentials;
use App\Entity\User;
use App\Tests\Repository\InMemoryUserRepository;

class LoginUserUseCaseTest extends TestCase {

  private $userRepository;
  private $usecase;
  private $storedUserId;

  protected function setUp() {
    $this->userRepository = new InMemoryUserRepository();
    $this->usecase = new LoginUserUseCase($this->userRepository);
    $this->storedUserId = $this->userRepository->store(
      User::newWithoutId("shady90", "About shady90.", "veryS3cure")
    );
  }

  public function testReturnLoggedUserOnValidCredentials() {
    $loggedUser = $this->usecase->run("shady90", "veryS3cure");

    $expected = User::build($this->storedUserId, "shady90", "About shady90.", "veryS3cure");
    $this->assertEquals($expected, $loggedUser);
  }

  public function testProduceInvalidCredentialsOnWrongCredentials() {
    $loggedUser = $this->usecase->run("invalid", "credentials");
    $this->assertInstanceOf(InvalidCredentials::class, $loggedUser);
    $loggedUser = $this->usecase->run("shady11", "veryS3cure");
    $this->assertInstanceOf(InvalidCredentials::class, $loggedUser);
    $loggedUser = $this->usecase->run("shady90", "wrongPassword");
    $this->assertInstanceOf(InvalidCredentials::class, $loggedUser);
  }

}
