<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin\Result;

use Pressable\Whmcs\Admin\Router as AdminRouter;

class Redirect implements Result
{

  /** @var array */
  private $data;

  /** @var array */
  private $config;

  public function __construct(string $action, array $data, array $config)
  {
    $data['_action'] = $action;

    $this->data = $data;
    $this->config = $config;
  }

  public function __toString(): string
  {
    return (string)(new AdminRouter())($this->data, $this->config);
  }

}
