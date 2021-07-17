<?php

declare(strict_types=1);

namespace App\DataProvoder;

use stdClass;

interface UserTokenProviderInterface
{
  /**
   * @params string $token
   * @return stdClass|null
   */
  public function retrieveUserByToken(string $token): ?stdClass
}