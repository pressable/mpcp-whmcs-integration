<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Controller;

use WHMCS\Module\Addon\Pressable\Admin\Result\Redirect;
use WHMCS\Module\Addon\Pressable\Admin\Result\Result as BaseResult;

class SuspendSite extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)($data['siteId'] ?? '');
    $this->assertGoodResponse($this->getApi($config)->disableSite($id));

    return new Redirect('showSiteList', $data, $config);
  }

}
