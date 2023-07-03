<?php

declare(strict_types = 1);

namespace Pressable\Whmcs\Admin\Result;

class SiteCreateForm implements Result
{

  private const _OPTIONS = [
    'datacenter_code' => 'Datacenter',
    'install' => 'Site Install',
    'php_version' => 'PHP Version',
  ];

  public function __construct(private array $options, private string $postUrl)
  {}

  private function generateOptions(string $name, string $display, array $options): string
  {
    $opts = '';
    foreach ($options as $val => $show) {
      $opts .= "<option value=\"{$val}\">{$show}</option>";
    }

    return <<<CONTENT
        <label>
          {$display}
          <select name="{$name}">
            {$opts}
          </select>
        </label>
      CONTENT;
  }

  public function __toString(): string
  {
    $options = '';
    foreach (self::_OPTIONS as $name => $display) {
      if (isset($this->options[$name])) {
        $options .= $this->generateOptions($name, $display, $this->options[$name]);
      }
    }

    return <<<CONTENT
        <form method="post" action="{$this->postUrl}">
          <label>Name <input type="text" name="name" /></label>
          <label><input type="checkbox" name="staging" value="true" /> Staging</label>
          {$options}
        </form>
      CONTENT;
  }

}
