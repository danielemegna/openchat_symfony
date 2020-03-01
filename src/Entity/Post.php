<?php

namespace App\Entity;

use Precious\Precious;

class Post extends Precious {

  public static function newWithoutId(string $userId, string $text, \DateTime $publishDateTime) {
    return new self(['userId' => $userId, 'text' => $text, 'publishDateTime' => $publishDateTime]);
  }

  public static function newWithoutIdAndDate(string $userId, string $text) {
    return new self(['userId' => $userId, 'text' => $text]);
  }

  public static function build(string $id, string $userId, string $text, \DateTime $publishDateTime) {
    return new self(['id' => $id, 'userId' => $userId, 'text' => $text, 'publishDateTime' => $publishDateTime]);
  }

  protected function init() : array
  {
    return [
      self::optional('id', self::stringType()),
      self::required('userId', self::stringType()),
      self::required('text', self::stringType()),
      self::optional('publishDateTime', self::instanceOf(\DateTime::class))
    ];
  }

}
