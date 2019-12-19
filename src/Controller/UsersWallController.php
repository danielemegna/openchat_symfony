<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\PostRepository;

class UsersWallController extends AbstractController {

  private $userRepository;
  private $postRepository;

  function __construct(UserRepository $userRepository, PostRepository $postRepository) {
    $this->userRepository = $userRepository;
    $this->postRepository = $postRepository;
  }

  /**
   * @Route("/users/{userId}/wall", methods={"GET"})
   */
  public function submitPost(string $userId) {
    if(!$this->userRepository->existsById($userId))
      return new Response("User not found.", 400, ["Content-Type" => "text/plain"]);

    $posts = $this->postRepository->getByUserId($userId);
    return $this->json($this->serializePosts($posts), 200);
  }

  private function serializePosts(array $posts) {
    return array_map(function($p) {
      return [
        "postId" => $p->getId(),
        "userId" => $p->getUserId(),
        "text" => $p->getText(),
        "dateTime" => $p->getPublishDateTime()->format(\DateTime::ISO8601)
      ];
    }, $posts);
  }

}
