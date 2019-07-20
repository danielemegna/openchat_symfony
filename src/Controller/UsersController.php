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
    $users = $usecase->run();
    return $this->serializeUsers($users);
  }

  /**
   * @Route("/users", methods={"POST"})
   */
  public function registerUser(Request $request, RegisterUserUseCase $usecase) {
    $userToBeRegistered = $this->deserializeUser($request->getContent());
    $createdUser = $usecase->run($userToBeRegistered);
    return $this->serializeUser($createdUser);
  }

  private function serializeUsers($users) {
    $responseBody = array_map(function($u) {
      return [
        'id' => $u->getId(),
        'username' => $u->getUsername(),
        'about' => $u->getAbout()
      ];
    }, $users);

    return $this->json($responseBody);
  }

  private function deserializeUser($jsonString) {
    $json = json_decode($jsonString);
    return User::newWithoutId(
      $json->username,
      $json->about,
      $json->password
    );
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
