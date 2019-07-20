<?php

namespace App\Controller;

use App\Entity\User;
use App\UseCase\RegisterUserUseCase;
use App\UseCase\RetrieveUsersUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends Controller {

  /**
   * @Route("/users", methods={"GET"})
   */
  public function retrieveUsers(RetrieveUsersUseCase $usecase) {
    $responseBody = array_map(function($u) {
      return [
        'id' => $u->getId(),
        'username' => $u->getUsername(),
        'about' => $u->getAbout()
      ];
    }, $usecase->run());

    return $this->json($responseBody);
  }

  /**
   * @Route("/users", methods={"POST"})
   */
  public function registerUser(Request $request, RegisterUserUseCase $usecase) {
    $requestBody = json_decode($request->getContent());
    $userToBeRegistered = User::newWithoutId(
      $requestBody->username,
      $requestBody->about,
      $requestBody->password
    );

    $createdUser = $usecase->run($userToBeRegistered);

    $responseBody = [
      'id' => $createdUser->getId(),
      'username' => $createdUser->getUsername(),
      'about' => $createdUser->getAbout()
    ];
    return $this->json($responseBody);
  }

}
