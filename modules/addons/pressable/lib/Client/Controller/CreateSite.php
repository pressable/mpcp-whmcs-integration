<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use Exception;
use WHMCS\Module\Addon\Pressable\Client\Result\Redirect;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class CreateSite extends Controller
{

  private function assertCanAddSite($config): void
  {
    $service = $config['service'];
    $count = $this->getApi($config)->siteCount();

    if ($service->isAtSiteLimit($count)) {
      throw new Exception('Denied');
    }
  }

  public function __invoke(array $data, array $config): BaseResult
  {
    $this->assertCanAddSite($config);

    $data['name'] = trim($data['name']);
    $data['name'] = trim($data['name'], '-');
    $data['name'] = preg_replace(
      ['(\s+)', '([^a-zA-Z0-9-])'],
      ['-', ''],
      $data['name']
    );

    $this->assertGoodResponse($this->getApi($config)->validateSiteName($data['name']));
    $this->assertGoodResponse($this->getApi($config)->createSite($data));

    return new Redirect('showSiteList', $this->getRedirectData($data), $config);
  }

}
