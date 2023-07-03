<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin\Controller;

use Pressable\Whmcs\Admin\Result\Result as BaseResult;
use Pressable\Whmcs\Admin\Result\SiteList as Result;

class ShowSiteList extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $response = $this->assertGoodResponse($this->getApi($config)->siteList($data));
    $body = json_decode($response->getBody()->getContents(), true);

    return new Result($body['data'] ?? [], $body['page'], $config['modulelink']);
  }

}
