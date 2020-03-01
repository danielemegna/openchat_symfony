<?php

namespace App\Entity;

use Precious\Precious;

class User extends Precious {

  public static function newWithoutId(string $username, string $about, string $password) {
    return new self(['username' => $username, 'about' => $about, 'password' => $password]);
  }

  public static function build(string $id, string $username, string $about, string $password) {
    return new self(['id' => $id, 'username' => $username, 'about' => $about, 'password' => $password]);
  }

  protected function init() : array
  {
    return [
      self::optional('id', self::stringType()),
      self::required('username', self::stringType()),
      self::required('about', self::stringType()),
      self::required('password', self::stringType()),
    ];
  }
}
