<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Api\PressableClientRestricted as Api;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;
use WHMCS\Module\Addon\Pressable\Client\Result\Site as Result;

class ShowSite extends Controller
{

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

  public function __invoke(array $data, array $config): BaseResult
  {
    $api = $this->getApi($config);
    $id = (int)$data['siteId'];

    $response = $this->assertGoodResponse($api->getSite($id));
    $site = json_decode($response->getBody()->getContents(), true)['data'];

    $response = $this->assertGoodResponse($api->getSiteDomains($id));
    $domains = json_decode($response->getBody()->getContents(), true)['data'] ?? [];

    $response = $this->assertGoodResponse($api->getSiteBackups($id));
    $backups = json_decode($response->getBody()->getContents(), true)['data'] ?? [];

    $phpVersions = $this->getOptionsPhpVersion($api);

    return new Result(
      $site,
      $domains,
      $backups,
      $phpVersions,
      $config['modulelink'],
      $config['service']
    );
  }

}
