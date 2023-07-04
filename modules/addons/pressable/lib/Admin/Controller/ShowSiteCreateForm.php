<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Controller;

use WHMCS\Module\Addon\Pressable\Admin\Result\Result as BaseResult;
use WHMCS\Module\Addon\Pressable\Admin\Result\SiteCreateForm as Result;
use WHMCS\Module\Addon\Pressable\Api\Pressable as Api;
use WHMCS\Module\Addon\Pressable\Api\Whmcs;

class ShowSiteCreateForm extends Controller
{

  private function getOptionsDatacenter(Api $api): ?array
  {
    $list = [];

    $response = $this->assertGoodResponse($api->datacenterList());
    $body = json_decode($response->getBody()->getContents(), true);
    foreach ($body['data'] ?? [] as $item) {
      $list[$item['code']] = $item['name'];
    }

    return empty($list)
      ? null
      : $list;
  }

  private function getOptionsInstall(Api $api): ?array
  {
    $list = [];

    $response = $this->assertGoodResponse($api->siteInstallOptionList());
    $body = json_decode($response->getBody()->getContents(), true);
    foreach ($body['data'] ?? [] as $item) {
      $list[$item] = $item;
    }

    return empty($list)
      ? null
      : $list;
  }

  private function getOptionsPhpVersion(Api $api): ?array
  {
    $list = [];

    $response = $this->assertGoodResponse($api->phpVersionsList());
    $body = json_decode($response->getBody()->getContents(), true);
    foreach ($body['data'] ?? [] as $item) {
      $list[$item] = $item;
    }

    return empty($list)
      ? null
      : $list;
  }

  private function getOptionsClients(): ?array
  {
    $list = [];

    foreach (Whmcs::getClients() as $client) {
      $list[$client->id] = "{$client->firstname} {$client->lastname}";
    }

    return empty($list)
      ? null
      : $list;
  }

  public function __invoke(array $data, array $config): BaseResult
  {
    $api = $this->getApi($config);

    $options = array_filter([
      'datacenter_code' => $this->getOptionsDatacenter($api),
      'install' => $this->getOptionsInstall($api),
      'php_version' => $this->getOptionsPhpVersion($api),
      'client_id' => $this->getOptionsClients(),
    ]);

    return new Result($options, $config['modulelink']);
  }

}
