<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Api;

class Client
{

  public function __construct(private string $id, private string $secret)
  {
  }

}
