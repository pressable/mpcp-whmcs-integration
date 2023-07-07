<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin;

use Throwable;
use WHMCS\Module\Addon\Pressable\Admin\Controller\Controller;
use WHMCS\Module\Addon\Pressable\Admin\Result\BadRequest;
use WHMCS\Module\Addon\Pressable\Admin\Result\Result;

class Router
{

  private const _ACTIONS = [
    'createSite',
    'deleteSite',
    'suspendSite',
    'unsuspendSite',
    'showSiteCreateForm',
    'showSiteList',
  ];

  private const _DEFAULT_ACTION = 'showSiteList';

  private function getAction(array $data): string
  {
    return $data['_action'] ?? self::_DEFAULT_ACTION;
  }

  private function isValidAction(string $action): bool
  {
    return in_array($action, self::_ACTIONS);
  }

  public function __invoke(array $data, array $config): Result
  {
    $action = $this->getAction($data);
    if (! $this->isValidAction($action)) {
      return new BadRequest('Invalid Action');
    }

    $controller = Controller::factory($action);

    try {
      return $controller($data, $config);
    } catch (Throwable $e) {
      return BadRequest::fromError($e);
    }
  }

}
