<?php

declare(strict_types = 1);

namespace WHMCS\Module\Addon\Pressable\Admin\Result;

class SiteCreateForm implements Result
{

  private const _OPTIONS = [
    'client_id' => 'Client',
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

  private function generateOptions(
    string $name,
    string $display,
    array $options,
    bool $includeDefault = true
  ): string
  {
    $defaultText = $includeDefault
      ? '(Use Default)'
      : '(Please Select)';
    $opts = "<option selected value=\"\">{$defaultText}</option>";

    foreach ($options as $val => $show) {
      $opts .= "<option value=\"{$val}\">{$show}</option>";
    }

    $required = $includeDefault
      ? ''
      : 'required';

    return <<<CONTENT
  <label>
    {$display}
    <select {$required} name="{$name}">
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
        $includeDefault = $name !== 'client_id';
        $options .= $this->generateOptions($name, $display, $this->options[$name], $includeDefault);
      }
    }

    return <<<CONTENT
  <form method="post" action="{$this->postUrl}">
    <input type="hidden" name="_action" value="createSite" />
    <label>Name <input required type="text" name="name" /></label><br />
    <label>Staging <input type="checkbox" name="staging" value="true" /></label><br />
    {$options}
    <input type="submit" value="Create Site" />
    <a href="{$this->postUrl}"><button type="button">Cancel</button></a>
  </form>
CONTENT;
  }

}
