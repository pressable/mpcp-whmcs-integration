<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api\Error;

use Exception;
use Psr\Http\Message\ResponseInterface;

class Pressable extends Exception
{

  private const DEFAULT_MESSAGE = 'Sorry, an error has occurred';

  /** @var ?array */
  private $errors;

  public function setErrors(?array $errors): Pressable
  {
    $this->errors = $errors;

    return $this;
  }

  public static function fromResponse(ResponseInterface $response)
  {
    $body = json_decode($response->getBody()->getContents(), true);

    return (new self($body['message'] ?? self::DEFAULT_MESSAGE))
      ->setErrors($body['errors']);
  }

  public function __toString(): string
  {
    $message = parent::__toString();

    foreach ($this->errors ?? [] as $error) {
      $message .= "\n- {$error}";
    }

    return $message;
  }

}
