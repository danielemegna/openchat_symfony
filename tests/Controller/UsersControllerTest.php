<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase {

  public function testEmptyUserArray() {
    $client = static::createClient();

    $client->request('GET', '/users');

    $response = $client->getResponse();
    $this->assertEquals(200, $response->getStatusCode());
    $this->assertEquals("application/json", $response->headers->get("content-type"));
    $this->assertEquals("[]", $response->getContent());
  }

}
