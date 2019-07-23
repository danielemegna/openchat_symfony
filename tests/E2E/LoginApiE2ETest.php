<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginApiE2ETest extends BaseE2E {

  const ENDPOINT = "/login";

  function testRegisterAndRetrieveScenario() {
    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');

    $response = $this->postAsJson(self::ENDPOINT, [
      'username' => 'shady90',
      'password' => 'wrongPassword'
    ]);

    $this->assertEquals(404, $response->getStatusCode());
    $this->assertEquals("text/plain; charset=UTF-8", $response->headers->get("content-type"));
    $this->assertEquals("Invalid credentials.", $response->getContent());

    $response = $this->postAsJson(self::ENDPOINT, [
      'username' => 'shady90',
      'password' => 'very$ecure'
    ]);

    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $responseBody = json_decode($response->getContent(), true);
    $this->assertEquals($shadyId, $responseBody["id"]);
    $this->assertEquals("shady90", $responseBody["username"]);
    $this->assertEquals("About shady90 here.", $responseBody["about"]);
  }

}
