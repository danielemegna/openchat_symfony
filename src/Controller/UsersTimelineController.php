<?php

namespace App\Controller;

use App\UseCase\CreateFollowingUseCase;
use App\UseCase\RetrieveFolloweesUseCase;
use App\UseCase\SubmitPostUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersTimelineController extends Controller {

  /**
   * @Route("/users/{userId}/timeline", methods={"POST"})
   */
  public function submitPost(string $userId, Request $request, SubmitPostUseCase $usecase) {
    $requestBody = json_decode($request->getContent(), true);
    $postText = $requestBody["text"];

    try {
      $post = $usecase->run($userId, $postText);
      $responseBody = [
        "postId" => $post->getId(),
        "userId" => $post->getUserId(),
        "text" => $post->getText(),
        "dateTime" => ""
      ];
      return $this->json($responseBody, 201);
    } catch (\RuntimeException $e) {
      return new Response("User not found.", 400, ["Content-Type" => "text/plain"]);
    }
  }

}
