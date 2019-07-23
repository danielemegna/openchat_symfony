<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\InvalidCredentials;
use App\UseCase\LoginUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends Controller {

  /**
   * @Route("/login", methods={"POST"})
   */
  public function performLogin(Request $request, LoginUserUseCase $usecase) {
    $requestBody = json_decode($request->getContent());

    $loggedUser = $usecase->run($requestBody->username, $requestBody->password);
    if($loggedUser instanceof InvalidCredentials)
      return new Response('Invalid credentials.', 404, ['Content-Type' => 'text/plain']);

    return $this->serializeUser($loggedUser);
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
