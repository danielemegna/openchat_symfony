<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\UseCase\UnexistingUserError;
use App\UseCase\GetWallUseCase;

class UsersWallController extends AbstractController {

  /**
   * @Route("/users/{userId}/wall", methods={"GET"})
   */
  public function getUserWall(string $userId, GetWallUseCase $getWallUseCase) {
    $wallPosts = $getWallUseCase->run($userId);

    if($wallPosts instanceof UnexistingUserError)
      return new Response("User not found.", 400, ["Content-Type" => "text/plain"]);

    return $this->json($this->serializePosts($wallPosts), 200);
  }

  private function serializePosts(array $posts) {
    return array_map(function($p) {
      return [
        "postId" => $p->getId(),
        "userId" => $p->getUserId(),
        "text" => $p->getText(),
        "dateTime" => $p->getPublishDateTime()->format('Y-m-d\TH:i:s\Z')
      ];
    }, $posts);
  }

}
