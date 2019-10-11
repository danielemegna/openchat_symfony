<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersTimelineApiE2ETest extends BaseE2E {

  private $UNEXISTING_USER_ID = "c41e0a83-08ff-444a-98d3-270d1fa2bdae";

  function testUnexisingUserPostSubmitAttempt() {
    $response = $this->postAsJson("/users/$this->UNEXISTING_USER_ID/timeline", [
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

  function testSubmitPostAndGetTimeline() {
    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');

    $response = $this->postAsJson("/users/$shadyId/timeline", [
      "text" => "This is the first shady90 post."
    ]);

    $this->assertEquals(201, $response->getStatusCode(), $this->getErrorStackTrace());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $firstPublishedPost = json_decode($response->getContent(), true);
    $this->assertIsAValidUUID($firstPublishedPost["postId"]);
    $this->assertEquals($shadyId, $firstPublishedPost["userId"]);
    $this->assertEquals("This is the first shady90 post.", $firstPublishedPost["text"]);
    $this->assertIsAValidISO8601DateTime($firstPublishedPost["dateTime"]);

    $response = $this->postAsJson("/users/$shadyId/timeline", [
      "text" => "Second shady90 post here."
    ]);
    $secondPublishedPost = json_decode($response->getContent(), true);

    $this->client->request('GET', "/users/$shadyId/timeline");

    $response = $this->client->getResponse();
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $timelinePosts = json_decode($response->getContent(), true);

    return // TODO to be completed

    $expectedPosts = [[
        "postId" => $secondPublishedPost["postId"], "userId" => $shadyId,
        "text" => "Second shady90 post here", "dateTime" => $secondPublishedPost["postId"]
    ],[
        "postId" => $firstPublishedPost["postId"], "userId" => $shadyId,
        "text" => "This is the first shady90 post.", "dateTime" => $firstPublishedPost["postId"]
    ]];
    $this->assertEquals($expectedPosts, $timelinePosts);

    $this->client->request('GET', "/users/$this->UNEXISTING_USER_ID/timeline");
    $this->assertEquals([], $json_decode($this->client->getResponse()->getContent()));
  }

  private function assertIsAValidISO8601DateTime(string $value) {
    $this->assertTrue(\DateTime::createFromFormat(\DateTime::ISO8601, $value) !== false);
  }

}
