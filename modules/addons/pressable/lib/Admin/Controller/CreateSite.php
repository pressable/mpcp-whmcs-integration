<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Controller;

use WHMCS\Module\Addon\Pressable\Admin\Result\Redirect;
use WHMCS\Module\Addon\Pressable\Admin\Result\Result as BaseResult;
use WHMCS\Module\Addon\Pressable\Api\Pressable as Api;

class CreateSite extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $api = $this->getApi($config);

    $response = $this->assertGoodResponse($api->createSite($data));
    $body = json_decode($response->getBody()->getContents(), true);
    $siteId = $body['data']['id'];

    $prefix = Api::SITE_TAG_CLIENT_PREFIX;

    $api->addSiteTag((int)$siteId, "{$prefix}{$data['client_id']}");

    return new Redirect('showSiteList', $data, $config);
  }

}
