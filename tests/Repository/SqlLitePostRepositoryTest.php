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
    $this->asserIsAValidPostId($firstShadyPostId);
    $this->asserIsAValidPostId($firstMariaPostId);

    $actual = $this->repository->getByUserId("shady90Id");
    $this->assertEquals(2, sizeof($actual));
    $this->assertEquals($firstShadyPostId, $actual[0]->getId());
    $this->assertEquals("shady90Id", $actual[0]->getUserId());
    $this->assertEquals("First shady90 post.", $actual[0]->getText());
    $this->assertNotNull($actual[0]->getPublishDateTime());
    $this->assertEquals($secondShadyPostId, $actual[1]->getId());
    $this->assertEquals("shady90Id", $actual[1]->getUserId());
    $this->assertEquals("Another shady90 post.", $actual[1]->getText());
    $this->assertNotNull($actual[1]->getPublishDateTime());

    $actual = $this->repository->getByUserId("maria89Id");
    $this->assertEquals(1, sizeof($actual));
    $this->assertEquals($firstMariaPostId, $actual[0]->getId());
    $this->assertEquals("maria89Id", $actual[0]->getUserId());
    $this->assertEquals("Maria first post.", $actual[0]->getText());
    $this->assertNotNull($actual[0]->getPublishDateTime());

    $actual = $this->repository->getByUserId("notPresent");
    $this->assertEquals([], $actual);
  }

  function testStorePostPublishDateTimeMilliseconds() {
    $postDateTime = new \DateTime("27-12-2019 11:54:39.456789");
    
    $this->repository->store(Post::newWithoutId("userId", "Post text.", $postDateTime));
    $posts = $this->repository->getByUserId("userId");

    $this->assertEquals($postDateTime, $posts[0]->getPublishDateTime());
    $this->assertEquals('456789', $posts[0]->getPublishDateTime()->format('u'));
  }

  private function asserIsAValidPostId(string $value) {
    $this->assertRegExp(
      '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
      $value, "Provided id do not match expected format."
    );
  }

}
