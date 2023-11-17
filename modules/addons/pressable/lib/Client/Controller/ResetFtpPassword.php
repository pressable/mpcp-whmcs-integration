<?php

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\NewPassword as Result;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class ResetFtpPassword extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)$data['siteId'];

    $response = $this->getApi($config)->resetFtpPassword($id, $data['username']);
    $this->assertGoodResponse($response);
    $password = json_decode((string)$response->getBody(), true)['data'];

    return new Result($password, $config['service']);
  }

}
