<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseE2E extends WebTestCase {

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

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $responseBody = json_decode($response->getContent(), true);
    $this->assertTrue(array_key_exists("id", $responseBody));
    $this->assertEquals($username, $responseBody["username"]);
    $this->assertEquals($about, $responseBody["about"]);

    return $responseBody["id"];
  }

  protected function assertArrayContainsExactlyInAnyOrder($expected, $actual) {
    $this->assertEquals($expected, $actual, "\$canonicalize = true", 0.0, 10, true);
  }

  protected function cleanApplication() {
    // i know ... a bit rude
    @unlink('sql.db');
  }

  protected function postAsJson($url, $data) {
    $this->client->request('POST', $url, [], [],
        ['CONTENT_TYPE' => 'application/json'],
        json_encode($data)
    );
    return $this->client->getResponse();
  }
}
