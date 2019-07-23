<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends Controller {

  /**
   * @Route("/login", methods={"POST"})
   */
  public function performLogin(Request $request) {
    $requestBody = json_decode($request->getContent());

    if($requestBody->username !== "shady90")
      return new Response('Invalid credentials.', 404, ['Content-Type' => 'text/plain']);

    $user = User::build(
      uniqid(),
      $requestBody->username,
      "About shady90 here.",
      "not important"
    );
    return $this->serializeUser($user);
  }

  private function serializeUser($user) {
    $responseBody = [
      'id' => $user->getId(),
      'username' => $user->getUsername(),
      'about' => $user->getAbout()
    ];
    return $this->json($responseBody);
  }

}
