<?php

namespace WHMCS\Module\Addon\Pressable\Client\Result;

use WHMCS\Module\Addon\Pressable\Client\Service;

class SiteList implements Result
{

  /** @var bool */
  private $canAddSite = false;

  /** @var array */
  private $createOptions;

  /** @var bool */
  private $isAdmin = false;

  /** @var array */
  private $list;

  /** @var array */
  private $pagination;

  /** @var string */
  private $postUrl;

  /** @var ?Service */
  private $service;

  public function __construct(
    array $list,
    array $pagination,
    string $postUrl,
    bool $isAdmin = false,
    bool $canAddSite = false,
    ?Service $service = null,
    array $createOptions = []
  )
  {
    $this->list = $list;
    $this->pagination = $pagination;
    $this->postUrl = $postUrl;
    $this->isAdmin = $isAdmin;
    $this->canAddSite = $canAddSite;
    $this->service = $service;
    $this->createOptions = $createOptions;
  }

  public function toArray(): array
  {
    $breadcrumbs = [];
    if (isset($this->service)) {
      $breadcrumbs = [
        "clientarea.php?action=productdetails&id={$this->service->getId()}" => 'Product Details',
      ];
    }
    $breadcrumbs[''] = 'Sites';

    return [
      'breadcrumb' => $breadcrumbs,
      'requirelogin' => true,
      'templatefile' => 'site_list',
      'vars' => [
        'list' => $this->list,
        'pagination' => $this->pagination,
        'url' => $this->postUrl,
        'createOptions' => $this->createOptions,
        'isAdmin' => $this->isAdmin,
        'canAddSite' => $this->canAddSite,
      ],
    ];
  }

}
