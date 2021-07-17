<?php

namespace App\Entity;

use Illuminate\Foundation\Auth\User as Authenticatable;


class User implements Authenticatable
{
    private $id;
    private $apitoken;
    private $name;
    private $email;
    private $password;

    public function __construct(int $id, string $apitoken, string $name, string $email, string $password ='')
    {
      $this->id = $id;
      $this->apitoken = $apitoken;
      $this->name = $name;
      $this->email = $email;
      $this->password = $password;
    }

    public function getName(): string
    {
      return $this->name;
    }

    public function getEmail(): string
    {
      return $this->email;
    }

    public function getAuthIdentifierName(): string
    {
      return 'user_id';
    }

    public function getAuthIdentifier(): int
    {
      return $this->id;
    }

    public function getAuthPassword()
    {
      return $this->password;
    }

    public function getRememberToken(): string
    {
      return '';
    }

    public function setRememberToken($value)
    {
    }

    public function getRememberTokenName(): string
    {
      return '';
    }
    
}
