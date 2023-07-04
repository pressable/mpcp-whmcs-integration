<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\BadRequest;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;
use WHMCS\Module\Addon\Pressable\Client\Result\Site as Result;

class ShowSite extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)$data['siteId'];
    $response = $this->assertGoodResponse($this->getApi($config)->getSite($id));
    $body = json_decode($response->getBody()->getContents(), true);

    if (empty($body)) {
      return new BadRequest('Not Found');
    }

    return new Result($body['data'], $config['modulelink']);
  }

}

