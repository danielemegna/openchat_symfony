<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends Controller {

  /**
   * @Route("/users")
   */
  public function serve() {
    return $this->json([]);
  }

}
