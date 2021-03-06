<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class BaseE2E extends WebTestCase {

  protected $client;

  protected function setUp() {
    $this->client = static::createClient();
    $this->cleanApplication();
  }

  protected function registerUser($username, $about, $password) {
    $response = $this->postAsJson(UsersApiE2ETest::ENDPOINT, [
      'username' => $username,
      'password' => $password,
      'about' => $about
    ]);

    $this->assertResponse($response, 200, "application/json");
    $responseBody = json_decode($response->getContent(), true);
    $this->assertIsAValidUUID($responseBody["id"]);
    $this->assertEquals($username, $responseBody["username"]);
    $this->assertEquals($about, $responseBody["about"]);

    return $responseBody["id"];
  }

  protected function submitPost($userId, $text) {
    $response = $this->postAsJson("/users/$userId/timeline", [ "text" => $text ]);

    $this->assertResponse($response, 201, "application/json");
    $responseBody = json_decode($response->getContent(), true);
    $this->assertIsAValidUUID($responseBody["postId"]);
    $this->assertEquals($userId, $responseBody["userId"]);
    $this->assertEquals($text, $responseBody["text"]);
    $this->assertIsAValidISO8601DateTime($responseBody["dateTime"]);

    return $responseBody;
  }

  protected function createFollowing($followerId, $followeeId) {
    $response = $this->postAsJson(FollowingsApiE2ETest::ENDPOINT, [
      "followerId" => $followerId,
      "followeeId" => $followeeId
    ]);
    $this->assertResponse($response, 201, "text/plain; charset=UTF-8");
    $this->assertEquals("Following created.", $response->getContent());
  }

  protected function assertResponse($response, $statusCode, $contentType) {
    $this->assertEquals($statusCode, $response->getStatusCode(), $this->getErrorStackTrace());
    $this->assertEquals($contentType, $response->headers->get("content-type"));
  }

  protected function assertIsAValidUUID(string $value) {
    $this->assertRegExp(
      '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
      $value, "Provided UUID do not match expected format."
    );
  }

  protected function assertIsAValidISO8601DateTime(string $value) {
    $this->assertRegExp(
      '/^((19|20)[0-9][0-9])[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])[T]([01][0-9]|[2][0-3])[:]([0-5][0-9])[:]([0-5][0-9])Z$/',
      $value, "Provided datetime do not match expected format."
    );
  }

  protected function assertArrayContainsExactlyInAnyOrder($expected, $actual) {
    $this->assertEquals($expected, $actual, "\$canonicalize = true", 0.0, 10, true);
  }

  protected function postAsJson($url, $data) {
    $this->client->request('POST', $url, [], [],
        ['CONTENT_TYPE' => 'application/json'],
        json_encode($data)
    );
    return $this->client->getResponse();
  }

  private function getErrorStackTrace() {
    $block = $this->client->getCrawler()->filter('pre.stacktrace');
    if ($block->count())
      return $block->text();

    return "Cannot find stacktrace";
  }

  protected function cleanApplication() {
    // i know ... a bit rude
    @unlink('sql.db');
  }

}
