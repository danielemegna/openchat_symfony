<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase {

  private $client;

  protected function setUp() {
    $this->client = static::createClient();
  }

  public function testScenario() {
    $this->assertEquals([], $this->retrieveUsers());

    $shadyId = $this->registerUser([
      'username' => 'shady90',
      'password' => 'very$ecure',
      'about' => 'About shady90 here.',
    ]);
    $mariaId = $this->registerUser([
      'username' => 'maria89',
      'password' => 'yeah$ecure',
      'about' => 'About maria89 here.',
    ]);

    $actual = $this->retrieveUsers();
    $expected = [
      [
        'id' => $shadyId,
        'username' => 'shady90',
        'about' => 'About shady90 here.',
      ],
      [
        'id' => $mariaId,
        'username' => 'maria89',
        'about' => 'About maria89 here.',
      ]
    ];
    $this->assertArrayContainsExactlyInAnyOrder($expected, $actual);
  }

  private function retrieveUsers() {
    $this->client->request('GET', '/users');

    $response = $this->client->getResponse();
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    return json_decode($response->getContent(), true);
  }

  private function registerUser($user) {
    $this->postAsJson($user);

    $response = $this->client->getResponse();
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $responseBody = json_decode($response->getContent(), true);
    $this->assertTrue(array_key_exists("id", $responseBody));
    $this->assertEquals($user["username"], $responseBody["username"]);
    $this->assertEquals($user["about"], $responseBody["about"]);

    return $responseBody["id"];
  }

  private function postAsJson($data) {
    $this->client->request('POST', '/users', [], [],
        ['CONTENT_TYPE' => 'application/json'],
        json_encode($data)
    );
  }

  private function assertArrayContainsExactlyInAnyOrder($expected, $actual) {
    $this->assertEquals($expected, $actual, "\$canonicalize = true", 0.0, 10, true);
  }

}
