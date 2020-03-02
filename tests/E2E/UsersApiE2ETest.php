<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersApiE2ETest extends BaseE2E {

  const ENDPOINT = "/users";

  function testRegisterAndRetrieveScenario() {
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

  function testRegisterTwiceUserProduceAnError() {
    $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');

    $response = $this->postAsJson(self::ENDPOINT, [
      'username' => 'shady90',
      'password' => 'any',
      'about' => 'any'
    ]);

    $this->assertResponse($response, 400, "text/plain; charset=UTF-8");
    $this->assertEquals("Username already in use.", $response->getContent());
  }

  private function retrieveUsers() {
    $this->client->request('GET', self::ENDPOINT);

    $response = $this->client->getResponse();
    $this->assertResponse($response, 200, "application/json");
    return json_decode($response->getContent(), true);
  }

}
