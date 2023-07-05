<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\Redirect;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class DomainDelete extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)$data['siteId'];
    $domain = (int)$data['domainId'];

    $this->assertGoodResponse($this->getApi($config)->deleteSiteDomain($id, $domain));

    return new Redirect('showSite', ['siteId' => $id], $config);
  }

}
