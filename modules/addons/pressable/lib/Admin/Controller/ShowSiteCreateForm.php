<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin\Controller;

use Pressable\Whmcs\Admin\Result\SiteCreateForm as Result;

class ShowSiteCreateForm extends Controller
{

  public function __invoke(array $data, array $config): Result
  {
    $api = $this->getApi($config);
    $options = [];

    $response = $this->assertGoodResponse($api->datacenterList());
    $datacentersResponse = json_decode($response->getBody()->getContents(), true);
    foreach ($datacentersResponse['data'] ?? [] as $item) {
      $options['datacenter_code'][$item['code']] = $item['name'];
    }

    $response = $this->assertGoodResponse($api->siteInstallOptionList());
    $installOptionsResponse = json_decode($response->getBody()->getContents(), true);
    foreach ($installOptionsResponse['data'] ?? [] as $item) {
      $options['install'][$item] = $item;
    }

    $response = $this->assertGoodResponse($api->phpVersionsList());
    $phpVersionsResponse = json_decode($response->getBody()->getContents(), true);
    foreach ($phpVersionsResponse['data'] ?? [] as $item) {
      $options['php_version'][$item] = $item;
    }

    return new Result($options, $config['modulelink']);
  }

}
