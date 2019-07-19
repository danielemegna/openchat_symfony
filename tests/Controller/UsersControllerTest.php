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
    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');
    $mariaId = $this->registerUser('maria89','About maria89 here.', 'yeah$ecure');

    $actual = $this->retrieveUsers();
    $expected = [
      ['id' => $shadyId, 'username' => 'shady90', 'about' => 'About shady90 here.'],
      ['id' => $mariaId, 'username' => 'maria89', 'about' => 'About maria89 here.']
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

  private function registerUser($username, $about, $password) {
    $this->postAsJson([
      'username' => $username,
      'password' => $password,
      'about' => $about
    ]);

    $response = $this->client->getResponse();
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $responseBody = json_decode($response->getContent(), true);
    $this->assertTrue(array_key_exists("id", $responseBody));
    $this->assertEquals($username, $responseBody["username"]);
    $this->assertEquals($about, $responseBody["about"]);

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
