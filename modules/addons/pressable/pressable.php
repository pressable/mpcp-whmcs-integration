<?php

declare(strict_types = 1);

use Pressable\Whmcs\Admin\Router as AdminRouter;

function pressable_ConfigOptions(): array
{
  return [
    'name' => 'Pressable',
    'description' => 'Communicate with the Pressable API',
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
