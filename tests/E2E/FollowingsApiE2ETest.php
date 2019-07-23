<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FollowingsApiE2ETest extends BaseE2E {

  const ENDPOINT = "/followings";

  function testFollowAndGetFollowedUsers() {
    $shadyId = $this->registerUser('shady90', 'About shady90 here.', 'very$ecure');
    $mariaId = $this->registerUser('maria89', 'About maria89 here.', 'very$ecure');
    $sandroId = $this->registerUser('sandro', 'About sandro here.', 'very$ecure');

    $response = $this->postAsJson(self::ENDPOINT, [
      "followerId" => $mariaId,
      "followeeId" => $shadyId
    ]);

    $this->assertEquals(201, $response->getStatusCode());
    $this->assertEquals("text/plain; charset=UTF-8", $response->headers->get("content-type"));
    $this->assertEquals("Following created.", $response->getContent());

    // TODO create another following
    // TODO get followed users
  }

}
