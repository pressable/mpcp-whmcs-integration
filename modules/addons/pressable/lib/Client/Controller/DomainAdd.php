<?php

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\Redirect;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class DomainAdd extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)$data['siteId'];
    $domain = $data['name'];

    $this->assertGoodResponse($this->getApi($config)->addSiteDomain($id, $domain));

    $data = $this->getRedirectData($data);
    $data['siteId'] = $id;

    return new Redirect('showSite', $data, $config);
  }

}
