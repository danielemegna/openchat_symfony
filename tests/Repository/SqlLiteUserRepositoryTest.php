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

  public function testEmptyRepository() {
    $actual = $this->repository->getAll();
    $this->assertEquals([], $actual);
  }

  public function testStoreAndGetAll() {
    $storedId = $this->repository->store(User::newWithoutId("username", "about", "passwd"));
    $this->assertNotNull($storedId);
    $this->assertNotEmpty($storedId);

    $users = $this->repository->getAll();
    $this->assertEquals(1, sizeof($users), var_export($users, true));
    $this->assertEquals("username", $users[0]->getUsername());
  }

}
