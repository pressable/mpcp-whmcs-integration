<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Client\Result;

class SiteList implements Result
{

  /** @var array */
  private $list;

  /** @var array */
  private $pagination;

  /** @var string */
  private $postUrl;

  public function __construct(array $list, array $pagination, string $postUrl)
  {
    $this->list = $list;
    $this->pagination = $pagination;
    $this->postUrl = $postUrl;
  }

  public function toArray(): array
  {
    return [
      'breadcrumb' => ['index.php?m=pressable' => 'Manage Sites'],
      'requirelogin' => true,
      'templatefile' => empty($this->list) ? 'site_list_empty' : 'site_list',
      'vars' => [
        'list' => $this->list,
        'pagination' => $this->pagination,
        'url' => $this->postUrl,
      ],
    ];
  }

}
