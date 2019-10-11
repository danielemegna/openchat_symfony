<?php

namespace App\Entity;

class Post {

  private $id;
  private $userId;
  private $text;
  private $dateTime;

  public static function newWithoutId(string $userId, string $text, \DateTime $dateTime) {
    return new Post(null, $userId, $text, $dateTime);
  }

  public static function newWithoutIdAndDate(string $userId, string $text) {
    return new Post(null, $userId, $text, null);
  }

  public static function build(string $id, string $userId, string $text, \DateTime $dateTime) {
    return new Post($id, $userId, $text, $dateTime);
  }

  private function __construct(?string $id, string $userId, string $text, ?\DateTime $dateTime) {
    $this->id = $id;
    $this->userId = $userId;
    $this->text = $text;
    $this->dateTime = $dateTime;
  }

  public function getId(): ?string { return $this->id; }
  public function getUserId(): string { return $this->userId; }
  public function getText(): string { return $this->text; }
  public function getDateTime(): ?\DateTime  { return $this->dateTime; }

}
