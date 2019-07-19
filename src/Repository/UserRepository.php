<?php

namespace App\Repository;

interface UserRepository {
  function getAll();
  function store($user);
}
