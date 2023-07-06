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
  <tr>
    <td class="fieldlabel">{$display}</td>
    <td class="fieldarea">
      <select class="form-control select-inline" {$required} name="{$name}">{$opts}</select>
    </td>
  </tr>
CONTENT;
  }

  private function getSelectElements(): string
  {
    $output = '';

    foreach (self::_OPTIONS as $name => $display) {
      if (isset($this->options[$name])) {
        $includeDefault = $name !== 'client_id';
        $output .= $this->generateOptions($name, $display, $this->options[$name], $includeDefault);
      }
    }

    return $output;
  }

  public function __toString(): string
  {
    return <<<CONTENT
  <form method="post" action="{$this->postUrl}">
    <input type="hidden" name="_action" value="createSite" />
    <table class="form" width="100%">
      <tr>
        <td class="fieldlabel">Name</td>
        <td class="fieldarea"><input required type="text" name="name" /></td>
      </tr>
      <tr>
        <td class="fieldlabel">Staging</td>
        <td class="fieldarea"><input type="checkbox" name="staging" value="true" /></td>
      </tr>
      {$this->getSelectElements()}
    </table>
    <div class="btn-container">
      <input class="btn btn-primary" type="submit" value="Create Site" />
      <a href="{$this->postUrl}"><button class="btn btn-secondary" type="button">Cancel</button></a>
    </div>
  </form>
CONTENT;
  }

}
