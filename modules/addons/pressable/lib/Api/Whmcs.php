<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api;

use WHMCS\Database\Capsule;
use WHMCS\Module\Addon\Setting;

class Whmcs
{

  public static function getClient(int $id): object
  {
    // @phpstan-ignore-next-line
    return Capsule::table('tblclients')->find($id);
  }

  public static function getClients(): iterable
  {
    // @phpstan-ignore-next-line
    return Capsule::table('tblclients')->get();
  }

  public static function getSetting(string $name): string
  {
    // @phpstan-ignore-next-line
    return Setting::where('module', 'pressable')
      ->where('setting', $name)
      ->value('value');
  }

}
