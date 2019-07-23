<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersApiE2ETest extends BaseE2E {

  private const ENDPOINT = "/users";

  public function testRegisterAndRetrieveScenario() {
    $users = $this->retrieveUsers();
    $this->assertEquals([], $users);

    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');
    $mariaId = $this->registerUser('maria89','About maria89 here.', 'yeah$ecure');

    $users = $this->retrieveUsers();
    $expected = [
      ['id' => $shadyId, 'username' => 'shady90', 'about' => 'About shady90 here.'],
      ['id' => $mariaId, 'username' => 'maria89', 'about' => 'About maria89 here.']
    ];
    $this->assertArrayContainsExactlyInAnyOrder($expected, $users);
  }

  private function retrieveUsers() {
    $this->client->request('GET',self::ENDPOINT);

    $response = $this->client->getResponse();
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    return json_decode($response->getContent(), true);
  }

  private function registerUser($username, $about, $password) {
    $this->postAsJson(self::ENDPOINT, [
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

}
