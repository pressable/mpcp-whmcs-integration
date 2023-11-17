<?php

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\Redirect;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class UpdateSite extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)$data['siteId'];
    $api = $this->getApi($config);

    // currently only allowing to set PHP version but we need to give all this data
    $response = $this->assertGoodResponse($api->getSite($id));
    $site = json_decode((string)$response->getBody(), true)['data'];
    $data = [
      'php_version' => $data['php_version'] ?? null,
      'name' => $site['name'],
      'wp_environment_type' => $site['wpEnvironmentType'],
    ];

    $this->assertGoodResponse($this->getApi($config)->updateSite($id, $data));

    // wait for the api to catch up
    sleep(1);

    $data = $this->getRedirectData($data);
    $data['siteId'] = $id;

    return new Redirect('showSite', $data, $config);
  }

}
