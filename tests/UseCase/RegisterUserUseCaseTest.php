<?php

namespace App\Tests\UseCase;

use PHPUnit\Framework\TestCase;
use App\UseCase\RegisterUserUseCase;
use App\UseCase\UsernameAlreadyUsed;
use App\Entity\User;
use App\Tests\Repository\InMemoryUserRepository;

class RegisterUserUseCaseTest extends TestCase {

  private $userRepository;
  private $usecase;

  protected function setUp() {
    $this->userRepository = new InMemoryUserRepository();
    $this->usecase = new RegisterUserUseCase($this->userRepository);
  }

  public function testRegisterUser() {
    $user = User::newWithoutId("username", "about", "pass");

    $registeredUser = $this->usecase->run($user);

    $this->assertInstanceOf(User::class, $registeredUser);
    $this->assertEquals("username", $registeredUser->getUsername());
    $this->assertEquals("about", $registeredUser->getAbout());
    $this->assertEquals("pass", $registeredUser->getPassword());
    $this->assertNotNull($registeredUser->getId());
    $this->assertNotEmpty($registeredUser->getId());
  }

  public function testProduceUsernameAlreadyUsed() {
    $registeredUser = $this->usecase->run(User::newWithoutId("username", "about", "pass"));
    $registeredUser = $this->usecase->run(User::newWithoutId("username", "different", "password"));

    $this->assertInstanceOf(UsernameAlreadyUsed::class, $registeredUser);
  }

}
