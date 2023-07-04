<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use Psr\Http\Message\ResponseInterface;
use WHMCS\Module\Addon\Pressable\Api\PressableClientRestricted as Api;
use WHMCS\Module\Addon\Pressable\Client\Result\Result;

abstract class Controller
{

  /** @var ?Api */
  private $_api;

  final protected function getApi(array $config): Api
  {
    if (! isset($this->_api)) {
      $this->_api = new Api($config['pressable_client_id'], $config['pressable_client_secret']);
    }

    return $this->_api;
  }

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

  abstract public function __invoke(array $data, array $config): Result;

}
