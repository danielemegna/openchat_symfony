<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersWallApiE2ETest extends BaseE2E {

  private $UNEXISTING_USER_ID = "c41e0a83-08ff-444a-98d3-270d1fa2bdae";

  function testUnexisingUserWallAttempt() {
    $this->client->request('GET', "/users/$this->UNEXISTING_USER_ID/wall");

    $response = $this->client->getResponse();
    $this->assertResponse($response, 400, "text/plain; charset=UTF-8");
    $this->assertEquals("User not found.", $response->getContent());
  }

}
