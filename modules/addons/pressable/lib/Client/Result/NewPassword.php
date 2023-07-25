<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Result;

use WHMCS\Module\Addon\Pressable\Client\Service;

class NewPassword implements Result
{

  /** @var string */
  private $password;

  /** @var ?Service */
  private $service;

  public function __construct(string $password, ?Service $service)
  {
    $this->password = $password;
    $this->service = $service;
  }

  public function toArray(): array
  {
    $breadcrumbs = [];
    if (isset($this->service)) {
      $breadcrumbs = [
        "clientarea.php?action=productdetails&id={$this->service->getId()}" => 'Service',
      ];
    }
    $breadcrumbs[''] = 'New Password';

    return [
      'breadcrumb' => $breadcrumbs,
      'requirelogin' => true,
      'templatefile' => 'new_password',
      'vars' => [
        'password' => $this->password,
      ],
    ];
  }

}

