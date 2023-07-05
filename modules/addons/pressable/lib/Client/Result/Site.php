<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Result;

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

  /** @var array */
  private $site;

  public function __construct(
    array $site,
    array $domains,
    array $backups,
    array $phpVersions,
    string $postUrl
  )
  {
    $this->site = $site;
    $this->domains = $domains;
    $this->backups = $backups;
    $this->phpVersions = $phpVersions;
    $this->postUrl = $postUrl;
  }

  public function toArray(): array
  {
    return [
      'breadcrumb' => [
        'index.php?m=pressable' => 'Manage Sites',
        "index.php?m=pressable&_action=showSite&siteId={$this->site['id']}" => $this->site['name'],
      ],
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

