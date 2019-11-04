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
    $this->assertResponse($response, 201, "text/plain; charset=UTF-8");
    $this->assertEquals("Following created.", $response->getContent());

    $this->postAsJson(self::ENDPOINT, [
      "followerId" => $shadyId,
      "followeeId" => $sandroId
    ]);
    $this->postAsJson(self::ENDPOINT, [
      "followerId" => $shadyId,
      "followeeId" => $mariaId
    ]);

    $this->client->request('GET', self::ENDPOINT.'/'.$shadyId.'/followees');
    $response = $this->client->getResponse();
    $this->assertResponse($response, 200, "application/json");
    $actual = json_decode($response->getContent(), true);
    $expected = [
      ['id' => $sandroId, 'username' => 'sandro', 'about' => 'About sandro here.'],
      ['id' => $mariaId, 'username' => 'maria89', 'about' => 'About maria89 here.']
    ];
    $this->assertArrayContainsExactlyInAnyOrder($expected, $actual);

    $this->client->request('GET', self::ENDPOINT.'/'.$sandroId.'/followees');
    $this->assertEquals([], json_decode($this->client->getResponse()->getContent(), true));
  }

}
