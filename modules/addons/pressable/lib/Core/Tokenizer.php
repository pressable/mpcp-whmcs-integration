<?php

namespace WHMCS\Module\Addon\Pressable\Core;

use Firebase\JWT\JWT;
use stdClass;
use Throwable;

class Tokenizer
{

  private const SCHEME = 'HS256';

  /** @var string */
  private $key;

  public function __construct(string $key)
  {
    $this->key = $key;
  }

  public function fromToken(string $token): ?stdClass
  {
    try {
      $data = JWT::decode($token, $this->key, [self::SCHEME]);
    } catch (Throwable $e) {
      // Invalid token, or something gone horribly wrong
      return null;
    }

    return json_decode($data->sub ?? null);
  }

  public function toToken($subject): string
  {
    $data = [
      'sub' => json_encode($subject),
      'iat' => time(),
      'ver' => 1,
    ];

    return JWT::encode($data, $this->key, self::SCHEME);
  }

}
