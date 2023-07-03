<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Result;

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
  <input type="submit" title="{$displayAction}" value="&#9212;" />
</form>
CONTENT;
  }

  private function getTableBody(): string
  {
    $rows = [];

    foreach ($this->list as $item) {
      $rows[] = "<tr>
        <td>{$item['name']}</td>
        <td>{$item['datacenterCode']}</td>
        <td>{$item['ipAddress']}</td>
        <td>{$item['created']}</td>
        <td>{$item['state']} {$this->getStateButton($item)}</td>
        <td>{$this->getDeleteButton($item)}</td>
      </tr>";
    }

    return implode("\n", $rows);
  }

  private function getTableHead(): string
  {
    return '<tr>
      <th>Name</th>
      <th>DC</th>
      <th>IP</th>
      <th>Created</th>
      <th>State</th>
      <th></th>
    </tr>';
  }

  public function __toString(): string
  {
    $table = "<table>{$this->getTableHead()}{$this->getTableBody()}</table>";

    return "{$table}<p>{$this->getPagination()}</p>";
  }

}
