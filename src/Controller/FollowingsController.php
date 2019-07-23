<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FollowingsController extends Controller {

  /**
   * @Route("/followings", methods={"POST"})
   */
  public function performLogin(Request $request) {
    return new Response("Following created.", 201, ["Content-Type" => "text/plain"]);
  }

}
