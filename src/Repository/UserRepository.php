<?php

namespace App\Repository;

interface UserRepository {
  function getAll();
  function getByUsername($username);
  function getById($id);
  function existsById($id);
  function store($user);
}
