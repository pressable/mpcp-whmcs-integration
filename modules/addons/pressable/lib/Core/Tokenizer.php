<?php

declare(strict_types = 1);

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
    $now = time();
    $data = [
      'sub' => json_encode($subject),
      'iat' => $now,
      'exp' => $this->getExpiration($now),
    ];

    return JWT::encode($data, $this->key, self::SCHEME);
  }

  private function getExpiration(int $now): int
  {
    // arbitrary 7 days
    return $now + (86400 * 7);
  }

}
