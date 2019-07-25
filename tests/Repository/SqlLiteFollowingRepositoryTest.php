<?php

namespace App\Tests\Repository;

use PHPUnit\Framework\TestCase;
use App\Repository\SqlLiteFollowingRepository;
use App\Entity\Following;

class SqlLiteFollowingRepositoryTest extends TestCase {

  private $repository;

  protected function setUp() {
    $this->repository = new SqlLiteFollowingRepository("test.db");
    (new \SQLite3("test.db"))->exec("DELETE FROM FOLLOWINGS");
  }

  function testEmptyRepository() {
    $actual = $this->repository->getByFollowerId("any");
    $this->assertEquals([], $actual);
  }

  function testStoreAndGetByFollowerId() {
    $this->repository->store(new Following("shady90Id", "sandroId"));
    $this->repository->store(new Following("shady90Id", "robertId"));
    $this->repository->store(new Following("maria88Id", "shady90Id"));

    $actual = $this->repository->getByFollowerId("shady90Id");
    $expected = [
      new Following("shady90Id", "sandroId"),
      new Following("shady90Id", "robertId")
    ];
    $this->assertEquals($expected, $actual);

    $actual = $this->repository->getByFollowerId("notPresent");
    $this->assertEquals([], $actual);

    $actual = $this->repository->getByFollowerId("maria88Id");
    $expected = [ new Following("maria88Id", "shady90Id") ];
    $this->assertEquals($expected, $actual);
  }

}
