<?php

declare(strict_types = 1);

add_hook('ClientAreaPrimaryNavbar', 1, static function($nav) {
  $nav->addChild(
    'pressableManageSites',
    [
      'name' => 'Manage Sites',
      'label' => 'Manage Sites',
      'uri' => 'index.php?m=pressable',
      'order' => 99,
    ]
  );
});
