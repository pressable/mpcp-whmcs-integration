<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin\Controller;

use Pressable\Whmcs\Admin\Result\Redirect;
use Pressable\Whmcs\Admin\Result\Result as BaseResult;

class UnsuspendSite extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $this->assertGoodResponse($this->getApi($config)->enableSite($data['siteId'] ?? ''));

    return new Redirect('showSiteList', $data, $config);
  }

}
