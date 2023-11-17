<?php

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class PhpMyAdmin extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)($data['siteId'] ?? '');
    $response = $this->assertGoodResponse($this->getApi($config)->getPhpMyAdminUrl($id));
    $url = json_decode((string)$response->getBody(), true)['data'];

    header("Location: {$url}");
    exit;
  }

}
