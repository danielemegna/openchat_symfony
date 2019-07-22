<?php

namespace App\Repository;

interface UserRepository {
  function getAll();
  function getByUsername($username);
  function store($user);
}
