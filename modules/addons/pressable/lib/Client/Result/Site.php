<?php

namespace WHMCS\Module\Addon\Pressable\Client\Result;

use WHMCS\Module\Addon\Pressable\Client\Service;

class Site implements Result
{

  /** @var array */
  private $backups;

  /** @var array */
  private $domains;

  /** @var array */
  private $phpVersions;

  /** @var string */
  private $postUrl;

  /** @var ?Service */
  private $service;

  /** @var array */
  private $site;

  public function __construct(
    array $site,
    array $domains,
    array $backups,
    array $phpVersions,
    string $postUrl,
    ?Service $service
  )
  {
    $this->site = $site;
    $this->domains = $domains;
    $this->backups = $backups;
    $this->phpVersions = $phpVersions;
    $this->postUrl = $postUrl;
    $this->service = $service;
  }

  public function toArray(): array
  {
    $breadcrumbs = [];
    if (isset($this->service)) {
      $breadcrumbs = [
        "clientarea.php?action=productdetails&id={$this->service->getId()}" => 'Product Details',
      ];
    }
    $breadcrumbs[''] = $this->site['name'];

    return [
      'breadcrumb' => $breadcrumbs,
      'requirelogin' => true,
      'templatefile' => 'site',
      'vars' => [
        'site' => $this->site,
        'domains' => $this->domains,
        'backups' => $this->backups,
        'phpVersions' => $this->phpVersions,
        'url' => $this->postUrl,
      ],
    ];
  }

}

