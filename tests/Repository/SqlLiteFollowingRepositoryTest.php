<?php

namespace App\Tests\Repository;

use PHPUnit\Framework\TestCase;
use App\Repository\SqlLiteFollowingRepository;
use App\Entity\Following;

class SqlLiteFollowingRepositoryTest extends TestCase {

  private $repository;
  private $sqlite;

  protected function setUp() {
    $this->sqlite = new \SQLite3("test.db");
    $this->sqlite->exec("DELETE FROM FOLLOWINGS");
    $this->repository = new SqlLiteFollowingRepository("test.db");
  }

  function testGetOnEmptyRepository() {
    $actual = $this->repository->getByFollowerId("any");
    $this->assertEquals([], $actual);
  }

  function testGetByFollowerId() {
    $this->prepareRow("shady90Id", "sandroId");
    $this->prepareRow("shady90Id", "robertId");
    $this->prepareRow("maria88Id", "shady90Id");

    $actual = $this->repository->getByFollowerId("shady90Id");
    $expected = [
      new Following("shady90Id", "sandroId"),
      new Following("shady90Id", "robertId")
    ];
    $this->assertEquals($expected, $actual);

    $actual = $this->repository->getByFollowerId("maria88Id");
    $expected = [ new Following("maria88Id", "shady90Id") ];
    $this->assertEquals($expected, $actual);
    
    $actual = $this->repository->getByFollowerId("notPresent");
    $this->assertEquals([], $actual);
  }

  function testStoreFollowings() {
    $this->repository->store(new Following("shady90Id", "sandroId"));
    $this->repository->store(new Following("shady90Id", "robertId"));
    $this->repository->store(new Following("maria88Id", "shady90Id"));

    $rows = $this->getStoredRows();
    
    $expected = [
      [ "FOLLOWER_ID" => "shady90Id", "FOLLOWEE_ID" => "sandroId" ],
      [ "FOLLOWER_ID" => "shady90Id", "FOLLOWEE_ID" => "robertId" ],
      [ "FOLLOWER_ID" => "maria88Id", "FOLLOWEE_ID" => "shady90Id" ]
    ];
    $this->assertEquals($expected, $rows);
  }

  private function prepareRow(string $followerId, string $followeeId) {
    $insert = $this->sqlite->prepare('INSERT INTO FOLLOWINGS(FOLLOWER_ID, FOLLOWEE_ID) VALUES(?,?)');
    $insert->bindValue(1, $followerId);
    $insert->bindValue(2, $followeeId);
    $insert->execute();
  }

  private function getStoredRows() {
    $select = $this->sqlite->prepare("SELECT * FROM FOLLOWINGS");
    $result = $select->execute();
    $rows = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) { $rows[] = $row; }
    return $rows;
  }

}
