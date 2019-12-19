<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersWallApiE2ETest extends BaseE2E {

  private $UNEXISTING_USER_ID = "c41e0a83-08ff-444a-98d3-270d1fa2bdae";

  function testUnexisingUserWallAttempt() {
    $this->client->request('GET', "/users/$this->UNEXISTING_USER_ID/wall");

    $response = $this->client->getResponse();
    $this->assertResponse($response, 400, "text/plain; charset=UTF-8");
    $this->assertEquals("User not found.", $response->getContent());
  }

  function testWallForExistingUser() {
    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');

    $wallPosts = $this->getUserWall($shadyId);
    $this->assertEquals([], $wallPosts);

    $firstShadyPost = $this->submitPost($shadyId, "This is the first shady90 post.");
    $secondShadyPost = $this->submitPost($shadyId, "This is the second shady90 post.");

    $wallPosts = $this->getUserWall($shadyId);
    $expectedPosts = [[
        "postId" => $secondShadyPost["postId"], "userId" => $shadyId,
        "text" => "This is the second shady90 post.", "dateTime" => $secondShadyPost["dateTime"]
    ],[
        "postId" => $firstShadyPost["postId"], "userId" => $shadyId,
        "text" => "This is the first shady90 post.", "dateTime" => $firstShadyPost["dateTime"]
    ]];
    $this->assertEquals($expectedPosts, $wallPosts);
  }

  private function getUserWall($userId) {
    $this->client->request('GET', "/users/$userId/wall");
    $response = $this->client->getResponse();
    $this->assertResponse($response, 200, "application/json");
    return json_decode($response->getContent(), true);
  }

}
