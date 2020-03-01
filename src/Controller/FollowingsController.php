<?php

namespace App\Controller;

use App\UseCase\CreateFollowingUseCase;
use App\UseCase\RetrieveFolloweesUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowingsController extends AbstractController {

  /**
   * @Route("/followings", methods={"POST"})
   */
  public function createFollowing(Request $request, CreateFollowingUseCase $usecase) {
    $json = json_decode($request->getContent());

    $usecase->run($json->followerId, $json->followeeId);

    return new Response("Following created.", 201, ["Content-Type" => "text/plain"]);
  }

  /**
   * @Route("/followings/{followerId}/followees", methods={"GET"})
   */
  public function retrieveFollowees(string $followerId, RetrieveFolloweesUseCase $usecase) {
    $followees = $usecase->run($followerId);

    $responseBody = array_map(function($u) {
      return [
        'id' => $u->id,
        'username' => $u->username,
        'about' => $u->about
      ];
    }, $followees);
    return $this->json($responseBody);
  }

}
