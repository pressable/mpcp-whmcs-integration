<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Api;

use WHMCS\Database\Capsule;

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

}
