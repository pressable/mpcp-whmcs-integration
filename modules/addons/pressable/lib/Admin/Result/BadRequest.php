<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Result;

use Throwable;
use WHMCS\Module\Addon\Pressable\Api\Error\Pressable as PressableError;

class BadRequest implements Result
{

  /** @var string */
  private $message;

  public function __construct(string $message)
  {
    $this->message = $message;
  }

  public static function fromError(Throwable $e): self
  {
    $message = ($e instanceof PressableError)
      ? $e->getMessage()
      : '';

    return new self($message);
  }

  public function __toString(): string
  {
    return $this->message;
  }

}
