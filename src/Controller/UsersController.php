<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends Controller {

  /**
   * @Route("/users", methods={"GET"})
   */
  public function retrieveUsers() {
    return $this->json([]);
  }

  /**
   * @Route("/users", methods={"POST"})
   */
  public function registerUser(Request $request) {
    $userToBeRegistered = json_decode($request->getContent());

    $createdUser = [
      'id' => '7a440ea9-6272-454b-a552-0848e9c366ae',
      'username' => $userToBeRegistered->username,
      'about' => $userToBeRegistered->about
    ];
    return $this->json($createdUser);
  }

}
