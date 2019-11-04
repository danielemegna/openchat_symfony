<?php

namespace App\Entity;

class Post {

  private $id;
  private $userId;
  private $text;
  private $publishDatetime;

  public static function newWithoutId(string $userId, string $text, \DateTime $publishDatetime) {
    return new Post(null, $userId, $text, $publishDatetime);
  }

  public static function newWithoutIdAndDate(string $userId, string $text) {
    return new Post(null, $userId, $text, null);
  }

  public static function build(string $id, string $userId, string $text, \DateTime $publishDatetime) {
    return new Post($id, $userId, $text, $publishDatetime);
  }

  private function __construct(?string $id, string $userId, string $text, ?\DateTime $publishDatetime) {
    $this->id = $id;
    $this->userId = $userId;
    $this->text = $text;
    $this->publishDatetime = $publishDatetime;
  }

  public function getId(): ?string { return $this->id; }
  public function getUserId(): string { return $this->userId; }
  public function getText(): string { return $this->text; }
  public function getPublishDateTime(): ?\DateTime  { return $this->publishDatetime; }

}
