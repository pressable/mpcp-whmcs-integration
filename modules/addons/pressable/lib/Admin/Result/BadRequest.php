<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin\Result;

class BadRequest implements Result
{

  /** @var string */
  private $message;

  public function __construct(string $message)
  {
    $this->message = $message;
  }

  public function __toString(): string
  {
    return $this->message;
  }

}
