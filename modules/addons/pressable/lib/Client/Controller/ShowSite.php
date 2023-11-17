<?php

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
    $body = json_decode((string)$response->getBody(), true);
    foreach ($body['data'] ?? [] as $item) {
      $list[$item] = $item;
    }

    return empty($list)
      ? null
      : $list;
  }

  private function getFtpUsername(Api $api, int $siteId): ?string
  {
    $response = $this->assertGoodResponse($api->getFtpUsers($siteId));
    $ftpUsers = json_decode((string)$response->getBody(), true)['data'] ?? [];

    $owner = null;
    foreach ($ftpUsers as $user) {
      if ($user['owner']) {
        $owner = $user;
        break;
      }
    }

    return $owner['username'] ?? null;
  }

  public function __invoke(array $data, array $config): BaseResult
  {
    $api = $this->getApi($config);
    $id = (int)$data['siteId'];

    $response = $this->assertGoodResponse($api->getSite($id));
    $site = json_decode((string)$response->getBody(), true)['data'];

    $response = $this->assertGoodResponse($api->getSiteDomains($id));
    $domains = json_decode((string)$response->getBody(), true)['data'] ?? [];

    $response = $this->assertGoodResponse($api->getSiteBackups($id));
    $backups = json_decode((string)$response->getBody(), true)['data'] ?? [];

    $phpVersions = $this->getOptionsPhpVersion($api);

    $site['ftpUsername'] = $this->getFtpUsername($api, $id);

    return new Result(
      $site,
      $domains,
      $backups,
      $phpVersions,
      $this->getPostUrl($data, $config),
      $config['service']
    );
  }

}
