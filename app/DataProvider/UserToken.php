<?php

declare(strict_types=1);

namespace App\DataProvoder;

use Illuminate\Database\DatabaseManager;
use stdClass;

final class UserToken implements UserTokenProviderInterface
{
  private $manager;
  private $table = 'user_tokens';

  public function __construct(DatabaseManage $manager)
  {
    $this->manager = $manager;
  }

  public function retrieveUserByToken(String $token): ?stdClass
  {
    return $this->manager->connection()
    ->table($this->table)
      ->join('users', 'users.id', '=', 'user_tokens.user_id')
      ->where('api_token', $token)
      ->first(['user_id', 'api_token', 'name', 'email']);
  }
}