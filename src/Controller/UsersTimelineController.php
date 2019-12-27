<?php

namespace App\Controller;

use App\Entity\Post;
use App\UseCase\SubmitPostUseCase;
use App\UseCase\UnexistingUserError;
use App\UseCase\InappropriateLanguageError;
use App\UseCase\GetTimelineUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersTimelineController extends AbstractController {

  /**
   * @Route("/users/{userId}/timeline", methods={"POST"})
   */
  public function submitPost(string $userId, Request $request, SubmitPostUseCase $submitPostUseCase) {
    $postText = json_decode($request->getContent())->text;
    $postToSubmit = Post::newWithoutIdAndDate($userId, $postText);

    $publishedPost = $submitPostUseCase->run($postToSubmit);

    if($publishedPost instanceof UnexistingUserError)
      return new Response("User not found.", 400, ["Content-Type" => "text/plain"]);
    if($publishedPost instanceof InappropriateLanguageError)
      return new Response("Post contains inappropriate language.", 400, ["Content-Type" => "text/plain"]);

    $responseBody = [
      "postId" => $publishedPost->getId(),
      "userId" => $publishedPost->getUserId(),
      "text" => $publishedPost->getText(),
      "dateTime" => $publishedPost->getPublishDateTime()->format('Y-m-d\TH:i:s\Z')
    ];
    return $this->json($responseBody, 201);
  }

  /**
   * @Route("/users/{userId}/timeline", methods={"GET"})
   */
  public function getUserTimeline(string $userId, GetTimelineUseCase $getTimelineUseCase) {
    $userPosts = $getTimelineUseCase->run($userId);

    if($userPosts instanceof UnexistingUserError)
      return new Response("User not found.", 400, ["Content-Type" => "text/plain"]);

    $responseBody = array_map(function($p) {
      return [
        "postId" => $p->getId(),
        "userId" => $p->getUserId(),
        "text" => $p->getText(),
        "dateTime" => $p->getPublishDateTime()->format('Y-m-d\TH:i:s\Z')
      ];
    }, $userPosts);
    return $this->json($responseBody, 200);
  }

}
