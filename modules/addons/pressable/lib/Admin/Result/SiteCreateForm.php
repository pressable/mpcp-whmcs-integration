<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Result;

class SiteCreateForm implements Result
{

  private const _OPTIONS = [
    'datacenter_code' => 'Datacenter',
    'install' => 'Site Install',
    'php_version' => 'PHP Version',
  ];

  /** @var array */
  private $options;

  /** @var string */
  private $postUrl;

  public function __construct(array $options, string $postUrl)
  {
    $this->options = $options;
    $this->postUrl = $postUrl;
  }

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
  </label><br />
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
  <form style="margin-bottom: 1em;" method="post" action="{$this->postUrl}">
    <input type="hidden" name="_action" value="createSite" />
    <label>Name <input type="text" name="name" /></label><br />
    <label>Staging <input type="checkbox" name="staging" value="true" /></label><br />
    {$options}
    <input type="submit" value="Create a Site" />
  </form>
CONTENT;
  }

}
