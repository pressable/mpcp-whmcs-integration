<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin\Result;

class SiteList implements Result
{

  public function __construct(private array $list, private array $pagination)
  {}

  private function getPagination(): string
  {
    if ($this->pagination['totalItems'] < 1 || empty($this->list)) {
      return '';
    }

    $first = ($this->pagination['currentPage'] - 1) * $this->pagination['perPage'] + 1;
    $last = $this->pagination['currentPage'] * $this->pagination['perPage'];
    $last = min($last, $this->pagination['totalItems']);

    $range = "{$first}-{$last}";

    return "Showing {$range} of {$this->pagination['totalItems']}";
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
        <td>{$item['state']}</td>
        <td></td>
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
