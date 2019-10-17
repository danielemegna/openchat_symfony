<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersWallApiE2ETest extends BaseE2E {

  private $UNEXISTING_USER_ID = "c41e0a83-08ff-444a-98d3-270d1fa2bdae";

  function testUnexisingUserWallAttempt() {
    $this->client->request('GET', "/users/$this->UNEXISTING_USER_ID/wall");

    $response = $this->client->getResponse();
    $this->assertStatusCode(400, $response);
    $this->assertEquals("text/plain; charset=UTF-8", $response->headers->get("content-type"));
    $this->assertEquals("User not found.", $response->getContent());
  }

}
