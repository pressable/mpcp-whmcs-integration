<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Result;

class BadRequest implements Result
{

  /** @var string */
  private $message;

  public function __construct(string $message)
  {
    $this->message = $message;
  }

  public function toArray(): array
  {
    return [
      'breadcrumb' => ['index.php?m=pressable' => 'Manage Sites'],
      'templatefile' => 'error',
      'requirelogin' => true,
      'vars' => ['message' => $this->message],
    ];
  }

}
