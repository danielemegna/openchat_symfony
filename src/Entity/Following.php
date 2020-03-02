<?php

namespace App\Entity;

use Precious\Precious;

class Following extends Precious {

  function __construct(string $followerId, string $followeeId) {
    parent::__construct(['followerId' => $followerId, 'followeeId' => $followeeId]);
  }

  protected function init() : array
  {
    return [
      self::required('followerId', self::stringType()),
      self::required('followeeId', self::stringType()),
    ];
  }
}
