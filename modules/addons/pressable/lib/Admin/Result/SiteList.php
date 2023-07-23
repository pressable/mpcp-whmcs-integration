<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Result;

use DateTimeImmutable;
use WHMCS\Module\Addon\Pressable\Api\Pressable as Api;
use WHMCS\Module\Addon\Pressable\Api\Whmcs;

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

  private function emptyResult(): string
  {
    return "<p>{$this->getAddButton()}</p><p>No Sites</p>";
  }

  private function getAddButton(): string
  {
    $url = "{$this->postUrl}&_action=showSiteCreateForm";

    return "<a href=\"{$url}\"><button class=\"btn btn-info\">Add a Site</button></a>";
  }

  private function getDeleteButton(array $item): string
  {
    if (! in_array($item['state'], ['live', 'disabled'])) {
      return '';
    }

    return <<<CONTENT
<form method="post" action="{$this->postUrl}">
  <input type="hidden" name="_action" value="deleteSite" />
  <input type="hidden" name="siteId" value="{$item['id']}" />
  <input class="btn btn-danger" type="submit" title="Delete" value="Delete" />
</form>
CONTENT;
  }

  private function getLabel(string $state): string
  {
    switch ($state) {
      case 'live':
        return 'active';
      case 'disabled':
        return 'pending';
      default:
        return 'suspended';
    }
  }

  private function getPagination(): string
  {
    if (
      $this->pagination['totalItems'] < 1 ||
      empty($this->list) ||
      count($this->list) >= $this->pagination['totalItems']
    ) {
      return '';
    }

    $first = ($this->pagination['currentPage'] - 1) * $this->pagination['perPage'] + 1;
    $last = $this->pagination['currentPage'] * $this->pagination['perPage'];
    $last = min($last, $this->pagination['totalItems']);

    $range = "{$first}-{$last}";
    $buttons = "{$this->getPaginationPreviousButton()} {$this->getPaginationNextButton()}";

    return "Showing {$range} of {$this->pagination['totalItems']} {$buttons}";
  }

  private function getPaginationNextButton(): ?string
  {
    $next = $this->pagination['nextPage'] ?? null;
    if (empty($next)) {
      return null;
    }

    $url = "{$this->postUrl}&page={$next}";

    return <<<CONTENT
  <a href="{$url}"><button class="btn btn-sm btn-light">Next</button></a>
CONTENT;
  }

  private function getPaginationPreviousButton(): ?string
  {
    $prev = ($this->pagination['currentPage'] ?? 1) - 1;
    if ($prev <= 0) {
      return null;
    }

    $url = "{$this->postUrl}&page={$prev}";

    return <<<CONTENT
  <a href="{$url}"><button class="btn btn-sm btn-light">Prev</button></a>
CONTENT;
  }

  private function getStateButton(array $item): string
  {
    $action = '';
    $displayAction = '';

    if ($item['state'] === 'live') {
      $action = 'suspendSite';
      $displayAction = 'Suspend';
    }
    if ($item['state'] === 'disabled') {
      $action = 'unsuspendSite';
      $displayAction = 'Unsuspend';
    }

    if (empty($action)) {
      return '';
    }

    return <<<CONTENT
<form method="post" action="{$this->postUrl}">
  <input type="hidden" name="_action" value="{$action}" />
  <input type="hidden" name="siteId" value="{$item['id']}" />
  <input class="btn btn-warning" type="submit" value="{$displayAction}" />
</form>
CONTENT;
  }

  private function getTableBody(): string
  {
    $rows = [];

    foreach ($this->list as $item) {
      $date = new DateTimeImmutable($item['created']);

      $rows[] = "<tr>
        <td>{$item['name']}</td>
        <td>{$this->getWhmcsClientName($item)}</td>
        <td>{$item['datacenterCode']}</td>
        <td>
          {$item['ipAddressOne']}<br />
          {$item['ipAddressTwo']}
        </td>
        <td>{$date->format('r')}</td>
        <td class=\"text-center\">
          <span class=\"label {$this->getLabel($item['state'])}\">{$item['state']}</span>
        </td>
        <td class=\"text-center\">{$this->getStateButton($item)}</td>
        <td class=\"text-center\">{$this->getDeleteButton($item)}</td>
      </tr>";
    }

    return implode("\n", $rows);
  }

  private function getTableHead(): string
  {
    return '<tr>
      <th>Name</th>
      <th>Client</th>
      <th>DC</th>
      <th>IP</th>
      <th>Created</th>
      <th>State</th>
      <th>Action</th>
      <th>Delete</th>
    </tr>';
  }

  private function getWhmcsClientIdFromTags(array $tags): ?int
  {
    $idTag = null;
    $prefix = Api::SITE_TAG_CLIENT_PREFIX;

    foreach ($tags as $tag) {
      if (strpos($tag['name'], $prefix) === 0) {
        $idTag = $tag['name'];
        break;
      }
    }

    return isset($idTag)
      ? (int)substr($idTag, strlen($prefix))
      : null;
  }

  private function getWhmcsClientName(array $site): ?string
  {
    $id = $this->getWhmcsClientIdFromTags($site['tags']);
    if ($id < 1) {
      return null;
    }

    $client = Whmcs::getClient($id);

    return empty($client->id)
      ? null
      // @phpstan-ignore-next-line
      : "{$id}: {$client->firstname} {$client->lastname}";
  }

  private function tableResult(): string
  {
    $table = '<div class="tablebg"><table class="datatable table table-list">' .
      $this->getTableHead() . $this->getTableBody() .
      '</table></div>';

    return "<p>{$this->getAddButton()}</p>{$table}<p>{$this->getPagination()}</p>";
  }

  public function __toString(): string
  {
    return empty($this->list)
      ? $this->emptyResult()
      : $this->tableResult();
  }

}
