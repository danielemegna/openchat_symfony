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

    $this->client->request('GET', "/users/$shadyId/wall");
    $response = $this->client->getResponse();
    $this->assertResponse($response, 200, "application/json");
    $this->assertEquals([], json_decode($response->getContent(), true));

    $response = $this->postAsJson("/users/$shadyId/timeline", [
      "text" => "This is the first shady90 post."
    ]);
    $firstShadyPost = json_decode($response->getContent(), true);

    $this->client->request('GET', "/users/$shadyId/wall");
    $response = $this->client->getResponse();
    $this->assertResponse($response, 200, "application/json");
    $wallPosts = json_decode($response->getContent(), true);
    $expectedPosts = [[
        "postId" => $firstShadyPost["postId"], "userId" => $shadyId,
        "text" => "This is the first shady90 post.", "dateTime" => $firstShadyPost["dateTime"]
    ]];
    $this->assertEquals($expectedPosts, $wallPosts);
  }

}
