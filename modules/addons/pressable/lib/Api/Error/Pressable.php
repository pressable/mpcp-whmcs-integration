<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api\Error;

use Exception;
use Psr\Http\Message\ResponseInterface;

class Pressable extends Exception
{

  private const API_ERROR_MESSAGE = 'API Error: %s';
  private const DEFAULT_MESSAGE = 'Sorry, an error has occurred';

  /** @var ?array */
  private $errors;

  public function setErrors(?array $errors): Pressable
  {
    $this->errors = $errors;

    return $this;
  }

  public function getFullMessage(): string
  {
    $message = parent::getMessage();

    foreach ($this->errors ?? [] as $error) {
      $message .= "\n- {$error}";
    }

    return $message;
  }

  public static function fromResponse(ResponseInterface $response)
  {
    $body = json_decode((string)$response->getBody(), true);
    $message = $body['message'] ?? null;
    if (empty($message) && $response->getStatusCode() >= 400) {
      $message = "{$response->getStatusCode()} {$response->getReasonPhrase()}";
      $message = sprintf(self::API_ERROR_MESSAGE, $message);
    }

    return (new self($message ?? self::DEFAULT_MESSAGE))
      ->setErrors($body['errors'] ?? null);
  }

}
