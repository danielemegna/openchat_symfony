<?php

namespace App\Controller;

use App\UseCase\CreateFollowingUseCase;
use App\UseCase\RetrieveFolloweesUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowingsController extends Controller {

  /**
   * @Route("/followings", methods={"POST"})
   */
  public function createFollowing(Request $request, CreateFollowingUseCase $usecase) {
    $usecase->run("", "");
    return new Response("Following created.", 201, ["Content-Type" => "text/plain"]);
  }

  /**
   * @Route("/followings/{followerId}/followees", methods={"GET"})
   */
  public function retrieveFollowees(string $followerId, RetrieveFolloweesUseCase $usecase) {
    $followees = $usecase->run($followerId);
    return $this->json($followees);
  }

}
