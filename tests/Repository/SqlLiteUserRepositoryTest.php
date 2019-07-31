<?php

namespace App\Tests\Repository;

use PHPUnit\Framework\TestCase;
use App\Repository\SqlLiteUserRepository;
use App\Entity\User;

class SqlLiteUserRepositoryTest extends TestCase {

  private $repository;

  protected function setUp() {
    $this->repository = new SqlLiteUserRepository("test.db");
    (new \SQLite3("test.db"))->exec("DELETE FROM USERS");
  }

  function testEmptyRepository() {
    $actual = $this->repository->getAll();
    $this->assertEquals([], $actual);
  }

  function testStoreAndGet() {
    $shadyId = $this->repository->store(User::newWithoutId("shady90", "shady90 about.", "securePassword"));
    $this->assertTrue(!is_null($shadyId) && !empty($shadyId));
    $mariaId = $this->repository->store(User::newWithoutId("maria89", "maria89 about.", "secureAgain"));
    $this->assertTrue(!is_null($mariaId) && !empty($mariaId));

    $users = $this->repository->getAll();

    $this->assertEquals(2, sizeof($users));
    $expected = [
      User::build($shadyId, "shady90", "shady90 about.", "securePassword"),
      User::build($mariaId, "maria89", "maria89 about.", "secureAgain")
    ];
    $this->assertEquals($expected, $users);

    $foundUser = $this->repository->getByUsername("notPresent");
    $this->assertNull($foundUser);

    $foundUser = $this->repository->getByUsername("maria89");
    $expected = User::build($mariaId, "maria89", "maria89 about.", "secureAgain");
    $this->assertEquals($expected, $foundUser);

    $foundUser = $this->repository->getById("notPresent");
    $this->assertNull($foundUser);

    $foundUser = $this->repository->getById($shadyId);
    $expected = User::build($shadyId, "shady90", "shady90 about.", "securePassword");
    $this->assertEquals($expected, $foundUser);
  }

}
