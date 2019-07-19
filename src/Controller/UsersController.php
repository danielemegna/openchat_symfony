<?php

namespace App\Controller;

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
    return $this->json(self::$users);
  }

  /**
   * @Route("/users", methods={"POST"})
   */
  public function registerUser(Request $request) {
    $userToBeRegistered = json_decode($request->getContent());

    $createdUser = [
      'id' => uniqid(),
      'username' => $userToBeRegistered->username,
      'about' => $userToBeRegistered->about
    ];

    array_push(self::$users, $createdUser);

    return $this->json($createdUser);
  }

}
