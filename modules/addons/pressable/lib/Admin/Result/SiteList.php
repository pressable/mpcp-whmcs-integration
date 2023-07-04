<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Result;

use DateTimeImmutable;
use WHMCS\Database\Capsule;

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

    return "<a href=\"{$url}\"><button>Add a Site</button></a>";
  }

  private function getCss(): string
  {
    return <<<'CONTENT'
<style type="text/css">
  table, th, td {
    border: 2px solid #000;
    border-collapse: collapse;
  }
  th, td {padding: .2em .5em;}
</style>
CONTENT;
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
  <input type="submit" title="Delete" value="&#128465;" />
</form>
CONTENT;
  }

  private function getPagination(): string
  {
    if (
      $this->pagination['totalItems'] < 1 ||
      empty($this->list) ||
      count($this->list) <= $this->pagination['totalItems']
    ) {
      return '';
    }

    $first = ($this->pagination['currentPage'] - 1) * $this->pagination['perPage'] + 1;
    $last = $this->pagination['currentPage'] * $this->pagination['perPage'];
    $last = min($last, $this->pagination['totalItems']);

    $range = "{$first}-{$last}";

    return "Showing {$range} of {$this->pagination['totalItems']}";
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
<form style="float: right" method="post" action="{$this->postUrl}">
  <input type="hidden" name="_action" value="{$action}" />
  <input type="hidden" name="siteId" value="{$item['id']}" />
  <input type="submit" title="{$displayAction}" value="&#9212;" />
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
        <td>{$item['ipAddress']}</td>
        <td>{$date->format('r')}</td>
        <td>
          <span style=\"vertical-align: middle; margin-right: .5em;\">{$item['state']}</span>
          {$this->getStateButton($item)}
        </td>
        <td style=\"text-align: center\">{$this->getDeleteButton($item)}</td>
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
      <th>Delete</th>
    </tr>';
  }

  private function getWhmcsClientIdFromTags(array $tags): ?string
  {
    $idTag = null;
    $prefix = 'whmcs.client.';

    foreach ($tags as $tag) {
      if (strpos($tag['name'], $prefix) === 0) {
        $idTag = $tag['name'];
        break;
      }
    }

    return isset($idTag)
      ? substr($idTag, strlen($prefix))
      : null;
  }

  private function getWhmcsClientName(array $site): ?string
  {
    $id = $this->getWhmcsClientIdFromTags($site['tags']);

    $client = Capsule::table('tblclients')->find($id);

    return empty($client->id)
      ? null
      : "{$id}: {$client->firstname} {$client->lastname}";
  }

  private function tableResult(): string
  {
    $table = "<table>{$this->getTableHead()}{$this->getTableBody()}</table>";

    return "{$this->getCss()}<p>{$this->getAddButton()}</p>{$table}<p>{$this->getPagination()}</p>";
  }

  public function __toString(): string
  {
    return empty($this->list)
      ? $this->emptyResult()
      : $this->tableResult();
  }

}
