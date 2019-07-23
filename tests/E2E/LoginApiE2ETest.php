<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginApiE2ETest extends BaseE2E {

  const ENDPOINT = "/login";

  function testRegisterAndRetrieveScenario() {
    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');

    $response = $this->postAsJson(self::ENDPOINT, [
      'username' => 'notPresent',
      'password' => 'very$ecure'
    ]);
    $this->assertEquals(404, $response->getStatusCode());
    $this->assertEquals("text/plain; charset=UTF-8", $response->headers->get("content-type"));
    $this->assertEquals("Invalid credentials.", $response->getContent());
  }

}
