<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin;

use Pressable\Whmcs\Admin\Controller\Controller;
use Pressable\Whmcs\Admin\Result\BadRequest;
use Pressable\Whmcs\Admin\Result\Result;

class Router
{

  private const _ACTIONS = [
    'createSite',
    'deleteSite',
    'suspendSite',
    'unsuspendSite',
    'showSiteList',
    'showCreateForm',
  ];

  private const _DEFAULT_ACTION = 'showSiteList';

  private function getAction(array $data): string
  {
    return $data['action'] ?? self::_DEFAULT_ACTION;
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

    return $controller($data, $config);
  }

}
