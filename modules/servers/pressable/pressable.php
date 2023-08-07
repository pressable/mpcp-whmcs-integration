<?php

declare(strict_types = 1);

use WHMCS\Module\Addon\Pressable\Client\Service;
use WHMCS\Module\Addon\Pressable\Core\Tokenizer;
use WHMCS\Module\Addon\Setting;

function pressable_MetaData()
{
  return [
    'DisplayName' => 'Pressable',
  ];
}

function pressable_ConfigOptions()
{
  return [
    'sites' => [
      'FriendlyName' => 'Sites Allowed',
      'Type' => 'text',
      'Description' => 'Maximum number of sites allowed to be created',
    ],
  ];
}

function pressable_ClientArea(array $data)
{
  if ($data['moduletype'] !== 'pressable') {
    return [];
  }

  $key = Setting::where('module', 'pressable')
    ->where('setting', 'tokenizer_key')
    ->value('value');
  $tokenizer = new Tokenizer($key);

  $service = new Service($data['serviceid'], $data['userid'], ['sites' => $data['configoption1']]);

  $url = 'index.php?m=pressable&service=' . urlencode($tokenizer->toToken($service->toArray()));

  return [
    'templatefile' => 'service',
    'vars' => [
      'url' => $url,
    ],
  ];
}

function pressable_CreateAccount(array $data)
{
  // Nothing to do
  // WHMCS expects this function to be defined and to return the following
  return 'success';
}
