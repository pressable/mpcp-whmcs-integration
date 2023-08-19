<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client;

use Throwable;
use WHMCS\Authentication\CurrentUser;
use WHMCS\Module\Addon\Pressable\Client\Controller\Controller;
use WHMCS\Module\Addon\Pressable\Client\Result\BadRequest;
use WHMCS\Module\Addon\Pressable\Client\Result\Result;
use WHMCS\Module\Addon\Pressable\Core\Tokenizer;

class Router
{

  private const _ACTIONS = [
    'createSite',
    'deleteSite',
    'domainAdd',
    'domainDelete',
    'phpMyAdmin',
    'resetFtpPassword',
    'resetWpPassword',
    'restoreFromBackup',
    'showSite',
    'showSiteList',
    'suspendSite',
    'unsuspendSite',
    'updateSite',
  ];

  private const _ADMIN_ACTIONS = [
    'suspendSite',
    'unsuspendSite',
  ];

  private const _DEFAULT_ACTION = 'showSiteList';

  private function getAction(array $data): string
  {
    return $data['_action'] ?? self::_DEFAULT_ACTION;
  }

  private function isAdmin(): bool
  {
    return (new CurrentUser())->isMasqueradingAdmin();
  }

  private function isAdminAction(string $action): bool
  {
    return in_array($action, self::_ADMIN_ACTIONS);
  }

  private function isValidAction(?string $action): bool
  {
    return in_array($action, self::_ACTIONS);
  }

  private function getService(?string $token, string $key): ?Service
  {
    if (empty($token)) {
      return null;
    }

    $data = (new Tokenizer($key))->fromToken($token);

    if (is_null($data)) {
      return null;
    }

    return Service::fromArray((array)$data);
  }

  public function __invoke(array $data, array $config): Result
  {
    if (! $config['service'] instanceof Service) {
      $config['service'] = $this->getService($data['service'] ?? null, $config['tokenizer_key']);
    }

    $action = $this->getAction($data);
    if ($this->isAdminAction($action) && ! $this->isAdmin()) {
      $action = null;
    }
    if (! $this->isValidAction($action)) {
      return new BadRequest('Invalid Action', $config['service']);
    }

    $controller = Controller::factory($action);

    try {
      return $controller($data, $config);
    } catch (Throwable $e) {
      return BadRequest::fromError($e, $config['service']);
    }
  }

}
