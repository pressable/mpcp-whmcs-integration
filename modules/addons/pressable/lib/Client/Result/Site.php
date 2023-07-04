<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Result;

class Site implements Result
{

  /** @var array */
  private $site;

  /** @var string */
  private $postUrl;

  public function __construct(array $site, string $postUrl)
  {
    $this->site = $site;
    $this->postUrl = $postUrl;
  }

  public function toArray(): array
  {
    return [
      'breadcrumb' => ['index.php?m=pressable' => 'Manage Sites'],
      'requirelogin' => true,
      'templatefile' => 'site',
      'vars' => [
        'item' => $this->site,
        'url' => $this->postUrl,
      ],
    ];
  }

}

