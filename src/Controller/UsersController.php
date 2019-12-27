<?php

namespace App\Controller;

use App\Entity\User;
use App\UseCase\UsernameAlreadyUsedError;
use App\UseCase\RegisterUserUseCase;
use App\UseCase\RetrieveUsersUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController {

  /**
   * @Route("/users", methods={"GET"})
   */
  public function retrieveUsers(RetrieveUsersUseCase $usecase) {
    $users = $usecase->run();

    $responseBody = array_map(function($u) {
      return [
        'id' => $u->getId(),
        'username' => $u->getUsername(),
        'about' => $u->getAbout()
      ];
    }, $users);
    return $this->json($responseBody);
  }

  /**
   * @Route("/users", methods={"POST"})
   */
  public function registerUser(Request $request, RegisterUserUseCase $usecase) {
    $json = json_decode($request->getContent());
    $userToBeRegistered = User::newWithoutId($json->username, $json->about, $json->password);
    $createdUser = $usecase->run($userToBeRegistered);

    if($createdUser instanceof UsernameAlreadyUsedError)
      return new Response('Username already in use.', 400, ['Content-Type' => 'text/plain']);

    $responseBody = [
      'id' => $createdUser->getId(),
      'username' => $createdUser->getUsername(),
      'about' => $createdUser->getAbout()
    ];
    return $this->json($responseBody);
  }

}
