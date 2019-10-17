<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersWallController extends Controller {

  /**
   * @Route("/users/{userId}/wall", methods={"GET"})
   */
  public function submitPost(string $userId, Request $request) {
    return new Response("User not found.", 400, ["Content-Type" => "text/plain"]);
  }

}
