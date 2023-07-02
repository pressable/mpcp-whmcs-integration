<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin\Result;

class BadRequest implements Result
{

  public function __construct(private string $_message)
  {}

  public function __toString(): string
  {
    return $this->_message;
  }

}
