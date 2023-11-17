<?php

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\Redirect;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class DeleteSite extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)($data['siteId'] ?? '');
    $this->assertGoodResponse($this->getApi($config)->deleteSite($id));

    return new Redirect('showSiteList', $this->getRedirectData($data), $config);
  }

}
