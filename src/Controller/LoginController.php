<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends Controller {

  /**
   * @Route("/login", methods={"POST"})
   */
  public function registerUser(Request $request) {
    return new Response('Invalid credentials.', 404, ['Content-Type' => 'text/plain']);
  }

}
