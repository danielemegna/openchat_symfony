<?php

namespace App\Tests\Repository;

use PHPUnit\Framework\TestCase;
use App\Repository\SqlLitePostRepository;
use App\Entity\Post;

class SqlLitePostRepositoryTest extends TestCase {

  private $repository;

  protected function setUp() {
    $this->repository = new SqlLitePostRepository("test.db");
    (new \SQLite3("test.db"))->exec("DELETE FROM POSTS");
  }

  function testEmptyRepository() {
    $actual = $this->repository->getByUserId("any");
    $this->assertEquals([], $actual);
  }

  function testStoreAndGetByUserId() {
    $firstShadyPostId = $this->repository->store(Post::newWithoutId("shady90Id", "First shady90 post.", new \DateTime()));
    $secondShadyPostId = $this->repository->store(Post::newWithoutId("shady90Id", "Another shady90 post.", new \DateTime()));
    $firstMariaPostId = $this->repository->store(Post::newWithoutId("maria89Id", "Maria first post.", new \DateTime()));
    $this->assertIsAValidUUID($firstShadyPostId);
    $this->assertIsAValidUUID($firstMariaPostId);

    $actual = $this->repository->getByUserId("shady90Id");
    $this->assertEquals(2, sizeof($actual));
    $this->assertEquals($firstShadyPostId, $actual[0]->getId());
    $this->assertEquals("shady90Id", $actual[0]->getUserId());
    $this->assertEquals("First shady90 post.", $actual[0]->getText());
    $this->assertNotNull($actual[0]->getDateTime());
    $this->assertEquals($secondShadyPostId, $actual[1]->getId());
    $this->assertEquals("shady90Id", $actual[1]->getUserId());
    $this->assertEquals("Another shady90 post.", $actual[1]->getText());
    $this->assertNotNull($actual[1]->getDateTime());

    $actual = $this->repository->getByUserId("maria89Id");
    $this->assertEquals(1, sizeof($actual));
    $this->assertEquals($firstMariaPostId, $actual[0]->getId());
    $this->assertEquals("maria89Id", $actual[0]->getUserId());
    $this->assertEquals("Maria first post.", $actual[0]->getText());
    $this->assertNotNull($actual[0]->getDateTime());

    $actual = $this->repository->getByUserId("notPresent");
    $this->assertEquals([], $actual);
  }

  private function assertIsAValidUUID(string $string) {
    $this->assertRegExp('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $string);
  }

}
