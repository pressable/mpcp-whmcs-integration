<?php

use WHMCS\Module\Addon\Pressable\Api\Pressable as Api;
use WHMCS\Module\Addon\Pressable\Api\Whmcs;
use WHMCS\Module\Addon\Pressable\Client\Service;
use WHMCS\Module\Addon\Pressable\Core\Tokenizer;

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

  $tokenizer = new Tokenizer(Whmcs::getSetting('tokenizer_key'));
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

function pressable_SuspendAccount(array $data)
{
  $data['tag_name'] = Api::SITE_TAG_SERVICE_PREFIX . $data['serviceid'];

  (new Api(
    Whmcs::getSetting('pressable_client_id'),
    Whmcs::getSetting('pressable_client_secret')
  ))->disableSites($data);

  return 'success';
}

function pressable_UnsuspendAccount(array $data)
{
  $data['tag_name'] = Api::SITE_TAG_SERVICE_PREFIX . $data['serviceid'];

  (new Api(
    Whmcs::getSetting('pressable_client_id'),
    Whmcs::getSetting('pressable_client_secret')
  ))->enableSites($data);

  return 'success';
}
