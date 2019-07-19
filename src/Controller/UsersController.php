<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends Controller {

  private static $users = [];

  /**
   * @Route("/users", methods={"GET"})
   */
  public function retrieveUsers() {
    $responseBody = array_map(function($u) {
      return [
        'id' => $u->getId(),
        'username' => $u->getUsername(),
        'about' => $u->getAbout()
      ];
    }, self::$users);

    return $this->json($responseBody);
  }

  /**
   * @Route("/users", methods={"POST"})
   */
  public function registerUser(Request $request) {
    $requestBody = json_decode($request->getContent());

    $userToBeRegistered = User::newWithoutId(
      $requestBody->username,
      $requestBody->about,
      $requestBody->password
    );

    $createdUser = User::build(
      uniqid(),
      $userToBeRegistered->getUsername(),
      $userToBeRegistered->getAbout(),
      $userToBeRegistered->getPassword()
    );
    array_push(self::$users, $createdUser);

    $responseBody = [
      'id' => $createdUser->getId(),
      'username' => $createdUser->getUsername(),
      'about' => $createdUser->getAbout()
    ];
    return $this->json($responseBody);
  }

}
