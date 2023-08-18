<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Authentication\CurrentUser;
use WHMCS\Module\Addon\Pressable\Api\PressableClientRestricted as Api;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;
use WHMCS\Module\Addon\Pressable\Client\Result\SiteList as Result;

class ShowSiteList extends Controller
{

  private function getSiteCreateOptions(Api $api): array
  {
    return [
      'datacenters' => $this->getOptionsDatacenter($api),
      'installOptions' => $this->getOptionsInstall($api),
      'phpVersions' => $this->getOptionsPhpVersion($api),
    ];
  }

  private function getOptionsDatacenter(Api $api): ?array
  {
    $list = [];

    $response = $this->assertGoodResponse($api->datacenterList());
    $body = json_decode((string)$response->getBody(), true);
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

    $display = static function ($item) {
      $item = str_replace('wordpress', 'WordPress', $item);

      return ucfirst($item);
    };

    $response = $this->assertGoodResponse($api->siteInstallOptionList());
    $body = json_decode((string)$response->getBody(), true);
    foreach ($body['data'] ?? [] as $item) {
      $list[$item] = $display($item);
    }

    return empty($list)
      ? null
      : $list;
  }

  private function getOptionsPhpVersion(Api $api): ?array
  {
    $list = [];

    $response = $this->assertGoodResponse($api->phpVersionsList());
    $body = json_decode((string)$response->getBody(), true);
    foreach ($body['data'] ?? [] as $item) {
      $list[$item] = $item;
    }

    return empty($list)
      ? null
      : $list;
  }

  public function __invoke(array $data, array $config): BaseResult
  {
    $api = $this->getApi($config);
    $service = $config['service'];

    $response = $this->assertGoodResponse($api->siteList($data));
    $body = json_decode((string)$response->getBody(), true);

    $isAdmin = (new CurrentUser())->isMasqueradingAdmin();

    $siteCount = $this->getApi($config)->siteCount();
    $canAdd = ! $service->isAtSiteLimit($siteCount);

    return new Result(
      $body['data'] ?? [],
      $body['page'],
      $this->getPostUrl($data, $config),
      $isAdmin,
      $canAdd,
      $service,
      $this->getSiteCreateOptions($api)
    );
  }

}
