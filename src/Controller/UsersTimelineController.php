<?php

namespace App\Controller;

use App\UseCase\CreateFollowingUseCase;
use App\UseCase\RetrieveFolloweesUseCase;
use App\UseCase\SubmitPostUseCase;
use App\Entity\UnexistingUserError;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersTimelineController extends Controller {

  /**
   * @Route("/users/{userId}/timeline", methods={"POST"})
   */
  public function submitPost(string $userId, Request $request, SubmitPostUseCase $submitPostUseCase) {
    $postText = json_decode($request->getContent())->text;

    $publishedPost = $submitPostUseCase->run($userId, $postText);

    if($publishedPost instanceof UnexistingUserError)
      return new Response("User not found.", 400, ["Content-Type" => "text/plain"]);

    if(strpos($publishedPost->getText(), 'elephants') !== false)
      return new Response("Post contains inappropriate language.", 400, ["Content-Type" => "text/plain"]);

    $responseBody = [
      "postId" => $publishedPost->getId(),
      "userId" => $publishedPost->getUserId(),
      "text" => $publishedPost->getText(),
      "dateTime" => $publishedPost->getDateTime()->format(\DateTime::ISO8601)
    ];
    return $this->json($responseBody, 201);
  }

}
