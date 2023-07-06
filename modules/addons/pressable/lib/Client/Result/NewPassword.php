<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Result;

class NewPassword implements Result
{

  /** @var string */
  private $password;

  public function __construct(string $password)
  {
    $this->password = $password;
  }

  public function toArray(): array
  {
    return [
      'breadcrumb' => [
        'index.php?m=pressable' => 'Manage Sites',
        "" => 'New Password',
      ],
      'requirelogin' => true,
      'templatefile' => 'new_password',
      'vars' => [
        'password' => $this->password,
      ],
    ];
  }

}

