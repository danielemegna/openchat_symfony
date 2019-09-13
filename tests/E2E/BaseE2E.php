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

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $responseBody = json_decode($response->getContent(), true);
    $this->assertTrue(array_key_exists("id", $responseBody));
    $this->assertEquals($username, $responseBody["username"]);
    $this->assertEquals($about, $responseBody["about"]);

    $registeredUserId = $responseBody["id"];
    $this->assertIsAValidUUID($registeredUserId);
    return $registeredUserId;
  }

  protected function assertIsAValidUUID($string) {
    $this->assertRegExp('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $string);
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

  protected function cleanApplication() {
    // i know ... a bit rude
    @unlink('sql.db');
  }

}
