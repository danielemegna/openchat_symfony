<?php

namespace App\Tests\E2E;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BaseE2E extends WebTestCase {

  protected $client;

  protected function setUp() {
    $this->client = static::createClient();
    $this->cleanApplication();
  }

  protected function assertArrayContainsExactlyInAnyOrder($expected, $actual) {
    $this->assertEquals($expected, $actual, "\$canonicalize = true", 0.0, 10, true);
  }

  protected function cleanApplication() {
    // i know ... a bit rude
    @unlink('sql.db');
  }

  protected function postAsJson($url, $data) {
    $this->client->request('POST', $url, [], [],
        ['CONTENT_TYPE' => 'application/json'],
        json_encode($data)
    );
  }
}
