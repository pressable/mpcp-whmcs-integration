<?php

namespace WHMCS\Module\Addon\Pressable\Client\Controller;

use WHMCS\Module\Addon\Pressable\Client\Result\Redirect;
use WHMCS\Module\Addon\Pressable\Client\Result\Result as BaseResult;

class RestoreFromBackup extends Controller
{

  public function __invoke(array $data, array $config): BaseResult
  {
    $id = (int)($data['siteId'] ?? '');

    $backups = array_filter([
      'filesystem_id' => $config['filesystem_id'] ?? null,
      'database_id' => $config['database_id'] ?? null,
    ]);

    $this->assertGoodResponse($this->getApi($config)->restoreBackups($id, $backups));

    $data = $this->getRedirectData($data);
    $data['siteId'] = $id;

    return new Redirect('showSite', $data, $config);
  }

}
