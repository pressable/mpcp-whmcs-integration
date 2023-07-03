<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin\Controller;

use Pressable\Whmcs\Admin\Result\Result;
use Pressable\Whmcs\Api\Client;
use Psr\Http\Message\ResponseInterface;

abstract class Controller
{

  /** @var ?Client */
  private $_api;

  public static function factory(string $action): self
  {
    $name = __NAMESPACE__ . '\\' . ucfirst($action);

    return new $name();
  }

  protected function assertGoodResponse(ResponseInterface $response): ResponseInterface
  {
    if ($response->getStatusCode() >= 300) {
      // todo: throw exception
    }

    return $response;
  }

  protected function getApi(array $config): Client
  {
    if (! isset($this->_api)) {
      $this->_api = new Client($config['pressable_client_id'], $config['pressable_client_secret']);
    }

    return $this->_api;
  }

  abstract public function __invoke(array $data, array $config): Result;

}
