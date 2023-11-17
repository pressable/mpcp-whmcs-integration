<?php

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\NewPassword as Result;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class ResetWpPassword extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)$data['siteId'];

    $response = $this->assertGoodResponse($this->getApi($config)->resetWpPassword($id));
    $password = json_decode((string)$response->getBody(), true)['data'];

    return new Result($password, $config['service']);
  }

}
