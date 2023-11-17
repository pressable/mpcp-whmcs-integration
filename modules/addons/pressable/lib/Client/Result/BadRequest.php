<?php

namespace WHMCS\Module\Addon\Pressable\Client\Result;

use Throwable;
use WHMCS\Module\Addon\Pressable\Api\Error\Pressable;
use WHMCS\Module\Addon\Pressable\Client\Service;

class BadRequest implements Result
{

  /** @var string */
  private $message;

  /** @var ?Service */
  private $service;

  public function __construct(string $message, ?Service $service)
  {
    $this->message = $message;
    $this->service = $service;
  }

  public static function fromError(Throwable $e, ?Service $service): self
  {
    $message = $e instanceof Pressable
        ? $e->getFullMessage()
        : $e->getMessage();

    return new self($message, $service);
  }

  public function toArray(): array
  {
    $breadcrumbs = [];
    if (isset($this->service)) {
      $breadcrumbs = [
        "clientarea.php?action=productdetails&id={$this->service->getId()}" => 'Product Details',
      ];
    }
    $breadcrumbs[''] = 'Error';

    return [
      'breadcrumb' => $breadcrumbs,
      'templatefile' => 'error',
      'requirelogin' => true,
      'vars' => ['message' => $this->message],
    ];
  }

}
