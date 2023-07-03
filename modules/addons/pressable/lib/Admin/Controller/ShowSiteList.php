<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Controller;

use WHMCS\Module\Addon\Pressable\Admin\Result\Result as BaseResult;
use WHMCS\Module\Addon\Pressable\Admin\Result\SiteList as Result;

class ShowSiteList extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $response = $this->assertGoodResponse($this->getApi($config)->siteList($data));
    $body = json_decode($response->getBody()->getContents(), true);

    return new Result($body['data'] ?? [], $body['page'], $config['modulelink']);
  }

}
