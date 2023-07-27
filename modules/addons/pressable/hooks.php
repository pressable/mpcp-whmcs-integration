<?php

declare(strict_types = 1);

use WHMCS\Module\Addon\Pressable\Api\Pressable as Api;
use WHMCS\Module\Addon\Setting;

add_hook('ServiceDelete', 1, static function($vars) {
  $serviceId = $vars['serviceid'];

  $apiId = Setting::where('module', 'pressable')
    ->where('setting', 'pressable_client_id')
    ->value('value');
  $apiSecret = Setting::where('module', 'pressable')
    ->where('setting', 'pressable_client_secret')
    ->value('value');

  (new Api($apiId, $apiSecret))
    ->deleteAllSites(Api::SITE_TAG_SERVICE_PREFIX . $serviceId);
});
