<?php

namespace App\Entity;

class Post {

  private $id;
  private $userId;
  private $text;
  private $dateTime;

  public function __construct(string $userId, string $text) {
    $this->id = $this->gen_uuid();
    $this->userId = $userId;
    $this->text = $text;
    $this->dateTime = new \DateTime();
  }

  public function getId(): string { return $this->id; }
  public function getUserId(): string { return $this->userId; }
  public function getText(): string { return $this->text; }
  public function getDateTime(): \DateTime  { return $this->dateTime; }

  private function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
      // 32 bits for "time_low"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

      // 16 bits for "time_mid"
      mt_rand( 0, 0xffff ),

      // 16 bits for "time_hi_and_version",
      // four most significant bits holds version number 4
      mt_rand( 0, 0x0fff ) | 0x4000,

      // 16 bits, 8 bits for "clk_seq_hi_res",
      // 8 bits for "clk_seq_low",
      // two most significant bits holds zero and one for variant DCE1.1
      mt_rand( 0, 0x3fff ) | 0x8000,

      // 48 bits for "node"
      mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
  }
}
