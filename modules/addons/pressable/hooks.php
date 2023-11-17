<?php

use WHMCS\Module\Addon\Pressable\Api\Pressable as Api;
use WHMCS\Module\Addon\Pressable\Api\Whmcs;

add_hook('ServiceDelete', 1, static function($vars) {
  $serviceId = $vars['serviceid'];

  $apiId = Whmcs::getSetting('pressable_client_id');
  $apiSecret = Whmcs::getSetting('pressable_client_secret');

  (new Api($apiId, $apiSecret))
    ->deleteAllSites(Api::SITE_TAG_SERVICE_PREFIX . $serviceId);
});
