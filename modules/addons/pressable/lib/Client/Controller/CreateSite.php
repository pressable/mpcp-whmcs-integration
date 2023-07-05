<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\Redirect;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class CreateSite extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $this->assertGoodResponse($this->getApi($config)->createSite($data));

    return new Redirect('showSiteList', [], $config);
  }

}
