<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\LoginUserUseCase;
use App\Entity\User;
use App\Entity\InvalidCredentials;
use App\Tests\Repository\InMemoryUserRepository;

class LoginUserUseCaseTest extends TestCase {

  private $userRepository;
  private $usecase;

  protected function setUp() {
    $this->userRepository = new InMemoryUserRepository();
    $this->usecase = new LoginUserUseCase($this->userRepository);
  }

  public function testReturnLoggedUserOnValidCredentials() {
    $storedUser = User::newWithoutId("shady90", "About shady90.", "veryS3cure");
    $firstUserId = $this->userRepository->store($storedUser);

    $loggedUser = $this->usecase->run("shady90", "veryS3cure");

    $expected = User::build($firstUserId, "shady90", "About shady90.", "veryS3cure");
    $this->assertEquals($expected, $loggedUser);
  }

  public function testProduceInvalidCredentialsOnWrongCredentials() {
    $loggedUser = $this->usecase->run("invalid", "credentials");

    $this->assertInstanceOf(InvalidCredentials::class, $loggedUser);
  }

}
