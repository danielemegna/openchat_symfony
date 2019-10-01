<?php

namespace App\Repository;

interface PostRepository {
  function getByUserId($userId);
  function store($post);
}
