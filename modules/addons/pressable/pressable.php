<?php

declare(strict_types = 1);

use WHMCS\Module\Addon\Pressable\Admin\Router as AdminRouter;
use WHMCS\Module\Addon\Pressable\Client\Router as ClientRouter;

function pressable_config(): array
{
  return [
    'name' => 'Pressable',
    'description' => 'Communicate with the Pressable API',
    'author' => 'Pressable',
    'version' => '1.0',
    'fields' => [
      'pressable_client_id' => [
        'FriendlyName' => 'Pressable client_id',
        'Type' => 'text',
        'Description' => 'See https://my.pressable.com/api/applications',
      ],
      'pressable_client_secret' => [
        'FriendlyName' => 'Pressable client_secret',
        'Type' => 'password',
        'Description' => 'See https://my.pressable.com/api/applications',
      ],
    ],
  ];
}

function pressable_clientarea(array $config): array
{
  // phpcs:ignore
  $data = array_merge($_POST, $_GET);

  $result = (new ClientRouter())($data, $config);

  return $result->toArray();
}

/**
 * Admin page
 */
function pressable_output(array $config): void
{
  // phpcs:ignore
  $data = array_merge($_POST, $_GET);

  $result = (new AdminRouter())($data, $config);

  echo $result;
}
