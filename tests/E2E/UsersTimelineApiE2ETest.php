<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersTimelineApiE2ETest extends BaseE2E {

  function testUnexisingUserPostSubmitAttempt() {
    $response = $this->postAsJson("/users/c41e0a83-08ff-444a-98d3-270d1fa2bdae/timeline", [
      "text" => "Any text here."
    ]);
    $this->assertEquals(400, $response->getStatusCode());
    $this->assertEquals("text/plain; charset=UTF-8", $response->headers->get("content-type"));
    $this->assertEquals("User not found.", $response->getContent());
  }

  function testInappropriateLanguagePostSubmitAttempt() {
    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');

    $response = $this->postAsJson("/users/$shadyId/timeline", [
      "text" => "I do not like elephants."
    ]);

    $this->assertEquals(400, $response->getStatusCode());
    $this->assertEquals("text/plain; charset=UTF-8", $response->headers->get("content-type"));
    $this->assertEquals("Post contains inappropriate language.", $response->getContent());
  }

  function testRegisterAndSubmitAPost() {
    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');

    $response = $this->postAsJson("/users/$shadyId/timeline", [
      "text" => "This is the first shady90 post."
    ]);

    $this->assertEquals(201, $response->getStatusCode(), $this->getErrorStackTrace());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $actual = json_decode($response->getContent(), true);
    $this->assertIsAValidUUID($actual["postId"]);
    $this->assertEquals($shadyId, $actual["userId"]);
    $this->assertEquals("This is the first shady90 post.", $actual["text"]);
    $this->assertTrue(\DateTime::createFromFormat(\DateTime::ISO8601, $actual["dateTime"]) !== false);
  }

}
