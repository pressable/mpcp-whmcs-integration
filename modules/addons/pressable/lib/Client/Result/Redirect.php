<?php

namespace WHMCS\Module\Addon\Pressable\Client\Result;

use WHMCS\Module\Addon\Pressable\Client\Router as ClientRouter;

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

  public function toArray(): array
  {
    return (new ClientRouter())($this->data, $this->config)->toArray();
  }

}
