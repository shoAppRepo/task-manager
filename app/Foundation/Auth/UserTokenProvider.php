<?php

declare(strinc_types=1)

namespace App\Foundation\Auth;

use App\DataProvider\UserTokenProviderInterface;
use App\Entity\User;
use Illuminate\Contracts\Auth\Authentivatable;
use Illuminate\Contracts\Auth\UserProvider;

use function is_null;

final class UserTokenProvider implements UserProvider
{
  private $provider;

  public function __construct(UserTokenProviderInterface $provider)
  {
    $this->provider = $provider;
  }

  public function retrieveById($identifier)
  {
    return null;
  }

  public function retrieveByToken($identifier, $token)
  {
    return null;
  }

  public function updateRememberToken(Authenticatable $user, $token)
  {
    // apiアプリケーションで自動ログインはできないため使用しない
  }

  public function retrieveByCredentials(array $credentials)
  {
    if(!iseet($credentials['api_token'])){
      return null;
    }

    // ユーザー情報取得
    $result = $this->provider->retrieveByToken($credentials['api_token']);
    if(is_null($result)){
      return null;
    }

    // インスタンスを返却することで、認証後にユーザ情報にアクセスできる
    return new User(
      $result->user_id,
      $result->api_token,
      $result->name,
      $result->email
    );
  }

  public function validateCredentials(Authenticatable $user, array $credentials)
  {
    // APIアプリケーションではパスワード認証しないため、false
    return false;
  }
}