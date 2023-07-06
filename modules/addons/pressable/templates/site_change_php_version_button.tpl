<script language="javascript" type="text/javascript">
{literal}
  function togglePhpVersionForm() {
    $('#pressable-change-php-version-form').toggle();
    $('#pressable-change-php-version-button').toggle();
  }
{/literal}
</script>

<button class="btn btn-warning btn-sm" id="pressable-change-php-version-button" onclick="togglePhpVersionForm()">Change</button>
<div id="pressable-change-php-version-form" style="display: none">
  <form method="post" action="{$url}">
    <input type="hidden" name="_action" value="updateSite" />
    <input type="hidden" name="siteId" value="{$siteId}" />

    <select class="form-control select-inline" name="php_version">
      {foreach from=$versions item=item}
        <option value="{$item}" {if $item == $currentVersion}selected disabled{/if}>{$item}</option>
      {/foreach}
    </select>
    <input class="btn btn-primary" type="submit" value="Change PHP Version" />
    <button class="btn btn-secondary" onclick="togglePhpVersionForm()" type="button">Cancel</button>
  </form>
</div>
