<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersTimelineApiE2ETest extends BaseE2E {

  function testWithoutRegisteredUser() {
    $response = $this->postAsJson("/users/c41e0a83-08ff-444a-98d3-270d1fa2bdae/timeline", [
      "text" => "Any text here."
    ]);
    $this->assertEquals(400, $response->getStatusCode());
    $this->assertEquals("text/plain; charset=UTF-8", $response->headers->get("content-type"));
    $this->assertEquals("User not found.", $response->getContent());
  }

  function testFollowAndGetFollowedUsers() {
    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');

    $response = $this->postAsJson("/users/$shadyId/timeline", [
      "text" => "This is the shady90 post."
    ]);

    $this->assertEquals(201, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $actual = json_decode($response->getContent(), true);
    $this->assertIsAValidUUID($actual["postId"]);
    $this->assertEquals($shadyId, $actual["userId"]);
    $this->assertEquals("This is the shady90 post.", $actual["text"]);
    $this->assertTrue(array_key_exists("dateTime", $actual));
    //"dateTime": "2018-01-10T11:30:00Z"
  }

}
