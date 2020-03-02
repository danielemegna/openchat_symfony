<?php

namespace App\Controller;

use App\Entity\User;
use App\UseCase\Error\InvalidCredentialsError;
use App\UseCase\LoginUserUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController {

  /**
   * @Route("/login", methods={"POST"})
   */
  public function performLogin(Request $request, LoginUserUseCase $usecase) {
    $requestBody = json_decode($request->getContent());

    $loggedUser = $usecase->run($requestBody->username, $requestBody->password);

    if($loggedUser instanceof InvalidCredentialsError)
      return new Response('Invalid credentials.', 404, ['Content-Type' => 'text/plain']);

    $responseBody = [
      'id' => $loggedUser->id,
      'username' => $loggedUser->username,
      'about' => $loggedUser->about
    ];
    return $this->json($responseBody);
  }

}
